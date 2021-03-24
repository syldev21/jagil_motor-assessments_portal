<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\Claim;
use App\Conf\Config;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
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
        $assessmentStatusIDs = array(Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'],Config::$STATUSES['ASSESSMENT']['APPROVED']['id']);

        try {
            if(!isset($request->fromDate) && !isset($request->toDate) && !isset($request->regNumber))
            {
                $asmts=Assessment::whereIn('assessmentStatusID', $assessmentStatusIDs)
                    ->where('segment','=',Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
                $assessments = Assessment::where("assessedBy","!=",$id)
                    ->where('segment',"!=",Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->where('dateCreated',">=",Carbon::now()->subDays(Config::DATE_RANGE))
                    ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('reInspection')->orderBy('dateCreated', 'DESC')->get();
            }elseif (isset($request->regNumber))
            {
//              $regNo = preg_replace("/\s+/", "", $request->regNumber);
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

//              $claimids = Claim::where('vehicleRegNo','like', '%'.$request->regNumber.'%')->pluck('id')->toArray();

                $asmts=Assessment::whereIn('assessmentStatusID', $assessmentStatusIDs)
                    ->where('segment','=',Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
                $assessments = Assessment::where("assessedBy","!=",$id)
                    ->where('segment',"!=",Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereIn('claimID', $claimids)
                    ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('reInspection')->orderBy('dateCreated', 'DESC')->get();
            }elseif(isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber))
            {
                $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
                $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
                $asmts=Assessment::whereIn('assessmentStatusID', $assessmentStatusIDs)
                    ->where('segment','=',Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
                $assessments = Assessment::where("assessedBy","!=",$id)
                    ->where('segment',"!=",Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereBetween('dateCreated', [$fromDate, $toDate])
                    ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('reInspection')->orderBy('dateCreated', 'DESC')->get();
            }
            return view('common.re-inspections', ['assessments' => $assessments, 'assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['APPROVED']['id'],'asmts'=>$asmts]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }

    public function fetchClaimsByType(Request $request)
    {
        $assessmentTypeID = $request->assessmentTypeID;
        try {
            $assessments = Assessment::where(['assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['APPROVED']['id'],'assessmentTypeID' =>$assessmentTypeID])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->get();
            return view('common.assessment-types',['assessments' => $assessments,'assessmentStatusID'=>Config::$STATUSES['ASSESSMENT']['APPROVED']['id'],'assessmentTypeID'=>$assessmentTypeID]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }

    public function sendNotification(Request $request)
    {
        $email = $request->email;
        $message = $request->message;
        $subject = $request->subject;
        $flag = false;
        $senderEmail = Auth::user()->email;

        $message = [
            'subject' => $subject,
            'from_user_email' => Config::JUBILEE_NO_REPLY_EMAIL,
            'message' => $message,
        ];

        InfobipEmailHelper::sendEmail($message, $email);
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
}
