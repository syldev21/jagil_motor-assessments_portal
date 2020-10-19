<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\AssessmentItem;
use App\CarModel;
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
use Illuminate\Support\Facades\File;
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
//        $claims = array (
//            0 =>
//                array (
//                    'CLM_NO' => 'C/109/1001/2020/000023',
//                    'CLM_POL_NO' => 'P/109/1001/2018/000016/01/02',
//                    'VEH_REG_NO' => 'KCM 945H',
//                    'VEH_MAKE' => 'I003',
//                    'VEH_MODEL' => 'I003012',
//                    'VEH_CHASSIS_NO' => 'ADMARRIJR64809178',
//                    'VEH_ENG_NO' => '4JKIRD5866',
//                    'VEH_MFG_YR' => '2017',
//                    'SUM_INSURED' => '2900000',
//                    'EXCESS_AMT' => '290000',
//                    'CLM_LOSS_DT' => '2020-10-01 00:00:00',
//                    'CLM_INTM_DT' => '2020-10-02 00:00:00',
//                    'CLAIM_TYPE' => 'Windscreen',
//                    'CUST_CODE' => '1550343',
//                    'CUST_NAME' => 'CHARLES KOGI KARANI\\CO-OP BANK LTD',
//                    'CUST_MOBILE_NO' => '0727163728',
//                    'CUST_EMAIL1' => 'muthurograce@gmail.com',
//                    'BRANCH' => 'Meru',
//                ),
//            1 =>
//                array (
//                    'CLM_NO' => 'C/101/1002/2020/002381',
//                    'CLM_POL_NO' => 'P/101/1002/2019/004498/01',
//                    'VEH_REG_NO' => 'KBQ 491P',
//                    'VEH_MAKE' => 'T009',
//                    'VEH_MODEL' => 'T009004',
//                    'VEH_CHASSIS_NO' => 'JTMZE31V90D003699',
//                    'VEH_ENG_NO' => '3ZRA427084',
//                    'VEH_MFG_YR' => '2010',
//                    'SUM_INSURED' => '1620000',
//                    'EXCESS_AMT' => '40500',
//                    'CLM_LOSS_DT' => '2020-09-02 00:00:00',
//                    'CLM_INTM_DT' => '2020-10-02 00:00:00',
//                    'CLAIM_TYPE' => 'Windscreen',
//                    'CUST_CODE' => '1515806',
//                    'CUST_NAME' => 'LOISE MWIHAKI MUNGAI',
//                    'CUST_MOBILE_NO' => '712124425',
//                    'CUST_EMAIL1' => 'mathew.kongo@gmail.com',
//                    'BRANCH' => 'Jubctr',
//                ),
//            2 =>
//                array (
//                    'CLM_NO' => 'C/101/1002/2020/002376',
//                    'CLM_POL_NO' => 'P/101/1002/2019/009751/01',
//                    'VEH_REG_NO' => 'KCD 171X',
//                    'VEH_MAKE' => 'M010',
//                    'VEH_MODEL' => 'M010037',
//                    'VEH_CHASSIS_NO' => 'WDD2040412A208896',
//                    'VEH_ENG_NO' => 'A208896',
//                    'VEH_MFG_YR' => '2008',
//                    'SUM_INSURED' => '1700000',
//                    'EXCESS_AMT' => '42500',
//                    'CLM_LOSS_DT' => '2020-09-25 00:00:00',
//                    'CLM_INTM_DT' => '2020-10-01 00:00:00',
//                    'CLAIM_TYPE' => 'Assessement',
//                    'CUST_CODE' => '1361331',
//                    'CUST_NAME' => 'SHEILA KATHAMBI MUGAMBI',
//                    'CUST_MOBILE_NO' => '0722204142',
//                    'CUST_EMAIL1' => 'kathambi2000@gmail.com',
//                    'BRANCH' => 'Jubctr',
//                ),
//            3 =>
//                array (
//                    'CLM_NO' => 'C/101/1001/2020/001079',
//                    'CLM_POL_NO' => 'P/101/1001/2019/000156/01',
//                    'VEH_REG_NO' => 'KCT 440C',
//                    'VEH_MAKE' => 'M009',
//                    'VEH_MODEL' => 'M009001',
//                    'VEH_CHASSIS_NO' => 'DE3FS-355226',
//                    'VEH_ENG_NO' => 'ZJ-899982',
//                    'VEH_MFG_YR' => '2011',
//                    'SUM_INSURED' => '550000',
//                    'EXCESS_AMT' => '0',
//                    'CLM_LOSS_DT' => '2020-09-28 00:00:00',
//                    'CLM_INTM_DT' => '2020-10-01 00:00:00',
//                    'CLAIM_TYPE' => 'Windscreen',
//                    'CUST_CODE' => 'K10010583',
//                    'CUST_NAME' => 'EVERLYN KAGENDO KANGERWE',
//                    'CUST_MOBILE_NO' => '0722915553',
//                    'CUST_EMAIL1' => 'nyaganewton89@gmail.com',
//                    'BRANCH' => 'Jubctr',
//                ),
//            4 =>
//                array (
//                    'CLM_NO' => 'C/101/1001/2020/001080',
//                    'CLM_POL_NO' => 'P/101/1001/2019/001313/01',
//                    'VEH_REG_NO' => 'KCR 265M',
//                    'VEH_MAKE' => 'T009',
//                    'VEH_MODEL' => 'T009052',
//                    'VEH_CHASSIS_NO' => 'KSP130 - 2018425',
//                    'VEH_ENG_NO' => '1KR - 1225754',
//                    'VEH_MFG_YR' => '2011',
//                    'SUM_INSURED' => '630000',
//                    'EXCESS_AMT' => '0',
//                    'CLM_LOSS_DT' => '2020-09-24 00:00:00',
//                    'CLM_INTM_DT' => '2020-10-01 00:00:00',
//                    'CLAIM_TYPE' => 'Windscreen',
//                    'CUST_CODE' => 'K10008909',
//                    'CUST_NAME' => 'LEE KIPKIRUI BETT',
//                    'CUST_MOBILE_NO' => '0726318121',
//                    'CUST_EMAIL1' => 'carolinewambu1985@gmail.com',
//                    'BRANCH' => 'Jubctr',
//                ),
//            5 =>
//                array (
//                    'CLM_NO' => 'C/104/1002/2020/000108',
//                    'CLM_POL_NO' => 'P/104/1002/2019/000143/01',
//                    'VEH_REG_NO' => 'KCR 425G',
//                    'VEH_MAKE' => 'V004',
//                    'VEH_MODEL' => 'V004001',
//                    'VEH_CHASSIS_NO' => 'KCR 425G TBA',
//                    'VEH_ENG_NO' => 'KCR 425G TBA',
//                    'VEH_MFG_YR' => '2011',
//                    'SUM_INSURED' => '1120000',
//                    'EXCESS_AMT' => '28000',
//                    'CLM_LOSS_DT' => '2020-09-01 00:00:00',
//                    'CLM_INTM_DT' => '2020-10-01 00:00:00',
//                    'CLAIM_TYPE' => 'Assessement',
//                    'CUST_CODE' => '1325330',
//                    'CUST_NAME' => 'ROSE WAMBUI MUIGAI',
//                    'CUST_MOBILE_NO' => '725542875',
//                    'CUST_EMAIL1' => 'rurwa@yahoo.co.uk',
//                    'BRANCH' => 'Mombasa',
//                ),
//            6 =>
//                array (
//                    'CLM_NO' => 'C/101/1002/2020/002382',
//                    'CLM_POL_NO' => 'B/101/1002/2018/000006/0411/01',
//                    'VEH_REG_NO' => 'KCB 681N',
//                    'VEH_MAKE' => 'N003',
//                    'VEH_MODEL' => 'N003035',
//                    'VEH_CHASSIS_NO' => 'C11-162685',
//                    'VEH_ENG_NO' => 'HR15-227439A',
//                    'VEH_MFG_YR' => '2007',
//                    'SUM_INSURED' => '760000',
//                    'EXCESS_AMT' => '19000',
//                    'CLM_LOSS_DT' => '2020-09-30 00:00:00',
//                    'CLM_INTM_DT' => '2020-10-01 00:00:00',
//                    'CLAIM_TYPE' => 'Assessement',
//                    'CUST_CODE' => '1160009',
//                    'CUST_NAME' => 'HANNAH NYAMBURA WAWERU',
//                    'CUST_MOBILE_NO' => '0721559318',
//                    'CUST_EMAIL1' => 'Hannah.Nyambura@jubileekenya.com',
//                    'BRANCH' => 'Jubctr',
//                ),
//            7 =>
//                array (
//                    'CLM_NO' => 'C/101/1001/2020/001081',
//                    'CLM_POL_NO' => 'P/101/1001/2019/000393/01',
//                    'VEH_REG_NO' => 'KCT 838H',
//                    'VEH_MAKE' => 'T009',
//                    'VEH_MODEL' => 'T009004',
//                    'VEH_CHASSIS_NO' => 'KDY231-8007875',
//                    'VEH_ENG_NO' => '1KD-2104416',
//                    'VEH_MFG_YR' => '2011',
//                    'SUM_INSURED' => '1700000',
//                    'EXCESS_AMT' => '0',
//                    'CLM_LOSS_DT' => '2020-09-30 00:00:00',
//                    'CLM_INTM_DT' => '2020-10-01 00:00:00',
//                    'CLAIM_TYPE' => 'Assessement',
//                    'CUST_CODE' => 'K10015639',
//                    'CUST_NAME' => 'SAMUEL NJOROGE NJOGU',
//                    'CUST_MOBILE_NO' => '0706573969',
//                    'CUST_EMAIL1' => 'sairichie45@gmail.com',
//                    'BRANCH' => 'Jubctr',
//                ),
//            8 =>
//                array (
//                    'CLM_NO' => 'C/101/1002/2020/002383',
//                    'CLM_POL_NO' => 'P/101/1002/2019/000560/01',
//                    'VEH_REG_NO' => 'KCK 481L',
//                    'VEH_MAKE' => 'T009',
//                    'VEH_MODEL' => 'T009076',
//                    'VEH_CHASSIS_NO' => 'NZT260-3052856',
//                    'VEH_ENG_NO' => 'INZ-D526579',
//                    'VEH_MFG_YR' => '2009',
//                    'SUM_INSURED' => '1100000',
//                    'EXCESS_AMT' => '27500',
//                    'CLM_LOSS_DT' => '2020-09-26 00:00:00',
//                    'CLM_INTM_DT' => '2020-10-02 00:00:00',
//                    'CLAIM_TYPE' => 'Assessement',
//                    'CUST_CODE' => '1489146',
//                    'CUST_NAME' => 'CHRISTINE NADZUA MWANGOLO',
//                    'CUST_MOBILE_NO' => '727692714',
//                    'CUST_EMAIL1' => NULL,
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
        $carDetails = CarModel::where(["modelCode" => $claim['VEH_MODEL']])->first();
        return view('adjuster.claim-form', ['claim' => $claim,'locations' =>$locations,'carDetails'=>$carDetails]);
    }
    public function claimDetails(Request $request,$claimID)
    {
        $claim = Claim::where(["id"=>$claimID])->with('customer')->with('assessment')->with('documents')->first();

        $carDetails = CarModel::where(["modelCode" => isset($claim->carModelCode) ? $claim->carModelCode : 0])->first();

        $assessments =Assessment::where(['claimID' => $claim->id])->with('user')->get();
        return view('adjuster.claim-details', ['claim' => $claim,"assessments" =>$assessments,"carDetails"=>$carDetails]);
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
            $carMakeCode = $request->carMakeCode;
            $carModelCode =$request->carModelCode;
            $yom = $request->yom;
            $engineNumber = $request->engineNumber;
            $chassisNumber = $request->chassisNumber;

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
                        "email" => $email,
                        "createdBy" => Auth::id(),
                        "dateCreated" => $curDate
                    ]);
                    $claimID = 0;
                    if ($customerID > 0) {
                        $claimID = Claim::insertGetId([
                            "claimNo" => $claimNo,
                            "policyNo" => $policyNo,
                            "branch" => $branch,
                            "vehicleRegNo" => $vehicleRegNo,
                            "carMakeCode" => $carMakeCode,
                            "carModelCode" => $carModelCode,
                            "engineNumber" => $engineNumber,
                            "chassisNumber" => $chassisNumber,
                            "yom" => $yom,
                            "customerCode" => $customerCode,
                            "claimType" => $claimType,
                            "sumInsured" => $sumInsured,
                            "excess" => $excess,
                            "claimStatusID" => Config::$STATUSES['CLAIM']['UPLOADED']['id'],
                            "intimationDate" => $intimationDate,
                            "loseDate" => $loseDate,
                            "location" => $location,
                            "createdBy" => Auth::id(),
                            "dateCreated" => $curDate
                        ]);
                        StatusTracker::create([
                            "claimID" =>$claimID,
                            "newStatus"=> Config::$STATUSES['CLAIM']['UPLOADED']['id'],
                            "oldStatus" => Config::DEFAULT_STATUS,
                            "statusType" => Config::$STATUS_TYPES["CLAIM"],
                            "createdBy" => Auth::id(),
                            "dateCreated" => $curDate
                        ]);
                        if($originalExcess != $excess || $originalSumInsured != $sumInsured)
                        {
                            $createdBy = Auth::id();
                            $claimTrackerID =ClaimTracker::insertGetId([
                                'claimID' => $claimID,
                                'claimNo' => $claimNo,
                                'policyNo' => $policyNo,
                                'excess' =>    $originalExcess,
                                'sumInsured' => $originalSumInsured,
                                'location' => $location,
                                'createdBy' => $createdBy,
                                'dateCreated' => $curDate
                            ]);
                            if($claimTrackerID > 0)
                            {
                                Claim::where(['id' => $claimID])->update([
                                    "changed" => Config::ACTIVE,
                                    "updatedBy"=> Auth::id(),
                                    "dateModified" => $curDate
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
                        "carMakeCode" => $carMakeCode,
                        "carModelCode" => $carModelCode,
                        "engineNumber" => $engineNumber,
                        "chassisNumber" => $chassisNumber,
                        "yom" => $yom,
                        "customerCode" => $customerCode,
                        "claimType" => $claimType,
                        "sumInsured" => $sumInsured,
                        "excess" => $excess,
                        "claimStatusID" => Config::$STATUSES['CLAIM']['UPLOADED']['id'],
                        "intimationDate" => $intimationDate,
                        "loseDate" => $loseDate,
                        "location" => $location,
                        "createdBy" => Auth::id(),
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
    public function fetchAllAssessments(Request $request)
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
    public function assessments(Request $request)
    {
        $assessmentStatusID = $request->assessmentStatusID;
        try {
            $assessments = Assessment::where('assessmentStatusID','=',$assessmentStatusID)->with('claim')->with('user')->with('approver')->with('assessor')->get();
            return view('adjuster.assessments',['assessments' => $assessments,'assessmentStatusID'=>$assessmentStatusID]);
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
        $claim = Claim::where(["id"=>$claimID])->with('customer')->with('documents')->first();
        $carDetails = CarModel::where(["modelCode" => isset($claim->carModelCode) ? $claim->carModelCode : 0])->first();
        return view("adjuster.edit-claim-form",['claim' => $claim,'carDetails' =>$carDetails]);
    }

    public function updateClaim(Request $request)
    {
        try {
            $claimID = $request->claimID;
            $totalImages = $request->totalImages;
            $claim = Claim::where(['id' => $claimID])->first();
            if ($claim->id > 0) {
                $oldexcess = $claim->excess;
                $oldsumInsured = $claim->sumInsured;
                $oldLocation = $claim->location;
                $claimNo = $claim->claimNo;
                $policyNo = $claim->policyNo;
                $createdBy = Auth::id();
                if ($oldexcess != $request->excess || $oldsumInsured != $request->sumInsured) {
                    $claimTrackerID = ClaimTracker::insertGetId([
                        'claimID' => $claimID,
                        'claimNo' => $claimNo,
                        'policyNo' => $policyNo,
                        'excess' => $oldexcess,
                        'sumInsured' => $oldsumInsured,
                        'location' => $oldLocation,
                        'createdBy' => $createdBy,
                        'dateCreated' => $this->functions->curlDate()
                    ]);
                    if ($claimTrackerID > 0) {
                        $claimResult = Claim::where(['id' => $claimID])->update([
                            "sumInsured" => isset($request->sumInsured) ? $request->sumInsured : $oldsumInsured,
                            "excess" => isset($request->excess) ? $request->excess : $oldexcess,
                            "location" => isset($request->location) ? $request->location : $oldLocation,
                            "changed" => Config::ACTIVE
                        ]);
                    }
                }
                $documents = Document::where(["claimID" => $claim->id])->get();
                if (count($documents) > 0) {
                    $affectedDocumentRows = Document::where(["claimID" => $claim->id])->delete();
                    if ($affectedDocumentRows > 0) {
                        foreach ($documents as $document) {
                            $image_path = "documents/" . $document->name;  // Value is not URL but directory file path
                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }
                        }
                    }
                }
                for ($x = 0; $x < $totalImages; $x++) {
                    if ($request->hasFile('images' . $x)) {
                        $file = $request->file('images' . $x);
                        $filename = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $path = $file->getRealPath();
                        $size = $file->getSize();
                        $picture = date('His') . '-' . $filename;
                        //Save files in below folder path, that will make in public folder
                        $file->move(public_path('documents/'), $picture);
                        $documents = Document::create([
                            "claimID" => $claimID,
                            "name" => $picture,
                            "mime" => $extension,
                            "size" => $size,
                            "documentType" => Config::$DOCUMENT_TYPES["IMAGE"]["ID"],
                            "url" => $path,
                            "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"],
                            "createdBy" => Auth::id(),
                            "dateCreated" => $this->functions->curlDate()
                        ]);
                    }
                }
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Congratulation!, You have successfully Updated Claim"
                );
            } else {
                $response = array(
                    "STATUS_CODE" => Config::NO_RECORDS_FOUND,
                    "STATUS_MESSAGE" => "Claim for this request does not exist"
                );
            }
        } catch (\Exception $e) {
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
