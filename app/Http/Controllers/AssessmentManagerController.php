<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\AssessmentItem;
use App\CarModel;
use App\ChangeRequest;
use App\Claim;
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

class AssessmentManagerController extends Controller
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
        $assessmentStatusID = $request->assessmentStatusID;
        try {
            if (!isset($request->fromDate) && !isset($request->toDate) && !isset($request->regNumber)) {
                echo $assessmentStatusID;
                if($assessmentStatusID == Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                {
                    $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                        ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                        ->where('active','=',Config::ACTIVE)
                        ->where('finalApprovedAt', ">=", Carbon::now()->subDays(Config::DATE_RANGE))
                        ->orderBy('finalApprovedAt', 'DESC')->with('approver')->with('final_approver')->with('assessor')->with('claim')->with('supplementaries')->get();
                }else
                {
                    $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                        ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                        ->where('active','=',Config::ACTIVE)
                        ->orderBy('dateCreated', 'DESC')->with('approver')->with('final_approver')->with('assessor')->with('claim')->with('supplementaries')->get();
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
                    ->orderBy('dateCreated', 'DESC')->with('approver')->with('final_approver')->with('assessor')->with('claim')->with('supplementaries')->get();
            } elseif (isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber)) {
                $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
                $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
                $assessments = Assessment::where(["assessmentStatusID" => $assessmentStatusID])
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->where('active','=',Config::ACTIVE)
                    ->whereBetween('dateCreated', [$fromDate, $toDate])
                    ->orderBy('dateCreated', 'DESC')->with('approver')->with('final_approver')->with('assessor')->with('claim')->with('supplementaries')->get();
            } else {
                $assessments = array();
            }
            return view('assessment-manager.assessments', ["assessments" => $assessments, 'assessmentStatusID' => $assessmentStatusID]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }

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
        return view("assessment-manager.price-change-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor]);
    }
    public function reviewPriceChange(Request $request)
    {
        try {
            $curlDate = $this->functions->curlDate();
            $assessmentID = $request->assessmentID;

            $update = PriceChange::where('assessmentID', $assessmentID)->first();

            $update->finalApproved = 1;
            $update->finalApprover= Auth::user()->id;
            $update->finalApprovedAt = $curlDate;





//            Assessment::where('id', $assessmentID)->update([
//                'approvedBy' => Auth::user()->id,
//                'approvedAt' => $curlDate,
//                'changeTypeID'=>Config::$STATUSES['PRICE-CHANGE']['APPROVED']['id'],
//            ]);
            if($update->save())
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
    public function supplementaries(Request $request)
    {
        $id = Auth::id();
        $assessmentStatusID = $request->assessmentStatusID;
        try {
            $assessments = Assessment::where(['assessmentStatusID' => $assessmentStatusID,'segment'=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],'active'=>Config::ACTIVE])->with('claim')->with('user')->with('approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
            return view('assessment-manager.supplementaries', ['assessments' => $assessments, 'assessmentStatusID' => $assessmentStatusID,'assessmentStatusID'=>$assessmentStatusID,'id'=>$id]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
    public  function requestPriceChange(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $priceChange=PriceChange::where(['assessmentID'=>$assessmentID])->first();
        $priceChange->approvedBy=null;
        $priceChange->approvedAt=null;
        $priceChange->finalApproved=1;
        $priceChange->finalApprover=null;
        $priceChange->finalApprovedAt=null;
        $priceChange->changed=false;
        $priceChange->save();
    }
    public function assessmentReport(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $priceChange=PriceChange::where('assessmentID',$assessmentID)->first();
        $aproved=isset($priceChange)?$priceChange:'false';

        $assessment = Assessment::where(["id" => $assessmentID])->with("claim")->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID])->with('part')->get();
        $jobDetails = JobDetail::where(["assessmentID" => $assessmentID])->get();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured= CustomerMaster::where(["customerCode" => $customerCode])->first();
        $documents = Document::where(["assessmentID" => $assessmentID])->get();
        $adjuster = User::where(['id'=> $assessment->claim->createdBy])->first();
        $assessor = User::where(['id'=> $assessment->assessedBy])->first();
        $carDetail = CarModel::where(['makeCode'=> isset($assessment['claim']['carMakeCode']) ? $assessment['claim']['carMakeCode'] : '','modelCode'=> isset($assessment['claim']['carModelCode']) ? $assessment['claim']['carModelCode'] : ''])->first();
        return view("assessment-manager.assessment-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor,'aproved'=>$aproved,'carDetail'=>$carDetail,'priceChange'=>$priceChange]);
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
        return view("assessment-manager.view-supplementary-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor]);
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
                        $assessorID = $assessment->assessedBy;
                        $assessor = User::where(["id" => $assessorID])->first();
                        $userDetails = array(
                            array(
                                "id"=> $adjuster->id,
                                "name" => $adjuster->firstName,
                                "email" => $adjuster->email,
                                "MSISDN" => $adjuster->MSISDN,
                                "role" =>Config::$ROLES['ADJUSTER']
                            )
//                            array(
//                                "id"=> $assessor->id,
//                                "name" => $assessor->firstName,
//                                "email" => $assessor->email,
//                                "MSISDN" => $assessor->MSISDN,
//                                "role" => Config::$ROLES['ASSESSOR']
//                            )

                        );
                        $link = 'assessment-report/' . $request->assessmentID;
                        $vehicleReg  = $claim->vehicleRegNo;
                        $claimNo = $claim->claimNo;
                        $reviewNote = $request->report;
                        $role = Config::$ROLES['ASSESSMENT-MANAGER'];

                        foreach ($userDetails as $userDetail)
                        {
                            $message = [
                                'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                                'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'to' => $userDetail['email'],
                                'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'html' =>"
                        Hello ".$userDetail['name'].", <br>

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

                            $logData = array(
                                "vehicleRegNo" => $claim->vehicleRegNo,
                                "claimNo" => $claim->claimNo,
                                "policyNo" => $claim->policyNo,
                                "userID" => Auth::user()->id,
                                "role" => Config::$ROLES['ADJUSTER'],
                                "activity" => Config::ACTIVITIES['FINAL_APPROVAL'],
                                "notification" => $message['html'],
                                "notificationTo" => $userDetail['email'],
                                "notificationType" => Config::NOTIFICATION_TYPES['EMAIL'],
                            );
                            $this->functions->logActivity($logData);
                            $smsMessage = 'Hello '. $userDetail['name'] .', Assessment for claimNo '.$claimNo.' has been approved';
                            InfobipEmailHelper::sendEmail($message, $userDetail['email']);
                            SMSHelper::sendSMS($smsMessage,$userDetail['MSISDN']);
                            $logData['notification'] = $smsMessage;
                            $logData['notificationTo'] = $userDetail['MSISDN'];
                            $logData['notificationType'] = Config::NOTIFICATION_TYPES['SMS'];
                            $this->functions->logActivity($logData);
                            $user = User::where(["id" => $userDetail['id']])->first();
                            Notification::send($user, new ClaimApproved($claim));
                        }
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
                        $assessorID = $assessment->assessedBy;
                        $assessor = User::where(["id" => $assessorID])->first();
                        $userDetails = array(
                            array(
                                "id"=> $adjuster->id,
                                "name" => $adjuster->firstName,
                                "email" => $adjuster->email,
                                "MSISDN" => $adjuster->MSISDN
                            ),
                            array(
                                "id"=> $assessor->id,
                                "name" => $assessor->firstName,
                                "email" => $assessor->email,
                                "MSISDN" => $assessor->MSISDN
                            )

                        );
                        $link = 'assessment-report/' . $request->assessmentID;
                        $vehicleReg  = $claim->vehicleRegNo;
                        $claimNo = $claim->claimNo;
                        $reviewNote = $request->report;
                        $role = Config::$ROLES['ASSESSMENT-MANAGER'];

                        foreach ($userDetails as $userDetail)
                        {
                            $message = [
                                'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                                'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'to' => $userDetail['email'],
                                'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'html' =>"
                        Hello ".$userDetail['name'].", <br>

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

                            InfobipEmailHelper::sendEmail($message, $userDetail['email']);
//                            SMSHelper::sendSMS('Hello '. $userDetail['name'] .', Assessment for claimNo '.$claimNo.' has been approved',$userDetail['MSISDN']);
                            $user = User::where(["id" => $userDetail['id']])->first();
                            Notification::send($user, new ClaimApproved($claim));
                        }
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
                'role' => Config::$ROLES['ASSESSMENT-MANAGER']
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
                    'updatedBy'=> Auth::user()->id
                ]);
                $email_add = $data['assessor']->email;
                $email = [
                    'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                    'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'to' => $email_add,
                    'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'cc' => Auth::user()->email,
                    'html' =>"
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
                $smsMessage = 'Hello '. $data['assessor']->firstName .', Check your email for changes due for vehicle Reg. '.$data['reg'];
                SMSHelper::sendSMS($smsMessage,$data['assessor']->MSISDN);

                $logData['notification'] = $smsMessage;
                $logData['notificationTo'] = $data['assessor']->MSISDN;
                $logData['notificationType'] = Config::NOTIFICATION_TYPES['SMS'];
                $this->functions->logActivity($logData);
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
                'role' => Config::$ROLES['ASSESSMENT-MANAGER']
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
                    'updatedBy'=> Auth::user()->id
                ]);
                $email_add = $data['assessor']->email;
                $email = [
                    'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                    'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'to' => $email_add,
                    'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'cc' => Auth::user()->email,
                    'html' =>"
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

    public function claims(Request $request)
    {
        try {
            $claimStatusID = $request->claimStatusID;

            $claims = Claim::with("assessment")
                ->where("claimStatusID", "=", $claimStatusID)
                ->where("active", "=", Config::ACTIVE)
                ->orderBy('dateCreated', 'DESC')->with('assessment')->get();
            $assessors = User::role('Assessor')->get();
            return view('assessment-manager.claims', ['claims' => $claims, 'assessors' => $assessors, "claimStatusID" => $claimStatusID]);
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
