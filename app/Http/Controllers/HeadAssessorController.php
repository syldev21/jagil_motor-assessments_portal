<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\Claim;
use App\Helper\SMSHelper;
use App\Notifications\AssignClaim;
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
                $location = isset($claim->location) ? $claim->location : '';
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
            $claims = Claim::with("assessment")->where("dateCreated", '>', Carbon::now()->subDays(3))->orderBy('dateCreated', 'DESC')->with('assessment')->get();
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

    public function fetchAssessments(Request $request)
    {
        try {
            $assessments = Assessment::orderBy('dateCreated', 'DESC')->with('claim')->get();
            return view('head-assessor.assessments',["assessments" => $assessments]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
}
