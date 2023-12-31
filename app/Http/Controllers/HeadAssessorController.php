<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\AssessmentItem;
use App\CarModel;
use App\ChangeRequest;
use App\Claim;
use App\Company;
use App\CustomerMaster;
use App\Document;
use App\Helper\SMSHelper;
use App\JobDetail;
use App\Notifications\AssignClaim;
use App\Notifications\ClaimApproved;
use App\Notifications\NewChangeRequest;
use App\PriceChange;
use App\StatusTracker;
use App\Conf\Config;
use App\Garage;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;

class HeadAssessorController extends Controller
{
    //
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }

    public function assignAssessor(Request $request)
    {
        $curlDate = $this->functions->curlDate();
        try {
            if (isset($request->claimID) && isset($request->assessor) && isset($request->claimType)) {
                $assessment = Assessment::where(['claimID' => $request->claimID])->get();
                if($request->claimType == "Assessement")
                {
                    $isTheft = Config::INACTIVE;
                }else
                {
                    $isTheft = Config::ACTIVE;
                }
                if (count($assessment) == 0) {
                    $assessmentID = Assessment::insertGetId([
                        "claimID" => $request->claimID,
                        "assessedBy" => $request->assessor,
                        "assessmentStatusID" => Config::$STATUSES["ASSESSMENT"]["ASSIGNED"]["id"],
                        "createdBy" => Auth::id(),
                        "active" => Config::ACTIVE,
                        "isTheft" =>$isTheft,
                        "segment" => Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                        "dateCreated" => $curlDate
                    ]);
                    if ($assessmentID > 0) {
                        $statusTracker = StatusTracker::where(["claimID" => $request->claimID])->first();
                        $oldStatus = isset($statusTracker->newStatus) ? $statusTracker->newStatus : 0;
//                        $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
//                            "Old status " . $oldStatus);
                        StatusTracker::create([
                            "assessmentID" => $assessmentID,
                            "claimID" => $request->claimID,
                            "statusType" => Config::$STATUS_TYPES["ASSESSMENT"],
                            "newStatus" => Config::$STATUSES["ASSESSMENT"]["ASSIGNED"]["id"],
                            "oldStatus" => $oldStatus,
                            "dateModified" => $curlDate
                        ]);
                        Claim::where(["id" => $request->claimID])->update([
                            "claimStatusID" => Config::$STATUSES["CLAIM"]["ASSIGNED"]["id"]
                        ]);
                        $assessor = User::where(['id' => $request->assessor])->first();
                        $claim = Claim::where(['id' => $request->claimID])->first();
                        $garage = Garage::where(['id' => $claim->garageID])->first();
                        $location = isset($garage->name) ? $garage->name : '';
                        if ($assessor->id > 0) {
                            $email_add = $assessor->email;
                            if($request->claimType == "Assessement")
                            {
                                $emailMessage = "
                    Hello, <br>
                    Please note that there's a vehicle
                    <br><strong>Claim number</strong>:  " . $claim->claimNo . "
                    <br><strong>Registration number</strong>: " . $claim->vehicleRegNo . "
                    <br><strong>Location</strong>: $location
                    <br><strong>Sum Insured</strong>: " . $claim->sumInsured . "
                    <br>Login to the portal using the link below to view the details. If you are not in a position to assess it, kindly inform us in the next 1 hour
                    <br>
                    <br>
                    <a href=" . url('/assessments') . ">Link</a>
                    <br><br>
                    Regards,<br>
                    Claims Department,<br>
                    Jubilee Insurance
                ";
                                $smsMessage = 'Hello ' . $assessor->firstName . ', You have been assigned to assess a claim. Vehicle registration: ' . $claim->vehicleRegNo . ', Location: ' . $location . '';
                            }else
                            {
                                $emailMessage = "
                    Hello, <br>
                    Please note that there's a vehicle
                    <br><strong>Claim number</strong>:  " . $claim->claimNo . "
                    <br><strong>Registration number</strong>: " . $claim->vehicleRegNo . "
                    <br><strong>Location</strong>: $location
                    <br><strong>Sum Insured</strong>: " . $claim->sumInsured . "
                    <br> You are required to provide PTV
                    <br>
                    <br>
                    <br><br>
                    Regards,<br>
                    Claims Department,<br>
                    Jubilee Insurance
                ";
                                $smsMessage = 'Hello ' . $assessor->firstName . ', You have been assigned to provide PTV for Vehicle registration: ' . $claim->vehicleRegNo;
                            }
                            $email = [
                                'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                                'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'to' => $email_add,
                                'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'html' => $emailMessage,
                            ];
                            $logData = array(
                                "vehicleRegNo" => $claim->vehicleRegNo,
                                "claimNo" => $claim->claimNo,
                                "policyNo" => $claim->policyNo,
                                "userID" => Auth::user()->id,
                                "role" => Config::$ROLES['ASSESSOR'],
                                "activity" => Config::ACTIVITIES['ASSIGN_ASSESSOR'],
                                "notification" => $emailMessage,
                                "notificationTo" => $email_add,
                                "notificationType" => Config::NOTIFICATION_TYPES['EMAIL'],
                            );
//                            $this->functions->logActivity($logData);
                            InfobipEmailHelper::sendEmail($email, $email_add);
                            $logData['notificationType'] = Config::NOTIFICATION_TYPES['SMS'];
                            $logData['notification'] = $smsMessage;
                            $logData["notificationTo"] = $assessor->MSISDN;
//                            $this->functions->logActivity($logData);
                            SMSHelper::sendSMS($smsMessage, $assessor->MSISDN);
                            Notification::send($assessor, new AssignClaim($claim));
                        }
                        $response = array(
                            "STATUS_CODE" => Config::SUCCESS_CODE,
                            "STATUS_MESSAGE" => "Congratulation!, You have successfully assigned the claim to assessor"
                        );
                    } else {
                        $response = array(
                            "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                            "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                        );
                    }
                } else {
                    $response = array(
                        "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                        "STATUS_MESSAGE" => "Assessor already assigned"
                    );
                }
            } else {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid payload"
                );
            }
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to assign assessor. Error message " . $e->getMessage());
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
        }
        return json_encode($response);
    }

    public function reAssignAssessor(Request $request)
    {
        $curlDate = $this->functions->curlDate();
        try {
            if (isset($request->claimID) && isset($request->assessor)) {
                Assessment::where(['claimID'=>$request->claimID])->update([
                    "assessedBy" => $request->assessor,
                    "updatedBy" => Auth::id(),
                    "dateModified"=>$this->functions->curlDate()
                ]);
                $assessor = User::where(['id' => $request->assessor])->first();
                $claim = Claim::where(['id' => $request->claimID])->first();
                $location = isset($claim->garageID) ? $claim->garageID : '';
                if ($assessor->id > 0) {
                    $email_add = $assessor->email;
                    $email = [
                        'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                        'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                        'to' => $email_add,
                        'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                        'html' => "
                    Hello, <br>
                    Please note that there's a vehicle
                    <br><strong>Claim number</strong>:  " . $claim->claimNo . "
                    <br><strong>Registration number</strong>: " . $claim->vehicleRegNo . "
                    <br><strong>Location</strong>: $location
                    <br><strong>Sum Insured</strong>: " . $claim->sumInsured . "
                    <br>Login to the portal using the link below to view the details. If you are not in a position to assess it, kindly inform us in the next 1 hour
                    <br>
                    <br>
                    <a href=" . url('/assessments') . ">Link</a>
                    <br><br>
                    Regards,<br>
                    Claims Department,<br>
                    Jubilee Insurance
                ",
                    ];
                    InfobipEmailHelper::sendEmail($email, $email_add);
                    SMSHelper::sendSMS('Hello ' . $assessor->firstName . ', You have been assigned to assess a claim. Vehicle registration: ' . $claim->vehicleRegNo . ', Location: ' . $location . '', $assessor->MSISDN);
                    Notification::send($assessor, new AssignClaim($claim));
                }
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Congratulation!, You have successfully Re-assigned the claim to assessor"
                );
            } else {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid payload"
                );
            }
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to Re-assign assessor. Error message " . $e->getMessage());
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
        }
        return json_encode($response);
    }

    public function fetchClaims(Request $request)
    {
        try {
            $claims = Claim::with("assessment")
                ->where("dateCreated", '>', Carbon::now()->subDays(3))
                ->where("active", "=", Config::ACTIVE)
                ->where("claimType","=". Config::CLAIM_TYPES['ASSESSMENT'])
                ->orderBy('dateCreated', 'DESC')->get();
            $assessors = User::role('Assessor')->get();
            return view('head-assessor.claims', ['claims' => $claims, 'assessors' => $assessors]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch claims " . $e->getMessage());
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
        }
    }

    public function claims(Request $request)
    {
        try {
            $claimStatusID = $request->claimStatusID;
            $claims = Claim::with("assessment")
                ->where("claimStatusID", "=", $claimStatusID)
                ->where("active", "=", Config::ACTIVE)
                ->where("claimType", "=", Config::CLAIM_TYPES['ASSESSMENT'])
                ->orderBy('dateCreated', 'DESC')->with('assessment')->get();
            $assessors = User::role('Assessor')->get();
            return view('head-assessor.claims', ['claims' => $claims, 'assessors' => $assessors, "claimStatusID" => $claimStatusID]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch claims " . $e->getMessage());
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
        }
    }

    public function assessments(Request $request)
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
                        ->where('isTheft','=',Config::INACTIVE)
                        ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
                }else
                {
                    $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                        ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                        ->where('active','=',Config::ACTIVE)
                        ->where('isTheft','=',Config::INACTIVE)
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
                    ->where('isTheft','=',Config::INACTIVE)
                    ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
            } elseif (isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber)) {
                $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
                $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
                $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereBetween('dateCreated', [$fromDate, $toDate])
                    ->where('active','=',Config::ACTIVE)
                    ->where('isTheft','=',Config::INACTIVE)
                    ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
            } else {
                $assessments = array();
            }
            return view('head-assessor.assessments', ["assessments" => $assessments, 'assessmentStatusID' => $assessmentStatusID]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }

    public function supplementaries(Request $request)
    {
        $id = Auth::id();
        $assessmentStatusID = $request->assessmentStatusID;
        try {
            $assessments = Assessment::where(['assessmentStatusID' => $assessmentStatusID, 'segment' => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],'active'=>Config::ACTIVE])->with('claim')->with('user')->with('approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
            return view('head-assessor.supplementaries', ['assessments' => $assessments, 'assessmentStatusID' => $assessmentStatusID, 'assessmentStatusID' => $assessmentStatusID, 'id' => $id]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }

    public function supplementaryReport(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $assessment = Assessment::where(["id" => $assessmentID, 'segment' => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->with("claim")->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID, 'segment' => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->with('part')->get();
        $jobDetails = JobDetail::where(["assessmentID" => $assessmentID, 'segment' => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->get();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured = CustomerMaster::where(["customerCode" => $customerCode])->first();
        $documents = Document::where(["assessmentID" => $assessmentID, "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->get();
        $adjuster = User::where(['id' => $assessment->claim->createdBy])->first();
        $assessor = User::where(['id' => $assessment->assessedBy])->first();
        $carDetail = CarModel::where(['makeCode' => isset($assessment['claim']['carMakeCode']) ? $assessment['claim']['carMakeCode'] : '', 'modelCode' => isset($assessment['claim']['carModelCode']) ? $assessment['claim']['carModelCode'] : ''])->first();
        return view("head-assessor.view-supplementary-report", ['assessment' => $assessment, "assessmentItems" => $assessmentItems, "jobDetails" => $jobDetails, "insured" => $insured, 'documents' => $documents, 'adjuster' => $adjuster, 'assessor' => $assessor,'carDetail'=>$carDetail]);
    }

    public function assessmentReport(Request $request)
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
        $companies = Company::select('id','name')->get();
        $carDetail = CarModel::where(['makeCode' => isset($assessment['claim']['carMakeCode']) ? $assessment['claim']['carMakeCode'] : '', 'modelCode' => isset($assessment['claim']['carModelCode']) ? $assessment['claim']['carModelCode'] : ''])->first();
        return view("head-assessor.assessment-report", ['assessment' => $assessment, "assessmentItems" => $assessmentItems, "jobDetails" => $jobDetails, "insured" => $insured, 'documents' => $documents, 'adjuster' => $adjuster, 'assessor' => $assessor, 'aproved' => $aproved, 'carDetail' => $carDetail,'priceChange'=>$priceChange,'companies'=>$companies]);
    }

    public function reviewAssessment(Request $request)
    {
        try {
            if (isset($request->assessmentReviewType)) {
                $assessment = Assessment::where(["id" => $request->assessmentID])->first();
                $isSubrogate = isset($request->isSubrogate) ? $request->isSubrogate : 0;
                $companyID = isset($request->companyID) ? $request->companyID : null;
                $thirdPartyDriver = isset($request->thirdPartyDriver) ? $request->thirdPartyDriver : null;
                $thirdPartyPolicy = isset($request->thirdPartyPolicy) ? $request->thirdPartyPolicy : null;
                $thirdPartyVehicleRegNo = isset($request->thirdPartyVehicleRegNo) ? $request->thirdPartyVehicleRegNo : null;
                $assessmentTypeID = $assessment->assessmentTypeID;
                if ($request->assessmentReviewType == Config::APPROVE) {
                    $approved = Assessment::where(["id" => $request->assessmentID])->update([
                        "assessmentStatusID" => Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'],
                        "changesDue" => 0,
                        "isSubrogate" => $isSubrogate,
                        "companyID"=> $companyID,
                        "thirdPartyDriver"=>$thirdPartyDriver,
                        "thirdPartyPolicy" =>$thirdPartyPolicy,
                        "thirdPartyVehicleRegNo" =>$thirdPartyVehicleRegNo,
                        "reviewNote" => isset($request->report) ? $request->report : null,
                        "approvedBy" => Auth::id(),
                        "approvedAt" => $this->functions->curlDate()
                    ]);
                    if ($approved) {
                        $claim = Claim::where(["id" => $assessment->claimID])->first();
//                      $assessorID = $assessment->assessedBy;
                        $adjuster = User::where(["id" => $claim->createdBy])->first();
                        $link = 'assessment-report/' . $request->assessmentID;
                        $firstName = $adjuster->firstName;
                        $email = $adjuster->email;
                        $MSISDN = $adjuster->MSISDN;
                        $vehicleReg = $claim->vehicleRegNo;
                        $claimNo = $claim->claimNo;
                        $reviewNote = isset($request->report) ? $request->report : '';
                        $role = Config::$ROLES['HEAD-ASSESSOR'];

                        if ($assessmentTypeID == Config::ASSESSMENT_TYPES['TOTAL_LOSS']){
                            $cc_emails = array(User::find(Auth::id())['email'], $email);
//                            $cc_emails = array(User::find(Auth::id())['email'], 'oumasylvester61@gmail.com');
                            $message = [
                                'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                                'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'to' => Config::JUBILEE_ALLIANZ_TALK_TO_US_EMAIL,
//                                'to' => 'sylvesterouma282@gmail.com',
                                'cc'=>$cc_emails,
                                'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'html' => "
                                Hello " . Config::JUBILEE_ALLIANZ_TALK_TO_US_NAME . ", <br>
                                <br>
                                This is in regards to claim number <strong>" . $claimNo . " </strong>, it is deemed a <strong>TOTAL LOSS</strong>. <br> <br>
                                Kindly cancel this policy immediately and flag this vehicle for future policies. <br> <br>
                                <b><i><u>Notes</u></i></b> <br>
                                <i> " . $reviewNote . " </i><br><br>
                                Regards, <br><br>
                                " . $role . ", <br>
                                Claims Department, <br>
                                Jubilee Allianz Insurance Company
                    ",
                            ];
                        }else{
                            $message = [
                                'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                                'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'to' => $email,
                                'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'html' => "
                        Hello " . $firstName . ", <br>

                        This is in regards to claim number <strong>" . $claimNo . " </strong> <br>

                        The assessment has been provisionally approved waiting for final approval. Find attached report. <br> <br>

                            <b><i><u>Notes</u></i></b> <br>

                            <i> " . $reviewNote . " </i><br><br>

                        Regards, <br><br>

                        " . $role . ", <br>

                        Claims Department, <br>

                        Jubilee Allianz Insurance Company
                    ",
                            ];
                        }


                        InfobipEmailHelper::sendEmail($message, $email);
//                        SMSHelper::sendSMS('Hello ' . $firstName . ', Assessment for claimNo ' . $claimNo . ' has been provisionally approved', $MSISDN);
//
                        $logData = array(
                            "vehicleRegNo" => $claim->vehicleRegNo,
                            "claimNo" => $claim->claimNo,
                            "policyNo" => $claim->policyNo,
                            "userID" => Auth::user()->id,
                            "role" => Config::$ROLES['ADJUSTER'],
                            "activity" => Config::ACTIVITIES['PROVISIONAL_APPROVAL'],
                            "notification" => $message['html'],
                            "notificationTo" => $email,
                            "notificationType" => Config::NOTIFICATION_TYPES['EMAIL'],
                        );
                        $this->functions->logActivity($logData);

                        $assessmentManagers = User::role(Config::$ROLES['ASSESSMENT-MANAGER'])->get(); // Returns only users with the role 'Assessment Managers'
                        if (count($assessmentManagers) > 0) {
                            foreach ($assessmentManagers as $assessmentManager) {
                                $message = [
                                    'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                                    'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                                    'to' => $assessmentManager->email, //sending emails to all managers
                                    'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                                    'html' => "
                        Hello " . $assessmentManager->firstName . ", <br>

                        This is in regards to claim number <strong>" . $claimNo . " </strong> <br>

                        The assessment has been provisionally approved waiting for your final approval. Find attached report. <br> <br>

                            <b><i><u>Notes</u></i></b> <br>

                            <i> " . $reviewNote . " </i><br><br>

                        Regards, <br><br>

                        " . $role . ", <br>

                        Claims Department, <br>

                        Jubilee Allianz Insurance Company
                    ",
                                ];
//                                InfobipEmailHelper::sendEmail($message, $assessmentManager->email);
//                        SMSHelper::sendSMS('Hello ' . $firstName . ', Assessment for claimNo ' . $claimNo . ' has been provisionally approved', $MSISDN);
//                        Notification::send($assessor, new ClaimApproved($claim));

//                                $logData['notification'] = $message['html'];
//                                $logData["notificationTo"] = $assessmentManager->email;
//                                $logData["role"] = Config::$ROLES['ASSESSMENT-MANAGER'];
//                                $this->functions->logActivity($logData);
                            }
                        }

                        //send the demand letter to third party insurer

                        if ($assessment->isSubrogate == 1){
                            $assessmentID= $request->assessmentID;
                            $assessment = Assessment::where(["id"=>$assessmentID])->first();
                            $claim = Claim::where(["id"=>$assessment->claimID])->with('customer')->first();
                            $company = Company::where(["id"=>$assessment->companyID])->first();
                            $adjuster_email = User::where('id', $claim->createdBy)->first()->email;
                            $cc_emails=array($adjuster_email, Config::JUBILEE_REPLY_EMAIL);


                            $end_salutation_email = Company::where("name", "=", "JUBILEE ALLIANZ GENERAL INSURANCE (K) LIMITED")->first()->recovery_officer_email;
                            $end_salutation_first=explode('@', $end_salutation_email)[0];
                            $first_array=explode(".", $end_salutation_first);
                            $regards=implode(" ", $first_array);
                            $pdf = App::make('dompdf.wrapper');
                            $pdf->loadView('reports.demand-letter', ['assessment'=>$assessment,'claim'=>$claim,'company'=>$company, 'regards'=>$regards]);

                            $pdfName = $assessment['claim']['vehicleRegNo'].'_'.$assessment['claim']['claimNo'];
                            $pdfName = str_replace("/","_",$pdfName);
                            $pdfFileName=preg_replace('/\s+/', ' ', $pdfName);
                            $pdfFileName = str_replace(" ","_",$pdfFileName);
                            $directoryPath = public_path('reports');

                            if (!File::isDirectory($directoryPath)) {
                                File::makeDirectory($directoryPath, 0755, true); // Creates the directory recursively with permissions 0755
                            }
                            $pdfFilePath = $directoryPath.'/'.$pdfFileName.'.pdf';


                            if (File::exists($pdfFilePath)) {
                                File::delete($pdfFilePath);
                            }
                            $pdf->save($pdfFilePath);

                            $flag = false;



                            $message = [
                                'subject' => "DEMAND LETTER - ".$assessment['claim']['claimNo']."_".$assessment['claim']['vehicleRegNo'],
                                'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'to' => $company->recovery_officer_email,
//                'to' => "sylvesterouma282@gmail.com",
                                'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'attachment' => $pdfFilePath,
                                'cc' => $cc_emails,
//                'cc' => Auth::user()->email,
                                'html' => "

                        Dear Sirs, <br>

                        Kindly see attached our demand letter. <br>
                        To acknowledge the receipt of the letter, and further follow-ups, kindly contact our recovery departmet via jazrecovery@allianz.com. <br> <br>

                        Regards, <br><br>


                        $regards, <br>

                        Recovery Officer , <br>

                        Jubilee Allianz Insurance Company
                    ",
                            ];

                            $emailSSent=InfobipEmailHelper::sendEmail($message);
                            if ($emailSSent){
                                Assessment::where(["id"=>$assessmentID])->update(["demandLetterDate"=>\Illuminate\Support\Carbon::now(), "subrogationSender"=>Auth::user()->id]);
                            }
                        }




                        $response = array(
                            "STATUS_CODE" => Config::SUCCESS_CODE,
                            "STATUS_MESSAGE" => "Heads up! You have successfully approved an assessment"
                        );
                    }
                } else if ($request->assessmentReviewType == Config::HALT) {

                }

            } else {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid data. Check your data and try again"
                );
            }

        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE,
//                "STATUS_MESSAGE" => $e->getMessage()
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to approve or halt a claim " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function reviewSupplementary(Request $request)
    {
        try {
            if (isset($request->assessmentReviewType)) {
                $assessment = Assessment::where(["id" => $request->assessmentID])->first();
                if ($request->assessmentReviewType == Config::APPROVE) {
                    $approved = Assessment::where(["id" => $request->assessmentID])->update([
                        "assessmentStatusID" => Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'],
                        "changesDue" => 0,
                        "reviewNote" => isset($request->report) ? $request->report : null,
                        "approvedBy" => Auth::id(),
                        "approvedAt" => $this->functions->curlDate()
                    ]);
                    if ($approved) {
                        $claim = Claim::where(["id" => $assessment->claimID])->first();
                        $assessorID = $assessment->assessedBy;
                        $assessor = User::where(["id" => $assessorID])->first();
                        $link = 'assessment-report/' . $request->assessmentID;
                        $firstName = $assessor->firstName;
                        $email = $assessor->email;
                        $MSISDN = $assessor->MSISDN;
                        $vehicleReg = $claim->vehicleRegNo;
                        $claimNo = $claim->claimNo;
                        $reviewNote = $request->report;
                        $role = Config::$ROLES['HEAD-ASSESSOR'];

                        $message = [
                            'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                            'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                            'to' => $email,
                            'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                            'html' => "
                        Hello " . $firstName . ", <br>

                        This is in regards to claim number <strong>" . $claimNo . " </strong> <br>

                        The Supplementary has been provisionally approved waiting for final approval. Find attached report. <br> <br>

                            <b><i><u>Notes</u></i></b> <br>

                            <i> " . $reviewNote . " </i><br><br>

                        Regards, <br><br>

                        " . $role . ", <br>

                        Claims Department, <br>

                        Jubilee Allianz Insurance Company
                    ",
                        ];

                        InfobipEmailHelper::sendEmail($message, $email);
                        SMSHelper::sendSMS('Hello ' . $firstName . ', Supplementary for claimNo ' . $claimNo . ' has been provisionally approved', $MSISDN);
                        Notification::send($assessor, new ClaimApproved($claim));
                        $response = array(
                            "STATUS_CODE" => Config::SUCCESS_CODE,
                            "STATUS_MESSAGE" => "Heads up! You have successfully approved a Supplementary"
                        );
                    }
                } else if ($request->assessmentReviewType == Config::HALT) {

                }

            } else {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid data. Check your data and try again"
                );
            }

        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to approve or halt a claim " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function requestPriceChange(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $priceChange = PriceChange::where(['assessmentID' => $assessmentID])->first();
        $priceChange->approvedBy = null;
        $priceChange->approvedAt = null;
        $priceChange->finalApproved = 1;
        $priceChange->finalApprover = null;
        $priceChange->finalApprovedAt = null;
        $priceChange->changed = false;
        $priceChange->save();
    }

    public function requestAssessmentChange(Request $request)
    {
        try {
            $assessment = Assessment::where('id', $request->assessmentID)->first();
            $claim = Claim::where(['id' => $assessment->claimID])->first();
            $assessor = User::where(['id' => isset($assessment->assessedBy) ? $assessment->assessedBy : ''])->first();
            $data = [
                'id' => $request->assessmentID,
                'assessments' => $assessment,
                'assessor' => $assessor,
                'claim' => $claim,
                'change' => $request->changes,
                'reg' => $claim->vehicleRegNo,
                'role' => Config::$ROLES['HEAD-ASSESSOR']
            ];

            $change = new ChangeRequest();
            $change->assessmentID = $request->assessmentID;
            $change->changeRequest = $request->changes;
            $change->createdBy = Auth::user()->id;
            $change->directedTo = $data['assessor']->id;
            $change->dateCreated = date('Y-m-d H:i:s');

            $save = $change->save();
            if ($save) {
                Assessment::where('id', $request->assessmentID)->update([
                    'assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id'],
                    'dateModified' => date('Y-m-d H:i:s'),
                    'changeRequestAt' => date('Y-m-d H:i:s'),
                    'updatedBy' => Auth::user()->id
                ]);
                $email_add = $data['assessor']->email;
                $email = [
                    'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                    'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'to' => $email_add,
                    'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'cc' => Auth::user()->email,
                    'html' => "
                    Hello " . $data['assessor']->firstName . ", <br>
                    This is in regards to the vehicle you've recently assessed, Registration <strong>" . $data['reg'] . "</strong> <br>
                    You are required to make the following change(s) <br>

                    <i><u>Changes Requested</u></i>: <br>
                    <p> " . $data['change'] . "</p> <br><br>

                    Regards, <br><br>
                     " . $data['role'] . ", <br>
                    Claims Department, <br>
                    Jubilee Allianz Insurance Company of Kenya.
                ",
                ];
                InfobipEmailHelper::sendEmail($email, $email_add);
                $logData = array(
                    "vehicleRegNo" => $claim->vehicleRegNo,
                    "claimNo" => $claim->claimNo,
                    "policyNo" => $claim->policyNo,
                    "userID" => Auth::user()->id,
                    "role" => Config::$ROLES['ASSESSOR'],
                    "activity" => Config::ACTIVITIES['REQUEST_CHANGES'],
                    "notification" => $email['html'],
                    "notificationTo" => $email_add,
                    "notificationType" => Config::NOTIFICATION_TYPES['EMAIL'],
                );
                $this->functions->logActivity($logData);
                $smsMessage = 'Hello ' . $data['assessor']->firstName . ', Check your email for changes due for vehicle Reg. ' . $data['reg'];
                SMSHelper::sendSMS($smsMessage, $data['assessor']->MSISDN);
                $logData['notification'] = $smsMessage;
                $logData['notificationTo'] = $data['assessor']->MSISDN;
                $logData['notificationType'] = Config::NOTIFICATION_TYPES['SMS'];
                $this->functions->logActivity($logData);
                Notification::send($assessor, new NewChangeRequest($claim));
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Heads up! An email was sent to " . $data['assessor']->firstName . " with the requested changes"
                );
            } else {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to request for changes " . $e->getMessage());
        }
        return json_encode($response);
    }
    public function requestAssessmentInvestigation(Request $request)
    {
        try {
            $investigationUpdateData = [
                'assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['UNDER-INVESTIGATION']['id'],
                'dateModified' => date('Y-m-d H:i:s'),
                'investigationRequestAt' => date('Y-m-d H:i:s'),
                'updatedBy' => Auth::user()->id,
                'reviewNote'=>$request->reviewNote??''
            ];
           $assessment = Assessment::where('id', $request->assessmentID);
           $save = $assessment->update($investigationUpdateData);

           if ($save){
               $assessment= $assessment->first();
               $claim = Claim::find($assessment->claimID);
               $adjuster = User::find($claim->createdBy);
               $assessor = User::find($assessment->assessedBy)->name;

               $cc_emails = [Auth::user()->email];
               // Get the role names for 'Assessment Manager' and 'Manager'
               $assessmentManagerRoleName = Config::$ROLES['ASSESSMENT-MANAGER'];
               $managerRoleName = Config::$ROLES['MANAGER'];

// Retrieve users with the role 'Assessment Manager' but not with the role 'Manager'
               $users = User::role($assessmentManagerRoleName)
                   ->whereDoesntHave('roles', function ($query) use ($managerRoleName) {
                       $query->where('name', $managerRoleName);
                   })
                   ->get();

// Now you have the users with the role 'Assessment Manager' but not with the role 'Manager'
               foreach ($users as $user){
                   $cc_emails[] = $user->email;
               }

                $email = [
                    'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                    'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'to' => $adjuster->email,
                    'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'cc' => $cc_emails,
                    'html' => "
                    Hello " . $adjuster->firstName . ", <br>
                    <br>
                    This is in regards to the vehicle registration number ".$claim->vehicleRegNo.", recently assessed by ".$assessor.". The circumstances are not consistent with damages <br>
                    Kindly appoint an investigator.<br>
                    <i><u>Note</u></i>: <br> <br>
                    <p> " . $assessment->reviewNote . "</p> <br>

                    Regards, <br>
                    ".Auth::user()->firstName.",
                     " . Config::$ROLES['HEAD-ASSESSOR'] . ", <br>
                    Claims Department, <br>
                    Jubilee Allianz Insurance Company of Kenya.
                ",
                ];
                InfobipEmailHelper::sendEmail($email, $adjuster->email);
                $logData = array(
                    "vehicleRegNo" => $claim->vehicleRegNo,
                    "claimNo" => $claim->claimNo,
                    "policyNo" => $claim->policyNo,
                    "userID" => Auth::user()->id,
                    "role" => Config::$ROLES['ASSESSOR'],
                    "activity" => Config::ACTIVITIES['REQUEST_INVESTIGATION'],
                    "notification" => $email['html'],
                    "notificationTo" => $adjuster->email,
                    "notificationType" => Config::NOTIFICATION_TYPES['EMAIL'],
                );
                $this->functions->logActivity($logData);
                $smsMessage = 'Hello ' . $adjuster->firstName . ', Check your email for investigation request for vehicle Reg. ' . $claim->vehicleRegNo. ' and action';
//                SMSHelper::sendSMS($smsMessage, $adjuster->MSISDN);
                $logData['notification'] = $smsMessage;
                $logData['notificationTo'] = $adjuster->MSISDN;
                $logData['notificationType'] = Config::NOTIFICATION_TYPES['SMS'];
                $this->functions->logActivity($logData);
                Notification::send($adjuster, new NewChangeRequest($claim));
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Heads up! An email was sent to " . $adjuster->firstName . " with the investigation request"
                );
            } else {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to request for changes " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function requestSupplementaryChange(Request $request)
    {
        try {
            $assessment = Assessment::where('id', $request->assessmentID)->first();
            $claim = Claim::where(['id' => $assessment->claimID])->first();
            $assessor = User::where(['id' => isset($assessment->assessedBy) ? $assessment->assessedBy : ''])->first();
            $data = [
                'id' => $request->assessmentID,
                'assessments' => $assessment,
                'assessor' => $assessor,
                'claim' => $claim,
                'change' => $request->changes,
                'reg' => $claim->vehicleRegNo,
                'role' => Config::$ROLES['HEAD-ASSESSOR']
            ];

            $change = new ChangeRequest();
            $change->assessmentID = $request->assessmentID;
            $change->changeRequest = $request->changes;
            $change->createdBy = Auth::user()->id;
            $change->directedTo = $data['assessor']->id;
            $change->dateCreated = date('Y-m-d H:i:s');

            $save = $change->save();
            if ($save) {
                Assessment::where('id', $request->assessmentID)->update([
                    'assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id'],
                    'dateModified' => date('Y-m-d H:i:s'),
                    'changeRequestAt' => date('Y-m-d H:i:s'),
                    'updatedBy' => Auth::user()->id
                ]);
                $email_add = $data['assessor']->email;
                $email = [
                    'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                    'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'to' => $email_add,
                    'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'cc' => Auth::user()->email,
                    'html' => "
                    Hello " . $data['assessor']->firstName . ", <br>
                    This is in regards to the vehicle you've recently submitted supplementary, Registration <strong>" . $data['reg'] . "</strong> <br>
                    You are required to make the following change(s) <br>

                    <i><u>Changes Requested</u></i>: <br>
                    <p> " . $data['change'] . "</p> <br><br>

                    Regards, <br><br>
                     " . $data['role'] . ", <br>
                    Claims Department, <br>
                    Jubilee Allianz Insurance Company of Kenya.
                ",
                ];
                InfobipEmailHelper::sendEmail($email, $email_add);
                SMSHelper::sendSMS('Hello ' . $data['assessor']->firstName . ', Check your email for changes due for vehicle Reg. ' . $data['reg'], $data['assessor']->MSISDN);
                Notification::send($assessor, new NewChangeRequest($claim));
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Heads up! An email was sent to " . $data['assessor']->firstName . " with the requested changes"
                );
            } else {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to request for changes " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function priceChangeReport(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $assessment = Assessment::where(["id" => $assessmentID])->with("claim")->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID])->with('part')->get();
        $jobDetails = JobDetail::where(["assessmentID" => $assessmentID])->get();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured = CustomerMaster::where(["customerCode" => $customerCode])->first();
        $documents = Document::where(["assessmentID" => $assessmentID])->get();
        $adjuster = User::where(['id' => $assessment->claim->createdBy])->first();
        $assessor = User::where(['id' => $assessment->assessedBy])->first();
        return view("head-assessor.price-change-report", ['assessment' => $assessment, "assessmentItems" => $assessmentItems, "jobDetails" => $jobDetails, "insured" => $insured, 'documents' => $documents, 'adjuster' => $adjuster, 'assessor' => $assessor]);
    }

    public function reviewPriceChange(Request $request)
    {
        try {
            $curlDate = $this->functions->curlDate();
            $assessmentID = $request->assessmentID;
            $update = PriceChange::where('assessmentID', $assessmentID)->update([
                'approvedBy' => Auth::user()->id,
                'approvedAt' => $curlDate
            ]);
            if ($update) {
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Congratulation!, You have approved price change request"
                );
            } else {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to approve price change " . $e->getMessage());
        }

        return json_encode($response);
    }
}

