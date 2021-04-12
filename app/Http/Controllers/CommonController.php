<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\Assessment;
use App\Claim;
use App\Conf\Config;
use App\CustomerMaster;
use App\Garage;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                    ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('reInspection')->orderBy('dateCreated', 'DESC')->get();
            } elseif (isset($request->regNumber)) {
//              $regNo = preg_replace("/\s+/", "", $request->regNumber);
                $registrationNumber = preg_replace("/\s+/", "", $request->regNumber);
                $regNoArray = preg_split('/(?=\d)/', $registrationNumber, 2);
                $regNo1 = isset($regNoArray[0]) ? $regNoArray[0] : '';
                $regNo2 = isset($regNoArray[1]) ? $regNoArray[1] : '';
                $regNo = $request->regNumber;
                $claimids = Claim::where(function ($a) use ($regNo, $regNo1, $regNo2) {
                    $a->where('vehicleRegNo', 'like', '%' . $regNo . '%');
                })->orWhere(function ($a) use ($regNo1, $regNo2) {
                    $a->where('vehicleRegNo', 'like', '%' . $regNo1 . '%')->where('vehicleRegNo', 'like', '%' . $regNo2 . '%');
                })->pluck('id')->toArray();

//              $claimids = Claim::where('vehicleRegNo','like', '%'.$request->regNumber.'%')->pluck('id')->toArray();

                $asmts = Assessment::whereIn('assessmentStatusID', $assessmentStatusIDs)
                    ->where('segment', '=', Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
                $assessments = Assessment::where("assessedBy", "!=", $id)
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereIn('claimID', $claimids)
                    ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('reInspection')->orderBy('dateCreated', 'DESC')->get();
            } elseif (isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber)) {
                $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
                $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
                $asmts = Assessment::whereIn('assessmentStatusID', $assessmentStatusIDs)
                    ->where('segment', '=', Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
                $assessments = Assessment::where("assessedBy", "!=", $id)
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereBetween('dateCreated', [$fromDate, $toDate])
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
            $assessments = Assessment::where(['assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['APPROVED']['id'], 'assessmentTypeID' => $assessmentTypeID])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->get();
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

        $message = [
            'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
            'from' => Config::JUBILEE_NO_REPLY_EMAIL,
            'to' => $emails,
            'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
            'cc' => $ccEmails,
            'html' => $message,
        ];

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
}
