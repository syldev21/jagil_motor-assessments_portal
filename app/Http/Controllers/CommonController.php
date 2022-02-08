<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\Assessment;
use App\AssessmentItem;
use App\CarModel;
use App\Claim;
use App\ClaimFormTracker;
use App\Company;
use App\Conf\Config;
use App\CustomerMaster;
use App\Document;
use App\Escalation;
use App\Garage;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\JobDetail;
use App\PriceChange;
use App\SalvageRegister;
use App\User;
use App\Utility;
use App\Vendor;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CommonController extends Controller
{
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }

    public function fetchReInspections(Request $request)
    {
        $id = Auth::id();
        $assessmentStatusIDs = array(Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'], Config::$STATUSES['ASSESSMENT']['APPROVED']['id']);

        try {
            if (!isset($request->fromDate) && !isset($request->toDate) && !isset($request->regNumber)) {
                $asmts = Assessment::whereIn('assessmentStatusID', $assessmentStatusIDs)
                    ->where('segment', '=', Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
                $assessments = Assessment::where("assessedBy", "!=", $id)
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->where('dateCreated', ">=", Carbon::now()->subDays(Config::DATE_RANGE))
                    ->where('active','=',Config::ACTIVE)
                    ->where('isTheft','=',Config::INACTIVE)
                    ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('reInspection')->orderBy('dateCreated', 'DESC')->get();
            } elseif (isset($request->regNumber)) {
//              $regNo = preg_replace("/\s+/", "", $request->regNumber);
                $registrationNumber = preg_replace("/\s+/", "", $request->regNumber);
                $regNoArray = preg_split('/(?=\d)/', $registrationNumber, 2);
                $regNo1 = isset($regNoArray[0]) ? $regNoArray[0] : '';
                $regNo2 = isset($regNoArray[1]) ? $regNoArray[1] : '';
                $regNo = $request->regNumber;
                $claimids = Claim::where(function ($a) use ($regNo, $regNo1, $regNo2) {
                    $a->where('vehicleRegNo', 'like', '%' . $regNo . '%')
                        ->where('active','=',Config::ACTIVE);
                })->orWhere(function ($a) use ($regNo1, $regNo2) {
                    $a->where('vehicleRegNo', 'like', '%' . $regNo1 . '%')
                        ->where('vehicleRegNo', 'like', '%' . $regNo2 . '%')
                        ->where('active','=',Config::ACTIVE);
                })->pluck('id')->toArray();

//              $claimids = Claim::where('vehicleRegNo','like', '%'.$request->regNumber.'%')->pluck('id')->toArray();

                $asmts = Assessment::whereIn('assessmentStatusID', $assessmentStatusIDs)
                    ->where('segment', '=', Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
                $assessments = Assessment::where("assessedBy", "!=", $id)
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereIn('claimID', $claimids)
                    ->where('active','=',Config::ACTIVE)
                    ->where('isTheft','=',Config::INACTIVE)
                    ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('reInspection')->orderBy('dateCreated', 'DESC')->get();
            } elseif (isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber)) {
                $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
                $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
                $asmts = Assessment::whereIn('assessmentStatusID', $assessmentStatusIDs)
                    ->where('segment', '=', Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
                $assessments = Assessment::where("assessedBy", "!=", $id)
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereBetween('dateCreated', [$fromDate, $toDate])
                    ->where('active','=',Config::ACTIVE)
                    ->where('isTheft','=',Config::INACTIVE)
                    ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('reInspection')->orderBy('dateCreated', 'DESC')->get();
            }
            return view('common.re-inspections', ['assessments' => $assessments, 'assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['APPROVED']['id'], 'asmts' => $asmts]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }

    public function fetchClaimsByType(Request $request)
    {
        $assessmentTypeID = $request->assessmentTypeID;
        try {
            $assessments = Assessment::where(['assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['APPROVED']['id'], 'assessmentTypeID' => $assessmentTypeID,'active'=>Config::ACTIVE])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->get();
            return view('common.assessment-types', ['assessments' => $assessments, 'assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['APPROVED']['id'], 'assessmentTypeID' => $assessmentTypeID]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }

    public function sendNotification(Request $request)
    {

        $emails = isset($request->emails) ? $request->emails : array();
        $ccEmails = isset($request->ccEmails) ? $request->ccEmails : array();
        $message = $request->message;
        $assessmentID = $request->assessmentID;
        $assessment = Assessment::where(['id'=>$assessmentID])->first();
        $claim = Claim::where(['id'=>$assessment->claimID])->first();
        array_push($ccEmails,Auth::user()->email);
        $footer = "<p>
               <br/>
               <br/>
             <i>Regards <br/>
             ".Auth::user()->name."<br/>
             <b>Claims ".Auth::user()->roles->pluck('name')[0]."</b></i>
         </p>";

        $msg = $message.$footer;


        $message = [
            'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
            'from' => Config::JUBILEE_NO_REPLY_EMAIL,
            'to' => $emails,
            'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
            'cc' => $ccEmails,
            'html' => $msg,
        ];
        Escalation::create([
            'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
            'to' => json_encode($emails),
            'cc' => json_encode($ccEmails),
            'message' => $msg,
            'createdBy'=> Auth::user()->id,

        ]);

        InfobipEmailHelper::sendEmail($message);
        $logData = array(
            "vehicleRegNo" => $claim->vehicleRegNo,
            "claimNo" => $claim->claimNo,
            "policyNo" => $claim->policyNo,
            "userID" => Auth::user()->id,
            "role" => Auth::user()->roles->pluck('name')[0],
            "activity" => Config::ACTIVITIES['GENERIC_NOTIFICATION'],
            "notification" => $message['html'],
            "notificationTo" => json_encode($emails),
            "cc" => json_encode($ccEmails),
            "notificationType" => Config::NOTIFICATION_TYPES['EMAIL'],
        );
        $this->functions->logActivity($logData);
        // SMSHelper::sendSMS('Dear Sir, kindly proceed with repairs as per attached on the email',$userDetail['MSISDN']);
        // $user = User::where(["id" => $userDetail['id']])->first();
        // Notification::send($user, new ClaimApproved($claim));

        $flag = true;
        // }
        if ($flag == true)
            $response = array(
                "STATUS_CODE" => Config::SUCCESS_CODE,
                "STATUS_MESSAGE" => "An email was sent successfuly"
            );
        else {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
        }
        return json_encode($response);
    }

    public function fetchEscalations(Request $request){
        $escalations = Escalation::all();
        return view('common.escalations', ['escalations'=>$escalations]);
    }


    public function showActivityLog(Request $request)
    {
        $logs = ActivityLog::orderBy('dateCreated', 'DESC')->get();
        return view('common.activity-log', ['logs' => $logs]);
    }

    public function fetchLogDetails(Request $request)
    {
        $activityLogID = $request->activityLogID;
        $activityLog = ActivityLog::where(["id" => $activityLogID])->first();
        return view('common.activity-log-detail', ['activityLog' => $activityLog]);
    }

    public function filterLogs(Request $request)
    {
        try {
            if (!isset($request->fromDate) && !isset($request->toDate) && !isset($request->regNumber))
            {
                $logs = ActivityLog::orderBy('dateCreated', 'DESC')->get();
            }elseif (isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber))
            {
                $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
                $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
                $logs = ActivityLog::whereBetween('dateCreated', [$fromDate, $toDate])
                    ->orderBy('dateCreated', 'DESC')->get();
            }elseif(isset($request->regNumber) && !isset($request->fromDate) && !isset($request->toDate))
            {
                $logs = ActivityLog::where(['vehicleRegNo' => $request->regNumber])->orderBy('dateCreated', 'DESC')->get();
            }elseif (isset($request->fromDate) && isset($request->toDate) && isset($request->regNumber))
            {
                $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
                $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
                $logs = ActivityLog::where('vehicleRegNo','=',$request->regNumber)
                    ->whereBetween('dateCreated', [$fromDate, $toDate])
                    ->orderBy('dateCreated', 'DESC')->get();

            }else
            {
                $logs = array();
            }
            return view('common.filtered-activity-log', ['logs' => $logs]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to filter logs. Error message " . $e->getMessage());
        }
    }

    public function flaggedAssessments(Request $request)
    {
        $provisonal =Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'];
        $changesDue = Config::$STATUSES["ASSESSMENT"]["CHANGES-DUE"]["id"];
        $assigned = Config::$STATUSES["ASSESSMENT"]["ASSIGNED"]["id"];
        $draft = Config::$STATUSES["ASSESSMENT"]["IS-DRAFT"]["id"];
        $assessed= Config::$STATUSES["ASSESSMENT"]["ASSESSED"]["id"];
        $flagThreshold = Carbon::now()->subDays(Config::FLAG_THRESHOLD)->toDateTimeString();
        $userID = Auth::user()->id;
        if (!isset($request->fromDate) && !isset($request->toDate) && !isset($request->regNumber)) {
            $assessments = Assessment::where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                ->where('active','=',Config::ACTIVE)
                ->where('isTheft','=',Config::INACTIVE)
                ->whereRaw(
                    ("CASE WHEN assessmentStatusID='$provisonal' THEN approvedAt <  '$flagThreshold'
             WHEN assessmentStatusID='$changesDue' THEN changeRequestAt < '$flagThreshold'
             WHEN assessmentStatusID='$assigned' THEN dateCreated < '$flagThreshold'
             WHEN assessmentStatusID='$draft' THEN assessedAt < '$flagThreshold'
             WHEN assessmentStatusID='$assessed' THEN assessedAt < '$flagThreshold'
             ELSE 0 END"));
        }elseif (isset($request->regNumber))
        {
//                $regNo = preg_replace("/\s+/", "", $request->regNumber);
            $registrationNumber=preg_replace("/\s+/", "", $request->regNumber);
            $regNoArray = preg_split('/(?=\d)/', $registrationNumber, 2);
            $regNo1 =isset($regNoArray[0]) ? $regNoArray[0] : '';
            $regNo2 = isset($regNoArray[1]) ? $regNoArray[1] : '';
            $regNo = $request->regNumber;
            $claimids = Claim::where(function($a) use ($regNo,$regNo1,$regNo2) {
                $a->where('vehicleRegNo','like', '%'.$regNo.'%');
            })->orWhere(function($a)use ($regNo1,$regNo2) {
                $a->where('vehicleRegNo','like', '%'.$regNo1.'%')->where('vehicleRegNo','like', '%'.$regNo2.'%');
            })->pluck('id')->toArray();
            $assessments = Assessment::where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                ->where('active','=',Config::ACTIVE)
                ->where('isTheft','=',Config::INACTIVE)
                ->whereIn('claimID', $claimids)
                ->whereRaw(
                    ("CASE WHEN assessmentStatusID='$provisonal' THEN approvedAt <  '$flagThreshold'
             WHEN assessmentStatusID='$changesDue' THEN changeRequestAt < '$flagThreshold'
             WHEN assessmentStatusID='$assigned' THEN dateCreated < '$flagThreshold'
             WHEN assessmentStatusID='$draft' THEN assessedAt < '$flagThreshold'
             WHEN assessmentStatusID='$assessed' THEN assessedAt < '$flagThreshold'
             ELSE 0 END"));
        }elseif(isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber))
        {
            $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
            $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
            $assessments = Assessment::where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                ->where('active','=',Config::ACTIVE)
                ->where('isTheft','=',Config::INACTIVE)
                ->whereBetween('dateCreated', [$fromDate, $toDate])
                ->whereRaw(
                    ("CASE WHEN assessmentStatusID='$provisonal' THEN approvedAt <  '$flagThreshold'
             WHEN assessmentStatusID='$changesDue' THEN changeRequestAt < '$flagThreshold'
             WHEN assessmentStatusID='$assigned' THEN dateCreated < '$flagThreshold'
             WHEN assessmentStatusID='$draft' THEN assessedAt < '$flagThreshold'
             WHEN assessmentStatusID='$assessed' THEN assessedAt < '$flagThreshold'
             ELSE 0 END"));
        }else
        {
            $assessments = array();
        }
        if(Auth::user()->hasRole('Assessor'))
        {
            $assessments = $assessments->where('assessedBy','=',$userID)->get();
        }else
        {
            $assessments = $assessments->get();
        }
        return view('common.flagged-assessments', ['assessments' => $assessments]);
    }
    public function flaggedSupplementaries(Request $request)
    {
        $provisonal =Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'];
        $changesDue = Config::$STATUSES["ASSESSMENT"]["CHANGES-DUE"]["id"];
        $assigned = Config::$STATUSES["ASSESSMENT"]["ASSIGNED"]["id"];
        $draft = Config::$STATUSES["ASSESSMENT"]["IS-DRAFT"]["id"];
        $assessed= Config::$STATUSES["ASSESSMENT"]["ASSESSED"]["id"];
        $flagThreshold = Carbon::now()->subDays(Config::FLAG_THRESHOLD)->toDateTimeString();
        $userID = Auth::user()->id;
        if (!isset($request->fromDate) && !isset($request->toDate) && !isset($request->regNumber)) {
            $assessments = Assessment::where('segment', "=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                ->where('active','=',Config::ACTIVE)
                ->whereRaw(
                    ("CASE WHEN assessmentStatusID='$provisonal' THEN approvedAt <  '$flagThreshold'
             WHEN assessmentStatusID='$changesDue' THEN changeRequestAt < '$flagThreshold'
             WHEN assessmentStatusID='$assigned' THEN dateCreated < '$flagThreshold'
             WHEN assessmentStatusID='$draft' THEN assessedAt < '$flagThreshold'
             WHEN assessmentStatusID='$assessed' THEN assessedAt < '$flagThreshold'
             ELSE 0 END"));
        }elseif (isset($request->regNumber))
        {
//                $regNo = preg_replace("/\s+/", "", $request->regNumber);
            $registrationNumber=preg_replace("/\s+/", "", $request->regNumber);
            $regNoArray = preg_split('/(?=\d)/', $registrationNumber, 2);
            $regNo1 =isset($regNoArray[0]) ? $regNoArray[0] : '';
            $regNo2 = isset($regNoArray[1]) ? $regNoArray[1] : '';
            $regNo = $request->regNumber;
            $claimids = Claim::where(function($a) use ($regNo,$regNo1,$regNo2) {
                $a->where('vehicleRegNo','like', '%'.$regNo.'%');
            })->orWhere(function($a)use ($regNo1,$regNo2) {
                $a->where('vehicleRegNo','like', '%'.$regNo1.'%')->where('vehicleRegNo','like', '%'.$regNo2.'%');
            })->pluck('id')->toArray();
            $assessments = Assessment::where('segment', "=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                ->where('active','=',Config::ACTIVE)
                ->whereIn('claimID', $claimids)
                ->whereRaw(
                    ("CASE WHEN assessmentStatusID='$provisonal' THEN approvedAt <  '$flagThreshold'
             WHEN assessmentStatusID='$changesDue' THEN changeRequestAt < '$flagThreshold'
             WHEN assessmentStatusID='$assigned' THEN dateCreated < '$flagThreshold'
             WHEN assessmentStatusID='$draft' THEN assessedAt < '$flagThreshold'
             WHEN assessmentStatusID='$assessed' THEN assessedAt < '$flagThreshold'
             ELSE 0 END"));
        }elseif(isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber))
        {
            $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
            $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
            $assessments = Assessment::where('segment', "=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                ->where('active','=',Config::ACTIVE)
                ->whereBetween('dateCreated', [$fromDate, $toDate])
                ->whereRaw(
                    ("CASE WHEN assessmentStatusID='$provisonal' THEN approvedAt <  '$flagThreshold'
             WHEN assessmentStatusID='$changesDue' THEN changeRequestAt < '$flagThreshold'
             WHEN assessmentStatusID='$assigned' THEN dateCreated < '$flagThreshold'
             WHEN assessmentStatusID='$draft' THEN assessedAt < '$flagThreshold'
             WHEN assessmentStatusID='$assessed' THEN assessedAt < '$flagThreshold'
             ELSE 0 END"));
        }else
        {
            $assessments = array();
        }
        if(Auth::user()->hasRole('Assessor'))
        {
            $assessments = $assessments->where('assessedBy','=',$userID)->get();
        }else
        {
            $assessments = $assessments->get();
        }
        return view('common.flagged-supplementaries', ['assessments' => $assessments]);
    }

    public function getUsers(Request $request)
    {
        $emails = array();
        $assessment  = Assessment::where(['id'=>$request->assessmentID])->first();
        $claim = Claim::where(['id'=>$assessment->claimID])->first();
        $garageEmail = Garage::where(['id'=>$claim->garageID])->first()->email;
        array_push($emails,array("email"=>$garageEmail,"name"=>"Garage_".$garageEmail));
        $adjusterEmail = User::where(['id'=>$claim->createdBy])->first()->email;
        array_push($emails,array("email"=>$adjusterEmail,"name"=>"Adjuster_".$adjusterEmail));
        $customerEmail = CustomerMaster::where(['customerCode'=>$claim->customerCode])->first()->email;
        array_push($emails,array("email"=>$customerEmail,"name"=>"Customer_".$customerEmail));
        if(isset($assessment->assessedBy))
        {
            $assessorEmail = User::where(['id'=>$assessment->assessedBy])->first()->email;
            array_push($emails,array("email"=>$assessorEmail,"name"=>"Assessor_".$assessorEmail));
        }
        if(isset($assessment->approvedBy))
        {
            $headAssessorEmail = User::where(['id'=>$assessment->approvedBy])->first()->email;
            array_push($emails,array("email"=>$headAssessorEmail,"name"=>"Provisional approver_".$headAssessorEmail));
        }
        if(isset($assessment->finalApprovalBy))
        {
            $assessmentManagerEmail = User::where(['id'=>$assessment->finalApprovalBy])->first()->email;
            array_push($emails,array("email"=>$assessmentManagerEmail,"name"=>"Final approver_".$assessmentManagerEmail));
        }
        $users = User::select('email')->get();
        return view('common.user-list',['users'=>$users,'emails'=>$emails]);
    }
    public function getClaimsWithoutClaimForm()
    {
        $claimIds =Document::select('claimID')->where(['documentType'=>Config::$DOCUMENT_TYPES['PDF']['ID'],'pdfType'=>Config::PDF_TYPES['CLAIM_FORM']['ID']])->pluck('claimID')->toArray();

        $claims = Claim::select('id','claimNo','policyNo','vehicleRegNo','customerCode','createdBy','dateCreated')
            ->where(["active" =>Config::ACTIVE])
            ->whereNotIn('id', $claimIds);
        $idsWithoutClaimForm = $claims->pluck('id')->toArray();
        $claims = $claims->get();
        foreach ($claims as $claim)
        {
            $claimTracker=ClaimFormTracker::where(['claimNo'=>$claim->claimNo])->first();
            if(isset($claimTracker->id))
            {

            }else
            {
                ClaimFormTracker::create([
                    "claimID" =>$claim->id,
                    "claimNo" =>$claim->claimNo,
                    "policyNo" => $claim->policyNo,
                    "vehicleRegNo" => $claim->vehicleRegNo,
                    "customerCode" => $claim->customerCode,
                    "notificationCount" => Config::INACTIVE,
                    "status" => Config::INACTIVE,
                    "createdBy" => $claim->createdBy,
                    "dateCreated" => $claim->dateCreated
                ]);
//                $documentsArray [] = $data;
            }
        }
          ClaimFormTracker::whereNotIn('claimID', $idsWithoutClaimForm)->delete();
//        $collection = collect($documentsArray);
//        $save = ClaimFormTracker::insert($collection->values()->all());
    }
    public function sendClaimFormNotification()
    {
        $threshold = Carbon::now()->subDays(1)->toDateTimeString();
        $claims = ClaimFormTracker::where('dateCreated','<',$threshold)
            ->where(['status'=>Config::INACTIVE])->get();
        if(count($claims)>0)
        {
            foreach ($claims as $claim)
            {
                if($claim->notificationCount ==0)
                {
                    $user = User::where(['id'=>$claim->createdBy])->first();
                    $email = [
                        'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                        'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                        'to' => $user->email,
                        'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                        'html' => "Dear ".$user->firstName."<br/>
                                   A polite reminder to upload claim form for vehicleRegNo : ".$claim->vehicleRegNo." for claimNo : ".$claim->claimNo."
                                   <br/>
                                   Regards<br/>
                                   IT Department Jubilee Insurance",
                    ];
                    InfobipEmailHelper::sendEmail($email, $email);
                    ClaimFormTracker::where(["claimID"=>$claim->claimID])->update([
                        "notificationCount"=>1
                    ]);
                }elseif ($claim->notificationCount == 1)
                {
                    $user = User::where(['id'=>$claim->createdBy])->first();
                    ClaimFormTracker::where(["claimID"=>$claim->claimID])->update([
                        "notificationCount"=>2
                    ]);
                    $email = [
                        'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                        'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                        'to' => $user->email,
                        'cc' => 'Josphat.Njoroge@jubileekenya.com',
                        'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                        'html' => "Dear ".$user->firstName."<br/>
                                   A 2nd polite reminder to upload claim form for vehicleRegNo : ".$claim->vehicleRegNo." for claimNo : ".$claim->claimNo."
                                   <br/>
                                   Regards<br/>
                                   IT Department Jubilee Insurance",
                    ];
                    InfobipEmailHelper::sendEmail($email, $email);
                }elseif ($claim->notificationCount == 1)
                {
                    ClaimFormTracker::where(["claimID"=>$claim->claimID])->update([
                        "status"=>Config::ACTIVE
                    ]);
                }
            }
        }
    }
    public function fetchDMSDocuments(Request $request)
    {
        $claimNo = $request->claimNo;
        $policyNo = $request->policyNo;
        $data = array(
            "claimNo"=>$claimNo,
            "policyNo" =>$policyNo
        );
        $utility = new Utility();
        $response = $utility->getData($data, '/api/v1/b2b/general/dms/fetchDMSDocuments', 'POST');
        $claim_data = json_decode($response->getBody()->getContents());

        return view('common.dms-documents',['documents'=>$claim_data]);
    }
    public function fetchModelsByMake(Request $request)
    {
        $carMakeCode = $request->carMakeCode;
        $carModels= CarModel::where(["makeCode"=>$carMakeCode])->get();
        return view('common.car-models',["carModels"=>$carModels]);
    }

    public function subrogationReport(Request $request)
    {
        $assessmentID= $request->assessmentID;
        $assessment = Assessment::where(["id"=>$assessmentID])->first();
        $claim = Claim::where(["id"=>$assessment->claimID])->with('customer')->first();
        $company = Company::where(["id"=>$assessment->companyID])->first();
        return view('common.subrogation',['assessment'=>$assessment,'claim'=>$claim,'company'=>$company]);

    }
    public function submitSalvageRequest(Request $request)
    {
        // dd($request->insuredRetainedSalvage);
        try {
            if(isset($request->claimID) && isset($request->logbookReceived))
            {
                $claimId = $request->claimID;
                $logbookReceived = $request->logbookReceived;
                $documentsReceived = $request->documentsReceived;
                $dateRecovered = Carbon::parse($request->dateRecovered)->format('Y-m-d H:i:s');
                $location = $request->location;
                $insuredInterestedWithSalvage = $request->insuredInterestedWithSalvage;
                $insuredRetainedSalvage = $request->insuredRetainedSalvage;
                $claim = Claim::where(["id"=>$claimId])->first();
                $salvageRegister = SalvageRegister::where(['claimID'=>$claimId])->first();
                if(!isset($salvageRegister->id))
                {
                    if(isset($claim->id))
                    {
                        SalvageRegister::create([
                            "claimID"=>$claimId,
                            "vehicleRegNo"=>$claim->vehicleRegNo,
                            "claimNo" => $claim->claimNo,
                            "logbookReceived"=>$logbookReceived,
                            "logbookDateReceived"=> $this->functions->curlDate(),
                            "recordsReceived" => $documentsReceived,
                            "insuredInterestedWithSalvage"=>$insuredInterestedWithSalvage,
                            "insuredRetainedSalvage"=>$insuredRetainedSalvage,
                            "dateRecovered" => $dateRecovered,
                            "location"=>$location,
                            "createdBy" => Auth::user()->id,
                            "dateCreated"=>$this->functions->curlDate()
                        ]);
                        Claim::where(["id"=>$claimId])->update([
                            "salvageProcessed"=>Config::ACTIVE,
                            "salvageProcessedDate"=>$this->functions->curlDate(),
                            "salvageProcessedBy"=>Auth::user()->id
                        ]);
                        $response = array(
                            "STATUS_CODE" => Config::SUCCESS_CODE,
                            "STATUS_MESSAGE" => "Record added successfully to Salvage Register"
                        );
                    }else
                    {
                        $response = array(
                            "STATUS_CODE" => Config::NO_RECORDS_FOUND,
                            "STATUS_MESSAGE" => "No record found for the provided claim No"
                        );
                    }
                }else
                {
                    $response = array(
                        "STATUS_CODE" => Config::RECORD_ALREADY_EXISTS,
                        "STATUS_MESSAGE" => "Salvage register already processed"
                    );
                }
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid Payload"
                );
            }
        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to insert to salvage register. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function fetchSalvageRegister(Request $request)
    {
        $vendors = Vendor::where(["status"=>Config::ACTIVE,"type"=>Config::DISPLAY_VENDOR_TYPES['SALVAGE']['ID']])->get();
        $salvageRegisters = SalvageRegister::with('assessment')->with('vendor')->with('claim')->get();
        return view('common.salvage-register',['salvageRegisters'=>$salvageRegisters,'vendors'=>$vendors]);
    }
    public function submitSaleSalvageRequest(Request $request)
    {


    //   dd($request->insuredRetainedSalvage);
        try {
            if(isset($request->salvageID) && isset($request->vendor) && isset($request->cost) && isset($request->logbookReceivedByRecoveryOfficer))
            {
                $salvage = SalvageRegister::where(['id'=>$request->salvageID])->first();
                if(isset($salvage->id))
                {
                    if(!isset($salvage->buyerID))
                    {
                        SalvageRegister::where(['id'=>$request->salvageID])->update([
                            "buyerID"=>$request->vendor,
                            "cost"=>$request->cost,
                            "logbookReceivedByRecoveryOfficer"=>$request->logbookReceivedByRecoveryOfficer,
                            "insuredRetainedSalvage"=>$request->insuredRetainedSalvage,
                            "updatedBy"=>Auth::user()->id,
                            "dateModified"=>$this->functions->curlDate()
                        ]);
                        $response = array(
                            "STATUS_CODE" => Config::SUCCESS_CODE,
                            "STATUS_MESSAGE" => "Salvage Register Updated Successfully"
                        );
                    }else
                    {
                        $response = array(
                            "STATUS_CODE" => Config::RECORD_ALREADY_EXISTS,
                            "STATUS_MESSAGE" => "Salvage Already Sold"
                        );
                    }
                }else
                {
                    $response = array(
                        "STATUS_CODE" => Config::NO_RECORDS_FOUND,
                        "STATUS_MESSAGE" => "The request can't be completed at the moment try again later"
                    );
                }
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid data provided"
                );
            }
        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to update salvage register. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function salvageReleaseLetter(Request $request)
    {
        $salvageRegisterID = $request->salvageRegisterID;
        $salvageRegister = SalvageRegister::where(['id'=>$salvageRegisterID])->with('assessment')->with('vendor')->with('claim')->first();
        return view('common.salvage-release-letter',['salvageRegister'=>$salvageRegister]);
    }
    public function viewLPOReport(Request $request)
    {
        $claimID = $request->claimID;
        $claim = Claim::where(['id'=>$claimID])->with('garage')->first();
        return view('common.view-LPO-report',['claim'=>$claim]);
    }
    public function fetchTheftClaims(Request $request)
    {
        $claimType = $request->claimType;
        $assessors = User::role('Assessor')->where(["userTypeID"=>Config::$USER_TYPES['INTERNAL']['ID']])->get();
        $claims = Claim::where(['claimType'=> $claimType,'active'=>Config::ACTIVE])->with('adjuster')->get();
        return view('common.theft-claims',['claims' => $claims, 'assessors' => $assessors]);
    }

    public function fetchTheftAssessments(Request $request)
    {
        try {
            $assessmentStatusID = $request->assessmentStatusID;
            if (!isset($request->fromDate) && !isset($request->toDate) && !isset($request->regNumber)) {
                if($assessmentStatusID == Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                {
                    $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                        ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                        ->where('finalApprovedAt', ">=", Carbon::now()->subDays(Config::DATE_RANGE))
                        ->where('active','=',Config::ACTIVE)
                        ->where('isTheft','=',Config::ACTIVE)
                        ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
                }else
                {
                    $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                        ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                        ->where('active','=',Config::ACTIVE)
                        ->where('isTheft','=',Config::ACTIVE)
                        ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
                }
            } elseif (isset($request->regNumber)) {
//                $regNo = preg_replace("/\s+/", "", $request->regNumber);
                $registrationNumber=preg_replace("/\s+/", "", $request->regNumber);
                $regNoArray = preg_split('/(?=\d)/', $registrationNumber, 2);
                $regNo1 =isset($regNoArray[0]) ? $regNoArray[0] : '';
                $regNo2 = isset($regNoArray[1]) ? $regNoArray[1] : '';
                $regNo = $request->regNumber;
                $claimids = Claim::where(function($a) use ($regNo,$regNo1,$regNo2) {
                    $a->where('vehicleRegNo','like', '%'.$regNo.'%');
                })->orWhere(function($a)use ($regNo1,$regNo2) {
                    $a->where('vehicleRegNo','like', '%'.$regNo1.'%')->where('vehicleRegNo','like', '%'.$regNo2.'%');
                })->pluck('id')->toArray();
//                $claimids = Claim::where('vehicleRegNo','like', '%'.$request->regNumber.'%')->pluck('id')->toArray();
                $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereIn('claimID', $claimids)
                    ->where('active','=',Config::ACTIVE)
                    ->where('isTheft','=',Config::ACTIVE)
                    ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
            } elseif (isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber)) {
                $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
                $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
                $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereBetween('dateCreated', [$fromDate, $toDate])
                    ->where('active','=',Config::ACTIVE)
                    ->where('isTheft','=',Config::ACTIVE)
                    ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
            } else {
                $assessments = array();
            }
            return view('common.theft-assessments', ["assessments" => $assessments, 'assessmentStatusID' => $assessmentStatusID]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
    public function PTVReport(Request $request)
    {
        try {
            if(isset($request->assessmentID))
            {
                $assessment = Assessment::where(["id"=>$request->assessmentID])->with("claim")->first();
                if(isset($assessment->id))
                {
                    $customerCode = isset($assessment->claim->customerCode) ? $assessment->claim->customerCode : 0;
                    $insured = CustomerMaster::where(["customerCode" => $customerCode])->first();
                    return view('common.PTVReport',["assessment"=>$assessment,'insuredFullName'=>$insured->fullName]);
                }else
                {
                    $response = array(
                        "STATUS_CODE" => Config::NO_RECORDS_FOUND,
                        "STATUS_MESSAGE" => "PTV report not found"
                    );
                }

            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid payload"
                );
            }
        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to view PTV report. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }
    public function sendAssessmentReport(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $priceChange = PriceChange::where('assessmentID', $assessmentID)->first();
        $aproved = isset($priceChange) ? $priceChange : 'false';
        $assessment = Assessment::where(["id" => $assessmentID])->with("claim")->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID])->with('part')->get();
        $jobDetails = JobDetail::where(["assessmentID" => $assessmentID])->get();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured = CustomerMaster::where(["customerCode" => $customerCode])->first();
        $documents = Document::where(["assessmentID" => $assessmentID])->get();
        $adjuster = User::where(['id' => $assessment->claim->createdBy])->first();
        $assessor = User::where(['id' => $assessment->assessedBy])->first();
        $carDetail = CarModel::where(['makeCode' => isset($assessment['claim']['carMakeCode']) ? $assessment['claim']['carMakeCode'] : '', 'modelCode' => isset($assessment['claim']['carModelCode']) ? $assessment['claim']['carModelCode'] : ''])->first();
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('reports.assessment-report', ['assessment' => $assessment, "assessmentItems" => $assessmentItems, "jobDetails" => $jobDetails, "insured" => $insured, 'documents' => $documents, 'adjuster' => $adjuster, 'assessor' => $assessor, 'aproved' => $aproved, 'carDetail' => $carDetail, 'priceChange' => $priceChange]);

        //$pdfFilePath = public_path('reports/assessment-report.pdf');
        $pdfName = $assessment['claim']['vehicleRegNo'].'_'.$assessment['claim']['claimNo'];
        $pdfName = str_replace("/","_",$pdfName);
        $pdfFileName=preg_replace('/\s+/', ' ', $pdfName);
        $pdfFileName = str_replace(" ","_",$pdfFileName);
        $pdfFilePath = public_path('reports/'.$pdfFileName.'.pdf');
        if (File::exists($pdfFilePath)) {
            File::delete($pdfFilePath);
        }
        $pdf->save($pdfFilePath);
    }

    public function fetchCustomerData(Request $request)
    {

        try {
            if(isset($request->report_type) && isset($request->identity_number) && isset($request->identity_type))
            {
                $privateKey = Config::METROPOL_PROD_PRIVATE_KEY;
                $publicKey = Config::METROPOL_PROD_PUBLIC_KEY;
                $url = Config::METROPOL_BASE_URL.":".Config::METROPOL_PORT ."/".Config::METROPOL_API_VERSION. "/identity/verify";
                date_default_timezone_set("UTC");
                //        $timestamp = gmdate("Y-m-d H:i:s.u");
                $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
                $local = $now->setTimeZone(new DateTimeZone('UTC'));
                $timestamp = $local->format("YmdHisu");
                $payload = array(
                    "report_type"=>$request->report_type,
                    "identity_number"=>$request->identity_number,
                    "identity_type"=>$request->identity_type
                );
                $jsonObject = json_encode($payload);
                $utf8Key = $privateKey.$jsonObject.$publicKey.$timestamp;
                $headers = array(
                    "Content-Type:application/json",
                    'X-METROPOL-REST-API-KEY:' .$publicKey,
                    'X-METROPOL-REST-API-HASH:'.hash('sha256',utf8_encode($utf8Key)),
                    'X-METROPOL-REST-API-TIMESTAMP:'.$timestamp
                );
                $response = $this->generateCurl($url,$jsonObject,$headers);
                $result = json_decode($response,true);
                if(isset($result['api_code']) && isset($result['id_number']))
                {
                    $resp = array(
                        "STATUS_CODE" =>200,
                        "MESSAGE"=>"ID Number successfully verified"
                    );
                }else
                {
                    $resp = array(
                        "STATUS_CODE" =>2001,
                        "MESSAGE"=>"ID Number not verified"
                    );
                }
            }else
            {
                $resp = array(
                    "STATUS_CODE" =>2001,
                    "MESSAGE"=>"ID Number not verified"
                );
            }
        }catch (\Exception $e)
        {
            $resp = array(
                "STATUS_CODE" =>2001,
                "MESSAGE"=>"ID Number not verified"
            );
        }
        return json_encode($resp);
    }

    public function generateCurl($url, $params = null, $headers)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (Exception $ex) {


        }
    }
    public function sendLPOReport(Request $request)
    {
        try{
            $claimID = $request->claimID;
            $claim = Claim::where(['id'=>$claimID])->with('garage')->first();
            $pdf = App::make('snappy.pdf.wrapper');
            $pdf->loadView('reports.LPO-report', ['claim'=>$claim]);
            $this->savePdf($claim->vehicleRegNo,$claim->claimNo,'LPO',$pdf);
            $fileName = $this->getFileName($claim->vehicleRegNo,$claim->claimNo);
            $pdfFilePath = public_path('reports/LPO/'.$fileName.'.pdf');
            $data = [
                'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_LPO Report',
                'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                'to' => $claim->garage->email,
                'cc' => Auth::user()->email,
                'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                'attachment' => $pdfFilePath,
                'html' => "Dear ".$claim->garage->name."<br/>
                                   Find attached LPO Report for : ".$claim->vehicleRegNo." for claimNo : ".$claim->claimNo."
                                   <br/>
                                   Regards<br/>
                                   ".Auth::user()->firstName." ".Auth::user()->lastName."<br/>
                                   Claims Adjuster Jubilee Allianz",
            ];
            InfobipEmailHelper::sendEmail($data);
            $response = array(
                "STATUS_CODE" => Config::SUCCESS_CODE,
                "STATUS_MESSAGE" => "Email successfully sent to the garage"
            );
        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to save pdf. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }
    public function savePdf($vehicleRegNumber,$claimNumber,$path,$pdf)
    {
        $pdfName = $vehicleRegNumber.'_'.$claimNumber;
        $pdfName = str_replace("/","_",$pdfName);
        $pdfFileName=preg_replace('/\s+/', ' ', $pdfName);
        $pdfFileName = str_replace(" ","_",$pdfFileName);
        $pdfFilePath = public_path('reports/'.$path.'/'.$pdfFileName.'.pdf');
        if (File::exists($pdfFilePath)) {
            File::delete($pdfFilePath);
        }
        $pdf->save($pdfFilePath);
    }
    public function getFileName($vehicleRegNumber,$claimNumber)
    {
        $pdfName = $vehicleRegNumber.'_'.$claimNumber;
        $pdfName = str_replace("/","_",$pdfName);
        $pdfFileName=preg_replace('/\s+/', ' ', $pdfName);
        $pdfFileName = str_replace(" ","_",$pdfFileName);
        return $pdfFileName;
    }
}
