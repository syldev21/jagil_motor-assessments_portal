<?php

namespace App\Http\Controllers;

use App\AssessmentItem;
use App\CarModel;
use App\ChangeRequest;
use App\Claim;
use App\Assessment;
use App\Conf\Config;
use App\CustomerMaster;
use App\Document;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\Helper\SMSHelper;
use App\JobDetail;
use App\Notifications\ClaimApproved;
use App\Notifications\NewChangeRequest;
use App\PriceChange;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AssistantHeadAssessorController extends Controller
{
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }
    public function assessments(Request $request)
    {
        try {
            $assessmentStatusID = $request->assessmentStatusID;
            if (!isset($request->fromDate) && !isset($request->toDate) && !isset($request->regNumber)) {
                if($assessmentStatusID == Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                {
                    $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                        ->where('totalCost', '<=', Config::HEAD_ASSESSOR_THRESHOLD)
                        ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                        ->where('dateCreated', ">=", Carbon::now()->subDays(Config::DATE_RANGE))
                        ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
                }else
                {
                    $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                        ->where('totalCost', '<=', Config::HEAD_ASSESSOR_THRESHOLD)
                        ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                        ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
                }
            }elseif (isset($request->regNumber))
            {
                $claimids = Claim::where('vehicleRegNo','like', '%'.$request->regNumber.'%')->pluck('id')->toArray();
                $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                    ->where('totalCost', '<=', Config::HEAD_ASSESSOR_THRESHOLD)
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereIn('claimID', $claimids)
                    ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
            }elseif(isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber))
            {
                $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
                $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
                $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                    ->where('totalCost', '<=', Config::HEAD_ASSESSOR_THRESHOLD)
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereBetween('dateCreated', [$fromDate, $toDate])
                    ->orderBy('dateCreated', 'DESC')->with('claim')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
            }else
            {
                $assessments = array();
            }

            return view('assistant-head-assessor.assessments',["assessments" => $assessments,'assessmentStatusID'=>$assessmentStatusID]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
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
        $carDetail = CarModel::where(['makeCode' => isset($assessment['claim']['carMakeCode']) ? $assessment['claim']['carMakeCode'] : '', 'modelCode' => isset($assessment['claim']['carModelCode']) ? $assessment['claim']['carModelCode'] : ''])->first();
        return view("assistant-head-assessor.assessment-report", ['assessment' => $assessment, "assessmentItems" => $assessmentItems, "jobDetails" => $jobDetails, "insured" => $insured, 'documents' => $documents, 'adjuster' => $adjuster, 'assessor' => $assessor, 'aproved' => $aproved, 'carDetail' => $carDetail,'priceChange'=>$priceChange]);
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
                'role' => Config::$ROLES['ASSISTANT-HEAD']
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
                    'updatedBy' => Auth::user()->id
                ]);
                $email_add = $data['assessor']->email;
                $email = [
                    'subject' => 'Survey Report for - ' . $data['reg'],
                    'from_user_email' => 'noreply@jubileeinsurance.com',
                    'message' => "
                    Hello " . $data['assessor']->firstName . ", <br>
                    This is in regards to the vehicle you've recently assessed, Registration <strong>" . $data['reg'] . "</strong> <br>
                    You are required to make the following change(s) <br>

                    <i><u>Changes Requested</u></i>: <br>
                    <p> " . $data['change'] . "</p> <br><br>

                    Regards, <br><br>
                     " . $data['role'] . ", <br>
                    Claims Department, <br>
                    Jubilee Insurance Company of Kenya.
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

    public function reviewAssessment(Request $request)
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
                        $role = Config::$ROLES['ASSISTANT-HEAD'];

                        $message = [
                            'subject' => "Assessment Report - " . $vehicleReg,
                            'from_user_email' => Config::JUBILEE_NO_REPLY_EMAIL,
                            'message' => "
                        Hello " . $firstName . ", <br>

                        This is in regards to claim number <strong>" . $claimNo . " </strong> <br>

                        The assessment has been provisionally approved waiting for final approval. Find attached report. <br> <br>

                            <b><i><u>Notes</u></i></b> <br>

                            <i> " . $reviewNote . " </i><br><br>

                        Regards, <br><br>

                        " . $role . ", <br>

                        Claims Department, <br>

                        Jubilee Insurance Company
                    ",
                        ];

                        InfobipEmailHelper::sendEmail($message, $email);
                        SMSHelper::sendSMS('Hello ' . $firstName . ', Assessment for claimNo ' . $claimNo . ' has been provisionally approved', $MSISDN);
                        Notification::send($assessor, new ClaimApproved($claim));
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
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to approve or halt a claim " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function supplementaries(Request $request)
    {
        $id = Auth::id();
        $assessmentStatusID = $request->assessmentStatusID;
        try {
            $assessments = Assessment::where(['assessmentStatusID' => $assessmentStatusID, 'segment' => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->with('claim')->with('user')->with('approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
            return view('assistant-head-assessor.supplementaries', ['assessments' => $assessments, 'assessmentStatusID' => $assessmentStatusID, 'assessmentStatusID' => $assessmentStatusID, 'id' => $id]);
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
        return view("assistant-head-assessor.view-supplementary-report", ['assessment' => $assessment, "assessmentItems" => $assessmentItems, "jobDetails" => $jobDetails, "insured" => $insured, 'documents' => $documents, 'adjuster' => $adjuster, 'assessor' => $assessor]);
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
                        $role = Config::$ROLES['ASSISTANT-HEAD'];

                        $message = [
                            'subject' => "Assessment Report - " . $vehicleReg,
                            'from_user_email' => Config::JUBILEE_NO_REPLY_EMAIL,
                            'message' => "
                        Hello " . $firstName . ", <br>

                        This is in regards to claim number <strong>" . $claimNo . " </strong> <br>

                        The Supplementary has been provisionally approved waiting for final approval. Find attached report. <br> <br>

                            <b><i><u>Notes</u></i></b> <br>

                            <i> " . $reviewNote . " </i><br><br>

                        Regards, <br><br>

                        " . $role . ", <br>

                        Claims Department, <br>

                        Jubilee Insurance Company
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
                'role' => Config::$ROLES['ASSISTANT-HEAD']
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
                    'updatedBy' => Auth::user()->id
                ]);
                $email_add = $data['assessor']->email;
                $email = [
                    'subject' => 'Supplementary Report for - ' . $data['reg'],
                    'from_user_email' => 'noreply@jubileeinsurance.com',
                    'message' => "
                    Hello " . $data['assessor']->firstName . ", <br>
                    This is in regards to the vehicle you've recently submitted supplementary, Registration <strong>" . $data['reg'] . "</strong> <br>
                    You are required to make the following change(s) <br>

                    <i><u>Changes Requested</u></i>: <br>
                    <p> " . $data['change'] . "</p> <br><br>

                    Regards, <br><br>
                     " . $data['role'] . ", <br>
                    Claims Department, <br>
                    Jubilee Insurance Company of Kenya.
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
        return view("assistant-head-assessor.price-change-report", ['assessment' => $assessment, "assessmentItems" => $assessmentItems, "jobDetails" => $jobDetails, "insured" => $insured, 'documents' => $documents, 'adjuster' => $adjuster, 'assessor' => $assessor]);
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

    public function claims(Request $request)
    {
        try {
            $claimStatusID = $request->claimStatusID;

            $claims = Claim::with("assessment")
                ->where("claimStatusID", "=", $claimStatusID)
                ->orderBy('dateCreated', 'DESC')->with('assessment')->get();
            $assessors = User::role('Assessor')->get();
            return view('assistant-head-assessor.claims', ['claims' => $claims, 'assessors' => $assessors, "claimStatusID" => $claimStatusID]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch claims " . $e->getMessage());
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
        }
    }
}
