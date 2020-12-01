<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\AssessmentItem;
use App\ChangeRequest;
use App\Claim;
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
use Illuminate\Support\Facades\Auth;
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
            if (isset($request->claimID) && isset($request->assessor)) {
                $assessmentID = Assessment::insertGetId([
                    "claimID" => $request->claimID,
                    "assessedBy" => $request->assessor,
                    "assessmentStatusID" => Config::$STATUSES["ASSESSMENT"]["ASSIGNED"]["id"],
                    "createdBy" => Auth::id(),
                    "segment" => Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                    "dateCreated" => $curlDate
                ]);
                if ($assessmentID > 0) {
                    $statusTracker = StatusTracker::where(["claimID" => $request->claimID])->first();
                    $oldStatus = isset($statusTracker->newStatus) ? $statusTracker->newStatus : 0;
                    $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                        "Old status " . $oldStatus);
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
                    $location = isset($claim->location) ? $claim->location : '';
//                    $garage = Garage::where(['garageID' => $request->garage])->first();
                    if ($assessor->id > 0) {
                        $email_add = $assessor->email;
                        $email = [
                            'subject' => 'Vehicle Assessment - ' . $claim->vehicleRegNo,
                            'from_user_email' => 'noreply@jubileeinsurance.com',
                            'message' => "
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
                        SMSHelper::sendSMS('Hello '. $assessor->firstName .', You have been assigned to assess a claim. Vehicle registration: ' .$claim->vehicleRegNo. ', Location: ' .$location. '', $assessor->MSISDN);
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
                Assessment::where(["claimID" => $request->claimID, "assessmentStatusID" => Config::$STATUSES["ASSESSMENT"]["ASSIGNED"]["id"]])->update([
                    "assessedBy" => $request->assessor,
                    "createdBy" => Auth::id(),
                    "dateModified" => $curlDate
                ]);
                $assessor = User::where(['id' => $request->assessor])->first();
                $claim = Claim::where(['id' => $request->claimID])->first();
                $location = isset($claim->garageID) ? $claim->garageID : '';
                if ($assessor->id > 0) {
                    $email_add = $assessor->email;
                    $email = [
                        'subject' => 'Vehicle Assessment - ' . $claim->vehicleRegNo,
                        'from_user_email' => 'noreply@jubileeinsurance.com',
                        'message' => "
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
                    SMSHelper::sendSMS('Hello '. $assessor->firstName .', You have been assigned to assess a claim. Vehicle registration: ' .$claim->vehicleRegNo. ', Location: ' .$location. '', $assessor->MSISDN);
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
            $claims = Claim::with("assessment")->where("dateCreated", '>', Carbon::now()->subDays(3))->orderBy('dateCreated', 'DESC')->get();
            $assessors = User::role('Assessor')->get();
            return view('head-assessor.claims', ['claims' => $claims, 'assessors' => $assessors]);
        }catch (\Exception $e)
        {
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
                ->where("claimStatusID", "=",$claimStatusID)
                ->where("dateCreated", '>', Carbon::now()->subDays(3))
                ->orderBy('dateCreated', 'DESC')->with('assessment')->get();
            $assessors = User::role('Assessor')->get();
            return view('head-assessor.claims', ['claims' => $claims, 'assessors' => $assessors,"claimStatusID" => $claimStatusID]);
        }catch (\Exception $e)
        {
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
            $segmentIds = array(Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],Config::$ASSESSMENT_SEGMENTS['RE_INSPECTION']['ID']);
            $assessmentStatusID = $request->assessmentStatusID;
            $assessments = Assessment::where(["assessmentStatusID" =>$assessmentStatusID])
                ->whereIn('segment', $segmentIds)
                ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->get();
            return view('head-assessor.assessments',["assessments" => $assessments,'assessmentStatusID'=>$assessmentStatusID]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
    public function supplementaries(Request $request)
    {
        $id = Auth::id();
        $assessmentStatusID = $request->assessmentStatusID;
        try {
            $assessments = Assessment::where(['assessmentStatusID' => $assessmentStatusID,'segment'=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->with('claim')->with('user')->with('approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
            return view('head-assessor.supplementaries', ['assessments' => $assessments, 'assessmentStatusID' => $assessmentStatusID,'assessmentStatusID'=>$assessmentStatusID,'id'=>$id]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
    public function supplementaryReport(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $assessment = Assessment::where(["id" => $assessmentID,'segment'=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->with("claim")->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID,'segment'=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->with('part')->get();
        $jobDetails = JobDetail::where(["assessmentID" => $assessmentID,'segment'=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->get();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured= CustomerMaster::where(["customerCode" => $customerCode])->first();
        $documents = Document::where(["assessmentID" => $assessmentID,"segment"=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->get();
        $adjuster = User::where(['id'=> $assessment->claim->createdBy])->first();
        $assessor = User::where(['id'=> $assessment->assessedBy])->first();
        return view("head-assessor.view-supplementary-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor]);
    }
    public function assessmentReport(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $approved=PriceChange::where('assessmentID',$assessmentID)->first();
        $aproved=isset($approved)?$approved:'false';

        $assessment = Assessment::where(["id" => $assessmentID])->with("claim")->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID])->with('part')->get();
        $jobDetails = JobDetail::where(["assessmentID" => $assessmentID])->get();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured= CustomerMaster::where(["customerCode" => $customerCode])->first();
        $documents = Document::where(["assessmentID" => $assessmentID])->get();
        $adjuster = User::where(['id'=> $assessment->claim->createdBy])->first();
        $assessor = User::where(['id'=> $assessment->assessedBy])->first();
        return view("head-assessor.assessment-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor,'aproved'=>$aproved]);
    }
    public function reviewAssessment(Request $request)
    {
        try {
            if(isset($request->assessmentReviewType))
            {
                $assessment = Assessment::where(["id" => $request->assessmentID])->first();
                if ($request->assessmentReviewType == Config::APPROVE) {
                    $approved = Assessment::where(["id" =>$request->assessmentID])->update([
                        "assessmentStatusID" => Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'],
                        "changesDue" => 0,
                        "reviewNote" => isset($request->report) ? $request->report : null,
                        "approvedBy" => Auth::id(),
                        "approvedAt" => $this->functions->curlDate()
                    ]);
                    if ($approved) {
                        $claim = Claim::where(["id" =>$assessment->claimID])->first();
                        $assessorID = $assessment->assessedBy;
                        $assessor = User::where(["id" => $assessorID])->first();
                        $link = 'assessment-report/' . $request->assessmentID;
                        $firstName = $assessor->firstName;
                        $email = $assessor->email;
                        $MSISDN = $assessor->MSISDN;
                        $vehicleReg  = $claim->vehicleRegNo;
                        $claimNo = $claim->claimNo;
                        $reviewNote = $request->report;
                        $role = Config::$ROLES['HEAD-ASSESSOR'];

                        $message = [
                            'subject' => "Assessment Report - " .$vehicleReg,
                            'from_user_email' => Config::JUBILEE_NO_REPLY_EMAIL,
                            'message' =>"
                        Hello ".$firstName.", <br>

                        This is in regards to claim number <strong>".$claimNo." </strong> <br>

                        The assessment has been provisionally approved waiting for final approval. Find attached report. <br> <br>

                            <b><i><u>Notes</u></i></b> <br>

                            <i> ".$reviewNote." </i><br><br>

                        Regards, <br><br>

                        ".$role.", <br>

                        Claims Department, <br>

                        Jubilee Insurance Company
                    ",
                        ];

                        InfobipEmailHelper::sendEmail($message, $email);
                        SMSHelper::sendSMS('Hello '. $firstName .', Assessment for claimNo '.$claimNo.' has been provisionally approved',$MSISDN);
                        Notification::send($assessor, new ClaimApproved($claim));
                        $response = array(
                            "STATUS_CODE" => Config::SUCCESS_CODE,
                            "STATUS_MESSAGE" => "Heads up! You have successfully approved an assessment"
                        );
                    }
                }else if($request->assessmentReviewType == Config::HALT)
                {

                }

            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid data. Check your data and try again"
                );
            }

        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to approve or halt a claim " . $e->getMessage());
        }
        return json_encode($response);
    }
    public function reviewSupplementary(Request $request)
    {
        try {
            if(isset($request->assessmentReviewType))
            {
                $assessment = Assessment::where(["id" => $request->assessmentID])->first();
                if ($request->assessmentReviewType == Config::APPROVE) {
                    $approved = Assessment::where(["id" =>$request->assessmentID])->update([
                        "assessmentStatusID" => Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'],
                        "changesDue" => 0,
                        "reviewNote" => isset($request->report) ? $request->report : null,
                        "approvedBy" => Auth::id(),
                        "approvedAt" => $this->functions->curlDate()
                    ]);
                    if ($approved) {
                        $claim = Claim::where(["id" =>$assessment->claimID])->first();
                        $assessorID = $assessment->assessedBy;
                        $assessor = User::where(["id" => $assessorID])->first();
                        $link = 'assessment-report/' . $request->assessmentID;
                        $firstName = $assessor->firstName;
                        $email = $assessor->email;
                        $MSISDN = $assessor->MSISDN;
                        $vehicleReg  = $claim->vehicleRegNo;
                        $claimNo = $claim->claimNo;
                        $reviewNote = $request->report;
                        $role = Config::$ROLES['HEAD-ASSESSOR'];

                        $message = [
                            'subject' => "Assessment Report - " .$vehicleReg,
                            'from_user_email' => Config::JUBILEE_NO_REPLY_EMAIL,
                            'message' =>"
                        Hello ".$firstName.", <br>

                        This is in regards to claim number <strong>".$claimNo." </strong> <br>

                        The Supplementary has been provisionally approved waiting for final approval. Find attached report. <br> <br>

                            <b><i><u>Notes</u></i></b> <br>

                            <i> ".$reviewNote." </i><br><br>

                        Regards, <br><br>

                        ".$role.", <br>

                        Claims Department, <br>

                        Jubilee Insurance Company
                    ",
                        ];

                        InfobipEmailHelper::sendEmail($message, $email);
                        SMSHelper::sendSMS('Hello '. $firstName .', Supplementary for claimNo '.$claimNo.' has been provisionally approved',$MSISDN);
                        Notification::send($assessor, new ClaimApproved($claim));
                        $response = array(
                            "STATUS_CODE" => Config::SUCCESS_CODE,
                            "STATUS_MESSAGE" => "Heads up! You have successfully approved a Supplementary"
                        );
                    }
                }else if($request->assessmentReviewType == Config::HALT)
                {

                }

            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid data. Check your data and try again"
                );
            }

        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to approve or halt a claim " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function requestAssessmentChange(Request $request) {
        try {
            $assessment = Assessment::where('id', $request->assessmentID)->first();
            $claim = Claim::where(['id'=> $assessment->claimID])->first();
            $assessor = User::where(['id'=>isset($assessment->assessedBy) ? $assessment->assessedBy : ''])->first();
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
                    'updatedBy'=> Auth::user()->id
                ]);
                $email_add = $data['assessor']->email;
                $email = [
                    'subject' => 'Survey Report for - '.$data['reg'],
                    'from_user_email' => 'noreply@jubileeinsurance.com',
                    'message' =>"
                    Hello ".$data['assessor']->firstName.", <br>
                    This is in regards to the vehicle you've recently assessed, Registration <strong>".$data['reg']."</strong> <br>
                    You are required to make the following change(s) <br>

                    <i><u>Changes Requested</u></i>: <br>
                    <p> ".$data['change']."</p> <br><br>

                    Regards, <br><br>
                     ".$data['role'].", <br>
                    Claims Department, <br>
                    Jubilee Insurance Company of Kenya.
                ",
                ];
                InfobipEmailHelper::sendEmail($email, $email_add);
                SMSHelper::sendSMS('Hello '. $data['assessor']->firstName .', Check your email for changes due for vehicle Reg. '.$data['reg'],$data['assessor']->MSISDN);
                Notification::send($assessor, new NewChangeRequest($claim));
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Heads up! An email was sent to " .$data['assessor']->firstName . " with the requested changes"
                );
            } else {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }
        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to request for changes " . $e->getMessage());
        }
        return json_encode($response);
    }
    public function requestSupplementaryChange(Request $request) {
        try {
            $assessment = Assessment::where('id', $request->assessmentID)->first();
            $claim = Claim::where(['id'=> $assessment->claimID])->first();
            $assessor = User::where(['id'=>isset($assessment->assessedBy) ? $assessment->assessedBy : ''])->first();
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
                    'updatedBy'=> Auth::user()->id
                ]);
                $email_add = $data['assessor']->email;
                $email = [
                    'subject' => 'Supplementary Report for - '.$data['reg'],
                    'from_user_email' => 'noreply@jubileeinsurance.com',
                    'message' =>"
                    Hello ".$data['assessor']->firstName.", <br>
                    This is in regards to the vehicle you've recently submitted supplementary, Registration <strong>".$data['reg']."</strong> <br>
                    You are required to make the following change(s) <br>

                    <i><u>Changes Requested</u></i>: <br>
                    <p> ".$data['change']."</p> <br><br>

                    Regards, <br><br>
                     ".$data['role'].", <br>
                    Claims Department, <br>
                    Jubilee Insurance Company of Kenya.
                ",
                ];
                InfobipEmailHelper::sendEmail($email, $email_add);
                SMSHelper::sendSMS('Hello '. $data['assessor']->firstName .', Check your email for changes due for vehicle Reg. '.$data['reg'],$data['assessor']->MSISDN);
                Notification::send($assessor, new NewChangeRequest($claim));
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Heads up! An email was sent to " .$data['assessor']->firstName . " with the requested changes"
                );
            } else {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }
        }catch (\Exception $e)
        {
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
        $insured= CustomerMaster::where(["customerCode" => $customerCode])->first();
        $documents = Document::where(["assessmentID" => $assessmentID])->get();
        $adjuster = User::where(['id'=> $assessment->claim->createdBy])->first();
        $assessor = User::where(['id'=> $assessment->assessedBy])->first();
        return view("head-assessor.price-change-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor]);
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
            if($update)
            {
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Congratulation!, You have approved price change request"
                );
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }
        }catch (\Exception $e)
        {
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

