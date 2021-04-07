<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\Claim;
use App\Conf\Config;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\Helper\SMSHelper;
use App\Notifications\ClaimApproved;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ApproverController extends Controller
{
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }
    public function reviewAssessment(Request $request)
    {
        try {
            if(isset($request->assessmentReviewType))
            {
                $assessment = Assessment::where(["id" => $request->assessmentID])->first();
                if ($request->assessmentReviewType == Config::APPROVE) {
                    $approved = Assessment::where(["id" =>$request->assessmentID])->update([
                        "assessmentStatusID" => Config::$STATUSES['ASSESSMENT']['APPROVED']['id'],
                        "changesDue" => 0,
                        "reviewNote" => isset($request->report) ? $request->report : null,
                        "finalApprovalBy" => Auth::id(),
                        "finalApprovedAt" => $this->functions->curlDate()
                    ]);
                    if ($approved) {
                        $claim = Claim::where(["id" =>$assessment->claimID])->first();
                        $adjusterID = $claim->createdBy;
                        $adjuster = User::where(["id" => $adjusterID])->first();
                        $link = 'assessment-report/' . $request->assessmentID;
                        $name = $adjuster->name;
                        $email = $adjuster->email;
                        $MSISDN = $adjuster->MSISDN;
                        $vehicleReg  = $claim->vehicleRegNo;
                        $claimNo = $claim->claimNo;
                        $reviewNote = $request->report;
                        $role = Config::$ROLES['ASSESSMENT-MANAGER'];

                        $message = [
                            'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo,
                            'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                            'to' => $email,
                            'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                            'html' =>"
                        Hello ".$name.", <br>

                        This is in regards to claim number <strong>".$claimNo." </strong> <br>

                        The assessment is approved and complete. Find attached report. <br> <br>

                            <b><i><u>Notes</u></i></b> <br>

                            <i> ".$reviewNote." </i><br><br>

                        Regards, <br><br>

                        ".$role.", <br>

                        Claims Department, <br>

                        Jubilee Insurance Company
                    ",
                        ];

                        InfobipEmailHelper::sendEmail($message, $email);
                        SMSHelper::sendSMS('Hello '. $name .', Assessment for claimNo '.$claimNo.' has been approved',$MSISDN);
                        Notification::send($adjuster, new ClaimApproved($claim));
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
}
