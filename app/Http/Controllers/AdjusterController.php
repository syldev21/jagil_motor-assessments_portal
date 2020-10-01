<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\AssessmentItem;
use App\Claim;
use App\ClaimTracker;
use App\Document;
use App\Helper\SMSHelper;
use App\JobDetail;
use App\Location;
use App\Notifications\NewClaimNotification;
use App\StatusTracker;
use App\Conf\Config;
use App\CustomerMaster;
use App\Garage;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\Role;
use App\User;
use App\UserHasRole;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdjusterController extends Controller
{
    //
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }

    public function fetchPremiaClaims(Request $request)
    {
//        $claims = array(
//            0 =>
//                array(
//                    'CLM_NO' => 'C/101/1001/2020/000973',
//                    'CLM_POL_NO' => 'P/101/1001/2019/004307',
//                    'VEH_REG_NO' => 'KCJ 878R',
//                    'SUM_INSURED' => '800000',
//                    'CLM_LOSS_DT' => '09/08/2020',
//                    'CLM_INTM_DT' => '28/08/2020 16:15',
//                    'EXCESS_AMT' => '15000',
//                    'CLAIM_TYPE' => 'Assessment',
//                    'CUST_CODE' => 'K71005098',
//                    'CUST_NAME' => 'Mr.DAVID MUTITU  KAMAU',
//                    'CUST_MOBILE_NO' => '74158845',
//                    'CUST_EMAIL1' => 'faridikim @gmail.co ',
//                    'BRANCH' => 'Jubctr',
//                ),
//            1 =>
//                array(
//                    'CLM_NO' => 'C/101/1001/202',
//                    'CLM_POL_NO' => 'P/101/1001/20',
//                    'VEH_REG_NO' => 'KCL 265K',
//                    'SUM_INSURED' => '650000',
//                    'CLM_LOSS_DT' => '25/08/2020',
//                    'CLM_INTM_DT' => '28/08/2020',
//                    'EXCESS_AMT' => '15000',
//                    'CLAIM_TYPE' => 'Assessment',
//                    'CUST_CODE' => 'K71005098',
//                    'CUST_NAME' => 'Mr.DAVID MUTITU KAMAU',
//                    'CUST_MOBILE_NO' => 74158845,
//                    'CUST_EMAIL1' => 'faridikim @gmail.com ',
//                    'BRANCH' => 'Jubctr',
//                ),
//            2 =>
//                array(
//                    'CLM_NO' => 'C/101/1001/2020/0009',
//                    'CLM_POL_NO' => 'P/101/1001/2018/001',
//                    'VEH_REG_NO' => 'KCS 641L',
//                    'SUM_INSURED' => '720000',
//                    'CLM_LOSS_DT' => '26/08/2020',
//                    'CLM_INTM_DT' => '28/08/2020 13:03',
//                    'EXCESS_AMT' => '15000',
//                    'CLAIM_TYPE' => 'Assessment',
//                    'CUST_CODE' => 'K71005098',
//                    'CUST_NAME' => 'Mr.DAVID MUTITU  KAMAU',
//                    'CUST_MOBILE_NO' => '74158845',
//                    'CUST_EMAIL1' => 'faridikim@gmail.com',
//                    'BRANCH' => 'Jubctr',
//                ),
//            3 =>
//                array(
//                    'CLM_NO' => 'C/101/1001/2020/000970',
//                    'CLM_POL_NO' => 'P/101/1001/2019/002366/01',
//                    'VEH_REG_NO' => 'KCS 249D',
//                    'SUM_INSURED' => '590000',
//                    'CLM_LOSS_DT' => '27/08/2020',
//                    'CLM_INTM_DT' => '28/08/2020 12:50',
//                    'EXCESS_AMT' => '15000',
//                    'CLAIM_TYPE' => 'Assessment',
//                    'CUST_CODE' => 'K71005098',
//                    'CUST_NAME' => 'Mr.DAVID MUTITU  KAMAU',
//                    'CUST_MOBILE_NO' => '74158845',
//                    'CUST_EMAIL1' => 'faridikim@gmail.com',
//                    'BRANCH' => 'Jubctr',
//                ),
//            4 =>
//                array(
//                    'CLM_NO' => 'C/101/1001/2020/000967',
//                    'CLM_POL_NO' => 'P/101/1001/2020/000473',
//                    'VEH_REG_NO' => 'KCX 764U',
//                    'SUM_INSURED' => '720000',
//                    'CLM_LOSS_DT' => '27/08/2020',
//                    'CLM_INTM_DT' => '27/08/2020 16:09',
//                    'EXCESS_AMT' => '15000',
//                    'CLAIM_TYPE' => 'Assessment',
//                    'CUST_CODE' => 'K71005098',
//                    'CUST_NAME' => 'Mr.DAVID MUTITU  KAMAU',
//                    'CUST_MOBILE_NO' => '74158845',
//                    'CUST_EMAIL1' => 'faridikim@gmail.com',
//                    'BRANCH' => 'Jubctr',
//                ),
//            5 =>
//                array(
//                    'CLM_NO' => 'C/101/1001/2020/000953',
//                    'CLM_POL_NO' => 'P/101/1001/2020/000081',
//                    'VEH_REG_NO' => 'KCH 033Z',
//                    'SUM_INSURED' => '3000000',
//                    'CLM_LOSS_DT' => '21/08/2020',
//                    'CLM_INTM_DT' => '24/08/2020 16:50',
//                    'EXCESS_AMT' => '15000',
//                    'CLAIM_TYPE' => 'Assessment',
//                    'CUST_CODE' => 'K71005098',
//                    'CUST_NAME' => 'Mr.DAVID MUTITU KAMAU',
//                    'CUST_MOBILE_NO' => '74158845',
//                    'CUST_EMAIL1' => 'faridikim@gmail.com',
//                    'BRANCH' => 'Jubctr',
//                ),
//            6 =>
//                array(
//                    'CLM_NO' => 'C/101/1001/2020/000943',
//                    'CLM_POL_NO' => 'P/101/1001/2019/002323/01',
//                    'VEH_REG_NO' => 'KBG 125K',
//                    'SUM_INSURED' => '500000',
//                    'CLM_LOSS_DT' => '10/08/2020',
//                    'CLM_INTM_DT' => '21/08/2020 10:49',
//                    'EXCESS_AMT' => '15000',
//                    'CLAIM_TYPE' => 'Assessment',
//                    'CUST_CODE' => 'K71005098',
//                    'CUST_NAME' => 'Mr.DAVID MUTITU  KAMAU',
//                    'CUST_MOBILE_NO' => '74158845',
//                    'CUST_EMAIL1' => 'faridikim@gmail.com',
//                    'BRANCH' => 'Jubctr',
//                ),
//        );
        $utility = new Utility();
        $access_token = $utility->getToken();

        $toDate = Carbon::now()->toDateTimeString();
        $fromDate = Carbon::now()->subDays(Config::DATES_LIMIT)->toDateTimeString();
        $data = ["fromDate"=>$fromDate,"toDate" => $toDate];
        $response = $utility->getData($data, '/api/v1/b2b/general/claim/fetch', 'POST');
        $claim_data = json_decode($response->getBody()->getContents());
        if ($claim_data->status == 'success' && sizeof($claim_data->data->DB_VALUE1) != 0) {
            $claims =json_decode(json_encode($claim_data->data->DB_VALUE1),true);
        }else{
            $claims = [];
        }

        return view('adjuster.index', ['claims' => $claims]);
    }

    public function claimForm(Request $request)
    {
        $claim = json_decode($request->getContent(), true);
        $locations = Location::all();
        return view('adjuster.claim-form', ['claim' => $claim,'locations' =>$locations]);
    }
    public function claimDetails(Request $request,$claimID)
    {
        $claim = Claim::where(["id"=>$claimID])->with('customer')->with('assessment')->first();
        $assessments =Assessment::where(['claimID' => $claim->id])->with('user')->get();
        return view('adjuster.claim-details', ['claim' => $claim,"assessments" =>$assessments]);
    }

    public function addClaim(Request $request)
    {
        try {
            $claimNo = $request->claimNo;
            $customerCode = $request->customerCode;
            $email = $request->email;
            $policyNo = $request->policyNo;
            $branch = $request->branch;
            $vehicleRegNo = $request->vehicleRegNo;
            $claimType = $request->claimType;
            $sumInsured = $request->sumInsured;
            $excess = $request->excess;
            $intimationDate = date('Y-m-d H:i:s', strtotime($request->intimationDate));
            $loseDate = date('Y-m-d H:i:s', strtotime($request->loseDate));
            $curDate = $this->functions->curlDate();
            $fullName = $request->fullName;
            $location = $request->location;
            $originalExcess = $request->originalExcess;
            $originalSumInsured = $request->originalSumInsured;

            $claims = Claim::where(['claimNo' => $claimNo])->limit(1)->get();
            if (count($claims) == 0) {
                $customers = CustomerMaster::where(['customerCode' => $customerCode])->limit(1)->get();
                if (count($customers) == 0) {
                    $firstName = '';
                    $middleName = '';
                    $lastName = '';
                    if (isset($fullName)) {
                        $fullNameArray = explode(' ', $fullName);
                        $firstName = isset($fullNameArray[0]) ? $fullNameArray[0] : '';
                        $middleName = isset($fullNameArray[1]) ? $fullNameArray[1] : '';
                        $lastName = isset($fullNameArray[2]) ? $fullNameArray[2] : '';
                    }
                    $customerID = CustomerMaster::insertGetId([
                        "customerCode" => $request->customerCode,
                        "MSISDN" => $request->MSISDN,
                        "firstName" => $firstName,
                        "middleName" => $middleName,
                        "lastName" => $lastName,
                        "fullname" => $fullName,
                        "customerType" => Config::$CUSTOMER_TYPE['INSURED_CUSTOMER'],
                        "email" => $email
                    ]);
                    $claimID = 0;
                    if ($customerID > 0) {
                        $claimID = Claim::insertGetId([
                            "claimNo" => $claimNo,
                            "policyNo" => $policyNo,
                            "branch" => $branch,
                            "vehicleRegNo" => $vehicleRegNo,
                            "customerCode" => $customerCode,
                            "claimType" => $claimType,
                            "sumInsured" => $sumInsured,
                            "excess" => $excess,
                            "claimStatusID" => Config::$STATUSES['CLAIM']['UPLOADED']['id'],
                            "intimationDate" => $intimationDate,
                            "loseDate" => $loseDate,
                            "location" => $location,
                            "dateCreated" => $curDate
                        ]);
                        StatusTracker::create([
                            "claimID" =>$claimID,
                            "newStatus"=> Config::$STATUSES['CLAIM']['UPLOADED']['id'],
                            "oldStatus" => Config::DEFAULT_STATUS,
                            "statusType" => Config::$STATUS_TYPES["CLAIM"],
                            "dateCreated" => $curDate
                        ]);
                        if($originalExcess != $excess || $originalSumInsured != $sumInsured)
                        {
                            $createdBy = Auth::id();
                            $claimTrackerID =ClaimTracker::insertGetId([
                                'claimID' => $claimID,
                                'claimNo' => $claimNo,
                                'policyNo' => $policyNo,
                                'createdBy' => $createdBy,
                                'excess' =>    $originalExcess,
                                'sumInsured' => $originalSumInsured,
                                'location' => $location
                            ]);
                            if($claimTrackerID > 0)
                            {
                                Claim::where(['id' => $claimID])->update([
                                    "changed" => Config::ACTIVE
                                ]);
                            }
                        }
                        $response = array(
                            "STATUS_CODE" => Config::SUCCESS_CODE,
                            "STATUS_MESSAGE" => "Congratulations!, You have successfully created a claim on the portal"
                        );
                    } else {

                        $response = array(
                            "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                            "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                        );
                    }
                } else {
                    $claimID = Claim::insertGetId([
                        "claimNo" => $claimNo,
                        "policyNo" => $policyNo,
                        "branch" => $branch,
                        "vehicleRegNo" => $vehicleRegNo,
                        "customerCode" => $customerCode,
                        "claimType" => $claimType,
                        "sumInsured" => $sumInsured,
                        "excess" => $excess,
                        "claimStatusID" => Config::$STATUSES['CLAIM']['UPLOADED']['id'],
                        "intimationDate" => $intimationDate,
                        "loseDate" => $loseDate,
                        "location" => $location,
                        "dateCreated" => $curDate
                    ]);
                    $response = array(
                        "STATUS_CODE" => Config::SUCCESS_CODE,
                        "STATUS_MESSAGE" => "Congratulation!, You have successfully created a claim on the portal"
                    );
                }
                if ($claimID > 0) {
                    $headAssessors = User::role('Head Assessor')->get(); // Returns only users with the role 'Head Assessor'
                    if (count($headAssessors) > 0) {
                        foreach ($headAssessors as $headAssessor) {
                            $data = [
                                'claim' => $claimNo,
                                'reg' => $vehicleRegNo,
                                'headAssessor' => $headAssessor->firstName,
                                'email' => $headAssessor->email
                            ];

                            $email_add = $data['email'];
                            // $email_add = 'brian.otwoma@jubileekenya.com';
                            $email = [
                                'subject' => "New Claim " . $data['reg'],
                                'from_user_email' => 'noreply@jubileeinsurance.com',
                                'message' => "Hello " . $data['headAssessor'] . ", <br> <br>

                            A new claim " . $data['claim'] . " has been created. You are required to assign an assessor. <br>

                            <strong>Vehicle Registration</strong>: " . $data['reg'] . "  <br>

                            <strong>Created by</strong>:  <br><br>



                            Claims Department, <br>

                            Jubilee Insurance.
                        ",
                            ];
//                            $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
//                            SMSHelper::sendSMS('Hello '. $headAssessor->firstName .', A new claim : '.$claimNo.' has been created. You are required to assign an assessor',$headAssessor->MSISDN);
                            $claim = Claim::where(['id' => $claimID])->first();
                            Notification::send($headAssessors, new NewClaimNotification($claim));
                        }
                    }
                }
            } else {
                $response = array(
                    "STATUS_CODE" => Config::RECORD_ALREADY_EXISTS,
                    "STATUS_MESSAGE" => "Claim No " . $claimNo . "Already exists"
                );
            }
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to create a claim. Error message " . $e->getMessage());
        }

        return json_encode($response);
    }

    public function fetchUploadedClaims(Request $request)
    {
        $assessors = User::role('Assessor')->get();
        $claims = Claim::where("dateCreated", '>', Carbon::now()->subDays(3))->where('claimStatusID','=',Config::$STATUSES['CLAIM']['UPLOADED']['id'])->get();
        return view('adjuster.claims', ['claims' => $claims, 'assessors' => $assessors]);
    }
    public function assignedClaims(Request $request)
    {
        $assessors = User::role('Assessor')->get();
        $claims = Claim::where("dateCreated", '>', Carbon::now()->subDays(3))->where('claimStatusID','=',Config::$STATUSES['CLAIM']['ASSIGNED']['id'])->get();
        return view('adjuster.claims', ['claims' => $claims, 'assessors' => $assessors]);
    }
    public function fetchClaims(Request $request)
    {
        $assessors = User::role('Assessor')->get();
        $claims = Claim::where("dateCreated", '>', Carbon::now()->subDays(3))->get();
        return view('adjuster.claims', ['claims' => $claims, 'assessors' => $assessors]);
    }

    public function uploadDocumentsForm(Request $request, $claimID)
    {
        $claim = Claim::where(['id' => $claimID])->first();
        return view('adjuster.file-upload', ['claim' => $claim]);
    }

    public function searchClaim(Request $request)
    {
        $userID = 3;
        if(isset($request->fromDate) && isset($request->toDate) && !isset($request->vehicleRegNo))
        {
            $response =$this->functions->search($userID,$request->fromDate,$request->toDate,'');

        }else if(isset($request->vehicleRegNo))
        {
            $response =$this->functions->search($userID,'','',$request->vehicleRegNo);
        }
        return $response;
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
    public function fetchAssignedAssessments(Request $request)
    {
        try {
            $assessments = Assessment::where('assessmentStatusID','=',Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id'])->with('claim')->with('user')->get();
            return view('adjuster.assessments',['assessments' => $assessments]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
    public function fetchDraftAssessments(Request $request)
    {
        try {
            $assessments = Assessment::where('assessmentStatusID','=',Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'])->with('claim')->with('user')->get();
            return view('adjuster.assessments',['assessments' => $assessments]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
    public function fetchAssessedAssessments(Request $request)
    {
        try {
            $assessments = Assessment::where('assessmentStatusID','=',Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'])->with('claim')->with('user')->get();
            return view('adjuster.assessments',['assessments' => $assessments]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
    public function assessmentDetails(Request $request,$assessmentID)
    {
        try {
            $assessment = Assessment::where('id','=',$assessmentID)->with('claim')->with('user')->first();
            $customer = CustomerMaster::where(['customerCode' =>$assessment->claim->customerCode])->first();
            return view('adjuster.assessment-details',['assessment' => $assessment,'customer' => $customer]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessment details. Error message " . $e->getMessage());
        }
    }
    public function editClaimForm(Request $request,$claimID)
    {
        $claim = Claim::where(["id"=>$claimID])->with('customer')->first();
        return view("adjuster.edit-claim-form",['claim' => $claim]);
    }

    public function updateClaim(Request $request)
    {
        try {
            $claimID= $request->claimID;
            $claim = Claim::where(['id' => $claimID])->first();
            if($claim->id > 0)
            {
                $oldexcess = $claim->excess;
                $oldsumInsured = $claim->sumInsured;
                $oldLocation = $claim->location;
                $claimNo = $claim->claimNo;
                $policyNo = $claim->policyNo;
                $createdBy = Auth::id();
                $claimTrackerID =ClaimTracker::insertGetId([
                    'claimID' => $claimID,
                    'claimNo' => $claimNo,
                    'policyNo' => $policyNo,
                    'createdBy' => $createdBy,
                    'excess' => $oldexcess,
                    'sumInsured' => $oldsumInsured,
                    'location' => $oldLocation
                ]);
                if($claimTrackerID > 0)
                {
                    Claim::where(['id' => $claimID])->update([
                        "sumInsured" => isset($request->sumInsured) ? $request->sumInsured : $oldsumInsured,
                        "excess" => isset($request->excess) ? $request->excess : $oldexcess,
                        "location" => isset($request->location) ? $request->location : $oldLocation,
                        "changed" => Config::ACTIVE
                    ]);
                }
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Congratulation!, You have successfully Updated Claim"
                );
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::NO_RECORDS_FOUND,
                    "STATUS_MESSAGE" => "Claim for this request does not exist"
                );
            }
        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to update a claim. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function filterPremia11ClaimsByDate(Request $request)
    {
        try {
                $utility = new Utility();
                $access_token = $utility->getToken();
                $defaultToDate = Carbon::now()->toDateTimeString();
                $defaultFromDate = Carbon::now()->subDays(Config::DATES_LIMIT)->toDateTimeString();
                $toDate = isset($request->toDate) ? Carbon::parse($request->toDate)->format('Y-m-d H:i:s') : $defaultToDate;
                $fromDate = isset($request->fromDate) ? Carbon::parse($request->fromDate)->format('Y-m-d H:i:s') : $defaultFromDate;
                $vehicleRegNo = $request->vehicleRegNo;
                $data = ["fromDate"=>$fromDate,"toDate" => $toDate,"vehicleRegNo"=>$vehicleRegNo];
                $response = $utility->getData($data, '/api/v1/b2b/general/claim/fetch', 'POST');
                $claim_data = json_decode($response->getBody()->getContents());
                if ($claim_data->status == 'success' && sizeof($claim_data->data->DB_VALUE1) != 0) {
                    $claims =json_decode(json_encode($claim_data->data->DB_VALUE1),true);
                }else{
                    $claims = [];
                }
        }catch (\Exception $e)
        {
            $claims = [];
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to filter claims. Error message " . $e->getMessage());
        }
        return view('adjuster.index', ['claims' => $claims]);
    }
    public function claimExceptionDetail(Request $request)
    {
        $claimID = $request->claimID;
        $claim = Claim::where(["changed"=>Config::ACTIVE])->with('claimtracker')->orderBy('dateCreated', 'DESC')->first();
        return view("adjuster.exception-report",['claim' => $claim]);
    }
    public function assessmentReport(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $assessment = Assessment::where(["id" => $assessmentID])->with("claim")->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID])->with('part')->get();
        $jobDetails = JobDetail::where(["assessmentID" => $assessmentID])->get();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured= CustomerMaster::where(["customerCode" => $customerCode])->first();
        $documents = Document::where(["assessmentID" => $assessmentID])->get();
        return view("adjuster.assessment-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents]);
    }
}
