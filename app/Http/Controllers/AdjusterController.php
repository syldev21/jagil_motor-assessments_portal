<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\AssessmentItem;
use App\CarModel;
use App\Claim;
use App\ClaimTracker;
use App\CourtesyCar;
use App\Document;
use App\Helper\SMSHelper;
use App\JobDetail;
use App\Notifications\ClaimApproved;
use App\Notifications\NewClaimNotification;
use App\PriceChange;
use App\ReInspection;
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
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
        $data = ["fromDate" => $fromDate, "toDate" => $toDate];
        $response = $utility->getData($data, '/api/v1/b2b/general/claim/fetch', 'POST');
        $claim_data = json_decode($response->getBody()->getContents());
        if ($claim_data->status == 'success' && sizeof($claim_data->data->DB_VALUE1) != 0) {
            $claims = json_decode(json_encode($claim_data->data->DB_VALUE1), true);
        } else {
            $claims = [];
        }

        return view('adjuster.index', ['claims' => $claims]);
    }

    public function claimForm(Request $request)
    {
        $claim = json_decode($request->getContent(), true);
        $garages = Garage::all();

        $carDetails = CarModel::where(['makeCode' => isset($claim['VEH_MAKE']) ? $claim['VEH_MAKE'] : '', 'modelCode' => isset($claim['VEH_MODEL']) ? $claim['VEH_MODEL'] : ''])->first();
        return view('adjuster.claim-form', ['claim' => $claim, 'garages' => $garages, 'carDetails' => $carDetails]);
    }

    public function claimDetails(Request $request, $claimID)
    {
        $claim = Claim::where(["id" => $claimID])->with('customer')->with('assessment')->with('documents')->first();

        $carDetails = CarModel::where(["modelCode" => isset($claim->carModelCode) ? $claim->carModelCode : 0])->first();

        $assessment = Assessment::where(['claimID' => $claim->id])->with('assessor')->first();

        return view('adjuster.claim-details', ['claim' => $claim, "assessment" => $assessment, "carDetails" => $carDetails]);
    }

    public function addClaim(Request $request)
    {
        try {
            $claimNo = $request->claimNo;
            $claimArray = explode('/',$claimNo);
            $subClassCode =$claimArray[2];
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
            $garageID = $request->garageID;
            $originalExcess = $request->originalExcess;
            $originalSumInsured = $request->originalSumInsured;
            $carMakeCode = $request->carMakeCode;
            $carModelCode = $request->carModelCode;
            $yom = $request->yom;
            $engineNumber = $request->engineNumber;
            $chassisNumber = $request->chassisNumber;

            $claims = Claim::where(['claimNo' => $claimNo])
                ->limit(1)->get();
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
                            "subClassCode"=>$subClassCode,
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
                            "garageID" => $garageID,
                            "active" =>Config::ACTIVE,
                            "createdBy" => Auth::id(),
                            "dateCreated" => $curDate
                        ]);
                        StatusTracker::create([
                            "claimID" => $claimID,
                            "newStatus" => Config::$STATUSES['CLAIM']['UPLOADED']['id'],
                            "oldStatus" => Config::DEFAULT_STATUS,
                            "statusType" => Config::$STATUS_TYPES["CLAIM"],
                            "createdBy" => Auth::id(),
                            "dateCreated" => $curDate
                        ]);
                        if ($originalExcess != $excess || $originalSumInsured != $sumInsured) {
                            $createdBy = Auth::id();
                            $claimTrackerID = ClaimTracker::insertGetId([
                                'claimID' => $claimID,
                                'claimNo' => $claimNo,
                                'policyNo' => $policyNo,
                                'excess' => $originalExcess,
                                'sumInsured' => $originalSumInsured,
                                'garageID' => $garageID,
                                'createdBy' => $createdBy,
                                'dateCreated' => $curDate
                            ]);
                            if ($claimTrackerID > 0) {
                                Claim::where(['id' => $claimID])->update([
                                    "changed" => Config::ACTIVE,
                                    "updatedBy" => Auth::id(),
                                    "dateModified" => $curDate
                                ]);
                            }
                        }
                        $utility = new Utility();
                        $access_token = $utility->getToken();
                        $data = ["claimNo" => $claimNo];
                        $response = $utility->getData($data, '/api/v1/b2b/general/claim/uploaded', 'POST');
                        $claim_data = json_decode($response->getBody()->getContents());
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
                        "subClassCode" =>$subClassCode,
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
                        "garageID" => $garageID,
                        "active" => Config::ACTIVE,
                        "createdBy" => Auth::id(),
                        "dateCreated" => $curDate
                    ]);
                    $utility = new Utility();
                    $access_token = $utility->getToken();
                    $data = ["claimNo" => $claimNo];
                    $response = $utility->getData($data, '/api/v1/b2b/general/claim/uploaded', 'POST');
                    $claim_data = json_decode($response->getBody()->getContents());
                    $response = array(
                        "STATUS_CODE" => Config::SUCCESS_CODE,
                        "STATUS_MESSAGE" => "Congratulation!, You have successfully created a claim on the portal"
                    );
                }
                if ($claimID > 0) {
                    $headAssessors = User::role('Head Assessor')->get(); // Returns only users with the role 'Head Assessor'
                    $claim = Claim::where(['id' => $claimID])->with('customer')->first();
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
                            $emailMessage = "Hello " . $data['headAssessor'] . ", <br> <br>

                            A new claim " . $data['claim'] . " has been created. You are required to assign an assessor. <br>

                            <strong>Vehicle Registration</strong>: " . $data['reg'] . "  <br>

                            <strong>Created by</strong>:  <br><br>



                            Claims Department, <br>

                            Jubilee Insurance.
                        ";
                            $smsMessage = 'Hello ' . $headAssessor->firstName . ', A new claim : ' . $claimNo . ' has been created. You are required to assign an assessor';
                            $email = [
                                'subject' => $claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
                                'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'to' => $email_add,
                                'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                                'html' => $emailMessage,
                            ];
                            $logData = array(
                                "vehicleRegNo" => $vehicleRegNo,
                                "claimNo" => $claimNo,
                                "policyNo" => $policyNo,
                                "userID" => Auth::user()->id,
                                "role" => Config::$ROLES['HEAD-ASSESSOR'],
                                "activity" => Config::ACTIVITIES['CLAIM_UPLOAD'],
                                "notification" => $emailMessage,
                                "notificationTo" => $email_add,
                                "notificationType" => Config::NOTIFICATION_TYPES['EMAIL'],
                            );
                            $this->functions->logActivity($logData);
                            $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                            $logData['notificationType'] = Config::NOTIFICATION_TYPES['SMS'];
                            $logData['notification'] = $smsMessage;
                            $logData["notificationTo"] = $headAssessor->MSISDN;
                            $this->functions->logActivity($logData);
                            SMSHelper::sendSMS($smsMessage, $headAssessor->MSISDN);
                            Notification::send($headAssessors, new NewClaimNotification($claim));
                        }
                        $MSISDN = isset($claim->customer->MSISDN) ? $claim->customer->MSISDN : '';
                        $customerFullName = isset($claim->customer->fullName) ? $claim->customer->fullName : 'customer';
                        $smsMessage = $smsMessage = 'Dear ' . $customerFullName . ', Your claim for vehicle regNumber : ' . $data['reg'] . ' has been initiated for processing. You will be notified for further stages';
                        SMSHelper::sendSMS($smsMessage,$MSISDN);
                        $logData['notificationType'] = Config::NOTIFICATION_TYPES['SMS'];
                        $logData['notification'] = $smsMessage;
                        $logData['role'] = 'Customer';
                        $logData["notificationTo"] = $MSISDN;
                        $this->functions->logActivity($logData);
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

    public function uploadDocumentsForm(Request $request, $claimID)
    {
        $claim = Claim::where(['id' => $claimID])->first();
        return view('adjuster.file-upload', ['claim' => $claim]);
    }

    public function searchClaim(Request $request)
    {
        $userID = 3;
        if (isset($request->fromDate) && isset($request->toDate) && !isset($request->vehicleRegNo)) {
            $response = $this->functions->search($userID, $request->fromDate, $request->toDate, '');

        } else if (isset($request->vehicleRegNo)) {
            $response = $this->functions->search($userID, '', '', $request->vehicleRegNo);
        }
        return $response;
    }

    public function assessments(Request $request)
    {
        $assessmentStatusID = $request->assessmentStatusID;
        try {
            if (!isset($request->fromDate) && !isset($request->toDate) && !isset($request->regNumber)) {
                if($assessmentStatusID == Config::$STATUSES['ASSESSMENT']['APPROVED']['id'])
                {
                    $assessments = Assessment::where('assessmentStatusID', '=', $assessmentStatusID)
                        ->where('active','=',Config::ACTIVE)
                        ->where('isTheft','=',Config::INACTIVE)
                        ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                        ->where('finalApprovedAt', ">=", Carbon::now()->subDays(Config::DATE_RANGE))
                        ->orderBy('finalApprovedAt', 'DESC')->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
                }else
                {
                    $assessments = Assessment::where('assessmentStatusID', '=', $assessmentStatusID)
                        ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                        ->where('active','=',Config::ACTIVE)
                        ->where('isTheft','=',Config::INACTIVE)
                        ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
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
//                $claimids = Claim::where('vehicleRegNo', 'like', '%' . $request->regNumber . '%')->pluck('id')->toArray();
                $assessments = Assessment::where('assessmentStatusID', '=', $assessmentStatusID)
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereIn('claimID', $claimids)
                    ->where('active','=',Config::ACTIVE)
                    ->where('isTheft','=',Config::INACTIVE)
                    ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();

            } elseif (isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber)) {
                $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
                $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
                $assessments = Assessment::where('assessmentStatusID', '=', $assessmentStatusID)
                    ->where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
                    ->whereBetween('dateCreated', [$fromDate, $toDate])
                    ->where('active','=',Config::ACTIVE)
                    ->where('isTheft','=',Config::INACTIVE)
                    ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->with('supplementaries')->get();
            } else {
                $assessments = array();
            }
            return view('adjuster.assessments', ['assessments' => $assessments, 'assessmentStatusID' => $assessmentStatusID]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }

    public function assessmentDetails(Request $request, $assessmentID)
    {
        try {
            $assessment = Assessment::where('id', '=', $assessmentID)->with('claim')->with('assessor')->first();
            $customer = CustomerMaster::where(['customerCode' => $assessment->claim->customerCode])->first();
            return view('adjuster.assessment-details', ['assessment' => $assessment, 'customer' => $customer]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessment details. Error message " . $e->getMessage());
        }
    }

    public function editClaimForm(Request $request, $claimID)
    {
        $claim = Claim::where(["id" => $claimID])->with('customer')->with('documents')->first();
        $carDetails = CarModel::where(["modelCode" => isset($claim->carModelCode) ? $claim->carModelCode : 0])->first();
        $garages = Garage::all();
        return view("adjuster.edit-claim-form", ['claim' => $claim, 'carDetails' => $carDetails, 'garages' => $garages]);
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
                $oldGarage = $claim->garageID;
                $claimNo = $claim->claimNo;
                $policyNo = $claim->policyNo;
                $createdBy = Auth::id();
                if ($oldexcess != $request->excess || $oldsumInsured != $request->sumInsured || $oldGarage != $request->garageID) {
                    $claimTrackerID = ClaimTracker::insertGetId([
                        'claimID' => $claimID,
                        'claimNo' => $claimNo,
                        'policyNo' => $policyNo,
                        'excess' => $oldexcess,
                        'sumInsured' => $oldsumInsured,
                        'garageID' => $oldGarage,
                        'createdBy' => $createdBy,
                        'dateCreated' => $this->functions->curlDate()
                    ]);
                    if ($claimTrackerID > 0) {
                        $claimResult = Claim::where(['id' => $claimID])->update([
                            "sumInsured" => isset($request->sumInsured) ? $request->sumInsured : $oldsumInsured,
                            "excess" => isset($request->excess) ? $request->excess : $oldexcess,
                            "garageID" => isset($request->garageID) ? $request->garageID : $oldGarage,
                            "changed" => Config::ACTIVE
                        ]);
                    }
                }
                $documents = Document::where(["claimID" => $claim->id])->get();
                if (isset($totalImages)) {
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
            $vehicleRegNo = $request->vehicleRegNo;
            if (isset($vehicleRegNo)) {
                $defaultFromDate = Carbon::now()->subDays(366)->toDateTimeString();
            }
            $toDate = isset($request->toDate) ? Carbon::parse($request->toDate)->format('Y-m-d H:i:s') : $defaultToDate;
            $fromDate = isset($request->fromDate) ? Carbon::parse($request->fromDate)->format('Y-m-d H:i:s') : $defaultFromDate;
            $data = ["fromDate" => $fromDate, "toDate" => $toDate, "vehicleRegNo" => $vehicleRegNo];
            $response = $utility->getData($data, '/api/v1/b2b/general/claim/fetch', 'POST');
            $claim_data = json_decode($response->getBody()->getContents());
            if ($claim_data->status == 'success' && sizeof($claim_data->data->DB_VALUE1) != 0) {
                $claims = json_decode(json_encode($claim_data->data->DB_VALUE1), true);
            } else {
                $claims = [];
            }
        } catch (\Exception $e) {
            $claims = [];
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to filter claims. Error message " . $e->getMessage());
        }
        return view('adjuster.index', ['claims' => $claims]);
    }

    public function claimExceptionDetail(Request $request)
    {
        $claimID = $request->claimID;
        $claim = Claim::where(["changed" => Config::ACTIVE])->with('claimtracker')->orderBy('dateCreated', 'DESC')->first();
        return view("adjuster.exception-report", ['claim' => $claim]);
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
        return view("adjuster.assessment-report", ['assessment' => $assessment, "assessmentItems" => $assessmentItems, "jobDetails" => $jobDetails, "insured" => $insured, 'documents' => $documents, 'adjuster' => $adjuster, 'assessor' => $assessor, 'aproved' => $aproved, 'carDetail' => $carDetail, 'priceChange' => $priceChange]);
    }

    public function claims(Request $request)
    {
        $claimStatusID = $request->claimStatusID;
        $assessors = User::role('Assessor')->get();
        if (!isset($request->fromDate) && !isset($request->toDate) && !isset($request->regNumber)) {
            if($claimStatusID == Config::$STATUSES['CLAIM']['UPLOADED']['id'])
            {
                $claims = Claim::where([
                    'claimStatusID'=> $claimStatusID,
                    'active'=>Config::ACTIVE,
                    'claimType'=> Config::CLAIM_TYPES['ASSESSMENT']
                ])->with('adjuster')->get();
            }else
            {
                $claims = Claim::where([
                    'claimStatusID'=> $claimStatusID,
                    'active'=>Config::ACTIVE,
                    'claimType'=> Config::CLAIM_TYPES['ASSESSMENT']
                ])  ->where('dateCreated', ">=", Carbon::now()->subDays(Config::DATE_RANGE))
                    ->with('adjuster')->get();
            }
        } elseif (isset($request->regNumber)) {
//                $regNo = preg_replace("/\s+/", "", $request->regNumber);
            $registrationNumber=preg_replace("/\s+/", "", $request->regNumber);
            $regNoArray = preg_split('/(?=\d)/', $registrationNumber, 2);
            $regNo1 =isset($regNoArray[0]) ? $regNoArray[0] : '';
            $regNo2 = isset($regNoArray[1]) ? $regNoArray[1] : '';
            $regNo = $request->regNumber;
            $claims = Claim::where(function($a) use ($regNo,$regNo1,$regNo2,$claimStatusID) {
                $a->where('vehicleRegNo','like', '%'.$regNo.'%')
                    ->where('claimStatusID','=',$claimStatusID)
                    ->where('claimType','=',Config::CLAIM_TYPES['ASSESSMENT'])
                    ->where('active','=',Config::ACTIVE);
            })->orWhere(function($a)use ($regNo1,$regNo2,$claimStatusID) {
                $a->where('vehicleRegNo','like', '%'.$regNo1.'%')
                    ->where('vehicleRegNo','like', '%'.$regNo2.'%')
                    ->where('claimStatusID','=',$claimStatusID)
                    ->where('claimType','=',Config::CLAIM_TYPES['ASSESSMENT'])
                    ->where('active','=',Config::ACTIVE);
            })->with('adjuster')
                ->get();

        } elseif (isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber)) {
            $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
            $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
            $claims = Claim::where([
                'claimStatusID' => $claimStatusID,
                'active' => Config::ACTIVE,
                'claimType' => Config::CLAIM_TYPES['ASSESSMENT']
            ])->whereBetween('dateCreated', [$fromDate, $toDate])
                ->with('adjuster')->get();
        } else {
            $claims = array();
        }
//        $claims = Claim::where(['claimStatusID'=> $claimStatusID,'active'=>Config::ACTIVE,'claimType'=> Config::CLAIM_TYPES['ASSESSMENT']])->with('adjuster')->get();
        return view('adjuster.claims', ['claims' => $claims, 'assessors' => $assessors, 'claimStatusID' => $claimStatusID]);
    }
    public function fetchClaimTypes(Request $request)
    {

        $assessors = User::role('Assessor')->get();
        $claimType =$request->claimType;
//        $claims = Claim::where(['claimType'=> $claimType,'active'=>Config::ACTIVE])->with('adjuster')->get();
        if (!isset($request->fromDate) && !isset($request->toDate) && !isset($request->regNumber)) {
            $claims = Claim::where(
                'dateCreated', ">=", Carbon::now()->subDays(Config::DATE_RANGE))
                ->where('active','=',Config::ACTIVE)
                ->where('claimType','=',$claimType)
                ->with('adjuster')->orderBy('dateCreated', 'DESC')->get();
        } elseif (isset($request->regNumber)) {
            $registrationNumber = preg_replace("/\s+/", "", $request->regNumber);
            $regNoArray = preg_split('/(?=\d)/', $registrationNumber, 2);
            $regNo1 = isset($regNoArray[0]) ? $regNoArray[0] : '';
            $regNo2 = isset($regNoArray[1]) ? $regNoArray[1] : '';
            $regNo = $request->regNumber;
            $claims = Claim::where(function ($a) use ($regNo, $regNo1, $regNo2,$claimType) {
                $a->where('vehicleRegNo', 'like', '%' . $regNo . '%')
                    ->where('claimType', '=',$claimType);
            })->orWhere(function ($a) use ($regNo1, $regNo2,$claimType) {
                $a->where('vehicleRegNo', 'like', '%' . $regNo1 . '%')->where('vehicleRegNo', 'like', '%' . $regNo2 . '%')
                    ->where('claimType', '=',$claimType);
            })
                ->with('adjuster')->orderBy('dateCreated', 'DESC')->get();

        } elseif (isset($request->fromDate) && isset($request->toDate) && !isset($request->regNumber)) {
            $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d H:i:s');
            $toDate = Carbon::parse($request->toDate)->format('Y-m-d H:i:s');
            $claims = Claim::whereBetween('dateCreated', [$fromDate, $toDate])
                ->where('active','=',Config::ACTIVE)
                ->where('claimType','=',$claimType)
                ->with('adjuster')->orderBy('dateCreated', 'DESC')->get();
        }
        if($request->claimType == Config::CLAIM_TYPES['WINDSCREEN'])
        {
            $view = 'windscreen-claims';

        }else{
            $view = 'claim-types';
        }
        return view('adjuster.'.$view, ['claims' => $claims, 'assessors' => $assessors,'claimType'=>$claimType]);
    }

    //Generate release letter
//    public function generateReleaseLetter($claim_id, $download=true) {
//        $claim = Claim::where(['id'=>$claim_id])->with('customer')->first();
//
//        $role = Config::$ROLES['ADJUSTER'];
//
//        $pdf_html = view('adjuster.release-letter', compact('claim', 'role'))->render();
//        $pdf_name = str_replace('/', '', $claim->claim_no).' - '.$claim->vehicle_reg.'.pdf';
//        $pdf_path = public_path().'/release-letters';
//        $pdf_url = url('/windscreen-repairs/'.$pdf_name);
//
//        if(!is_dir($pdf_path)){
//            //Directory does not exist, so lets create it.
//            mkdir($pdf_path, 0755);
//
//            $pdf_path = $pdf_path.'/'.$pdf_name;
//        }
//
//        $pdf = app()->make('dompdf.wrapper');
//        $pdf->getDomPDF()->set_option('enable_html5_parser', true);
//        $pdf->loadHTML($pdf_html);
//
//        if($download) {
//            return $pdf->stream($pdf_name, array("Attachment" => false));
//        } else {
//
//            $pdf->save($pdf_path);
//
//            return public_path().'/windscreen-repairs/'.$pdf_name;
//        }
//    }
//Generate release letter
    public function generateReleaseLetter($claim_id, $download = true)
    {
        $claim = Claim::where(['id' => $claim_id])->with('customer')->first();
        $claimID = $claim_id;

        $role = Config::$ROLES['ADJUSTER'];

        return view('adjuster.release-letter', compact('claim', 'role', 'claimID'));
    }

    public function reInspectionLetter(Request $request, $id)
    {
        $assessment = Assessment::findOrFail($id);
        $assessmentIds = Assessment::where(['assessmentID' => $id, 'assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['APPROVED']])->pluck('id')->toArray();
        array_push($assessmentIds, $assessment->id);

        $claimExists = Claim::where('id', $assessment->claimID)->exists();
        $scrapValue = isset($assessment->scrapValue) ? $assessment['scrapValue'] : 0;
        $priceChange = PriceChange::where('assessmentID', $id)->first();
        if ($claimExists) {
            $claim = Claim::where('id', $assessment->claimID)->first();
            $reinspection = ReInspection::where('assessmentID', $id)->first();

            $award = AssessmentItem::whereIn("assessmentID", $assessmentIds)
                ->where('reInspectionType','=', Config::$JOB_CATEGORIES['CIL']['ID'])
                ->where('reInspection', Config::ACTIVE)
                ->sum('total');
            if(isset($priceChange->finalApprovedAt)) {
                $priceChangeDiff = AssessmentItem::whereIn("assessmentID", $assessmentIds)
                    ->where('reInspectionType','=', Config::$JOB_CATEGORIES['CIL']['ID'])
                    ->where('reInspection', Config::ACTIVE)
                    ->sum('totalDifference');
                $award = $award+$priceChangeDiff;
            }

            $status = $assessment->assessmentTypeID;

            $labor = $reinspection->labor;

            if ($status == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                $subAmount = (Config::MARK_UP * $award) + $labor;
            } elseif ($status == Config::ASSESSMENT_TYPES['CASH_IN_LIEU']) {
                $subAmount = (Config::MARK_UP * $award) + $labor;
            }

            $unReInspectedParts = AssessmentItem::whereIn("assessmentID", $assessmentIds)
                ->where('reInspectionType', '=', Config::$JOB_CATEGORIES['CIL']['ID'])
                ->where('reInspection', Config::ACTIVE)
//                ->where('total', '!=', 0)
                ->get();
            $assessor = User::where('id', $reinspection->createdBy)->first();
            $insured = CustomerMaster::where(['customerCode' => $claim->customerCode])->first();
            $insuredName = isset($insured->firstName) ? $insured->firstName : '' . isset($insured->lastName) ? $insured->lastName : '';
            $assessorName = isset($assessor->name) ? $assessor->name : '';

            $priceChangeAssessmentIds = PriceChange::whereIn('assessmentID',$assessmentIds)
                ->whereNotNull(['finalApprovedAt'])
                ->pluck('assessmentID')->toArray();
            $difference = AssessmentItem::where(['reInspectionType'=>Config::$JOB_CATEGORIES['REPLACE']['ID'],'reInspection'=>Config::ACTIVE])
                ->whereIn('assessmentID', $priceChangeAssessmentIds)
                ->whereNotNull('current')
                ->sum('totalDifference');
            if ($assessment['assessmentTypeID'] == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                if($assessment['claim']->intimationDate >= Config::VAT_REDUCTION_DATE && $assessment['claim']->intimationDate <= Config::VAT_END_DATE)
                {
                    $difference = ((Config::CURRENT_TOTAL_PERCENTAGE) / Config::INITIAL_PERCENTAGE * $difference);
                }else
                {
                    $difference = ((Config::TOTAL_PERCENTAGE) / Config::INITIAL_PERCENTAGE * $difference);
                }
            } else {
                $difference = (Config::NEW_MARKUP * $difference);
            }
            $amount = $reinspection->total+$difference;
//            if(isset($assessment->totalChange) && isset($priceChange->finalApprovedAt))
//            {
//                if($assessment->assessmentTypeID= Config::ASSESSMENT_TYPES['CASH_IN_LIEU'])
//                {
//                    $amount = $assessment->totalChange - $scrapValue;
//                }else
//                {
//                    $amount = $assessment->totalChange;
//                }
//            }else
//            {
//                $amount = $reinspection->total;
//            }
            $data = [
                'assessor' => $assessorName,
                'amount' => $amount,
                'vehicleRegNo' => $claim->vehicleRegNo,
                'assessmentDate' => $assessment->dateCreated,
                'day' => $reinspection->dateCreated,
                'insured' => $insuredName,
                'claim' => $claim->claimNo,
                'subAmount' => isset($subAmount) ? $subAmount : 0,
                'parts' => $unReInspectedParts,
                'labor' => $reinspection->labor,
                'addLabor' => $reinspection->add_labor,
                'intimationDate' => $claim->intimationDate,
                'priceChange' => $priceChange
            ];
        } else {
            $data = [
                'assessor' => null,
                'amount' => 0,
                'vehicleRegNo' => null,
                'assessmentDate' => '00:00:00',
                'day' => '00:00:00',
                'insured' => null,
                'claim' => null,
                'subAmount' => 0,
                'parts' => [],
                'labor' => 0,
                'addLabor' => 0,
                'intimationDate' => '00:00:00',
                'priceChange' => []
            ];
        }

        return view('common.letter', $data);
    }

    public function supplementaries(Request $request)
    {
        $assessmentStatusID = $request->assessmentStatusID;
        try {
            $assessments = Assessment::where(['assessmentStatusID' => $assessmentStatusID, 'segment' => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],'active'=>Config::ACTIVE])
                ->with('claim')->with('user')->with('approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
            return view('adjuster.supplementaries', ['assessments' => $assessments, 'assessmentStatusID' => $assessmentStatusID, 'assessmentStatusID' => $assessmentStatusID]);
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
        return view("adjuster.supplementary-report", ['assessment' => $assessment, "assessmentItems" => $assessmentItems, "jobDetails" => $jobDetails, "insured" => $insured, 'documents' => $documents, 'adjuster' => $adjuster, 'assessor' => $assessor,'carDetail'=>$carDetail]);
    }

    public function SendRepairAuthority(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $email = $request->email;

        $role = Config::$ROLES['ADJUSTER'];
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
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('adjuster.send-repair-authority', compact('assessment', "assessmentItems", "jobDetails", "insured", 'documents', 'adjuster', 'assessor', 'aproved', 'carDetail', 'priceChange'));

        $pdfFilePath = public_path('images/assessment-report.pdf');

        if (File::exists($pdfFilePath)) {
            File::delete($pdfFilePath);
        }
        $pdf->save($pdfFilePath);
        //     return $pdf->stream();
        //     // return view("assessment-manager.assessment-report", ['assessment' => $assessment, "assessmentItems" => $assessmentItems, "jobDetails" => $jobDetails, "insured" => $insured, 'documents' => $documents, 'adjuster' => $adjuster, 'assessor' => $assessor, 'aproved' => $aproved, 'carDetail' => $carDetail, 'priceChange' => $priceChange]);

//        $userDetails = array(
//            array(
//                "id" => $claim->garage->id,
//                "name" => $claim->garage->name,
//                "email" => $claim->garage->email
//            )
//
//        );

        $flag = false;

        $message = [
            'subject' => "PROCEED TO REPAIR - ".$assessment['claim']['claimNo']."_".$assessment['claim']['vehicleRegNo'],
            'from' => Config::JUBILEE_NO_REPLY_EMAIL,
            'to' => $email,
            'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
            'attachment' => $pdfFilePath,
            'cc' => Auth::user()->email,
            'html' => "
                        Dear Sirs, <br>

                        Kindly proceed with repairs as per attached and adhere to REPAIR TIMELINES <br>

                        Note: No supplmentaries will be allowed or price changes after repair commencement. <br> <br>
                        Kindly adhere to above terms.



                        Regards, <br><br>

                        " . $role . ", <br>

                        Claims Department, <br>

                        Jubilee Allianz Insurance Company
                    ",
        ];

        InfobipEmailHelper::sendEmail($message, $email);
        // SMSHelper::sendSMS('Dear Sir, kindly proceed with repairs as per attached on the email', $userDetail['MSISDN']);
//            $user = User::where(["id" => $userDetail['id']])->first();
//            Notification::send($user, new ClaimApproved($claim));

        $flag = true;
        if ($flag)
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
    public function emailReleaseletter(Request $request)
    {
        $claim_id = $request->claimID;
        $claim = Claim::where(['id' => $claim_id])->with('customer')->first();
        $email = $request->email;
        $role = Config::$ROLES['ADJUSTER'];
        $assessment = Assessment::where(["id" => $claim_id])->first();

        $adjusterID = $claim->createdBy;
        $adjuster = User::where(["id" => $adjusterID])->first();
        $assessorID = $assessment->assessedBy;
        $assessor = User::where(["id" => $assessorID])->first();

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('adjuster.release', compact('claim', 'role'));

        $pdfFilePath = public_path('images/release-letter.pdf');

        if (File::exists($pdfFilePath)) {
            File::delete($pdfFilePath);
        }
        $pdf->save($pdfFilePath);
        // return $pdf->stream();

        $flag = false;
        $message = [
            'subject' => "RELEASE LETTER FOR ".$claim->claimNo.'_'.$claim->vehicleRegNo.'_'.$this->functions->curlDate(),
            'from' => Config::JUBILEE_NO_REPLY_EMAIL,
            'to' => $email,
            'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
            'attachment' => $pdfFilePath,
            'cc' => Auth::user()->email,
            'html' => "
                        Dear Sirs, <br>

                        Please release as per the attached. <br/>

                        Regards, <br><br>

                        " . $role . ", <br>

                        Claims Department, <br>

                        Jubilee Allianz Insurance Company
                    ",
        ];

        InfobipEmailHelper::sendEmail($message, $email);
        // SMSHelper::sendSMS('Dear Sir, kindly proceed with repairs as per attached on the email',$userDetail['MSISDN']);
//            $user = User::where(["id" => $userDetail['id']])->first();
//            Notification::send($user, new ClaimApproved($claim));

        $flag = true;
        if ($flag)
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

    public function sendDischargeVoucher($claim_id)
    {
        $assessment = Assessment::where(['claimID' => $claim_id])->with('claim')->first();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured = CustomerMaster::where(["customerCode" => $customerCode])->first();
        $assessmentItem = AssessmentItem::where(["assessmentID" => $assessment->id])->first();
        $jobDetailsSum = JobDetail::where(["assessmentID" => $assessment->id])->get()->sum('cost');
        $priceChange = PriceChange::where('assessmentID', $assessment->id)->first();

        if($assessment['assessmentTypeID'] == Config::ASSESSMENT_TYPES['CASH_IN_LIEU'])
        {
            $val = number_format(round((AssessmentItem::where('assessmentID', $assessment['id'])->sum('total')) + $jobDetailsSum - (isset($assessment->scrapValue) ? $assessment['scrapValue'] : 1) - $assessment['claim']['excess']));

        }
        elseif($assessment['assessmentTypeID'] == Config::ASSESSMENT_TYPES['TOTAL_LOSS'])
        {
            if (isset($assessment['totalChange']) && isset($priceChange->finalApprovedAt)) {
                $val =  number_format(round($assessment['totalChange'] - $assessment['claim']['excess']));
            } else {
                $val = number_format(round($assessment['totalCost'] - $assessment['claim']['excess']));
            }
        }
        $amt = str_replace(',',"", $val);

        $amount = $amt;

        $claimID = $claim_id;

        return view('adjuster.discharge-voucher', compact('claimID', 'assessment', 'jobDetailsSum', 'amount','val', 'insured'));
    }

    public function archiveClaim(Request $request)
    {
        try {
            $archiveNote = $request->archiveNote;
            $claimID = $request->claimID;
            $claim = Claim::where(['id' => $claimID])->first();
            if (isset($claim->id)) {
                Claim::where(["id" => $claimID])->update([
                    "archivedBy" => Auth::id(),
                    "archivalNote" => $archiveNote,
                    "archivedAt" => $this->functions->curlDate(),
                    "active"=>Config::INACTIVE
                ]);
                $assessment = Assessment::where(['claimID' => $claimID])->first();
                if (isset($assessment->id)) {
                    Assessment::where(["id" => $assessment->id])->update([
                        "active" => Config::INACTIVE
                    ]);
                }
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Congratulations!, You have successfully archived a claim"
                );
            }
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying archive claim. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }
    public function addLPO(Request $request)
    {
        try {
            if(isset($request->claimID) && isset($request->amount))
            {
                $claim = Claim::where(['id'=>$request->claimID])->first();
                if(isset($claim->id))
                {
                    Claim::where(['id'=>$claim->id])->update([
                        "LPOAmount"=>$request->amount,
                        "LPOAddedBy"=>Auth::user()->id,
                        "LPODateCreated"=>$this->functions->curlDate()
                    ]);
                    $response = array(
                        "STATUS_CODE" => Config::SUCCESS_CODE,
                        "STATUS_MESSAGE" => "LPO added successfully"
                    );
                }else
                {
                    $response = array(
                        "STATUS_CODE" => Config::NO_RECORDS_FOUND,
                        "STATUS_MESSAGE" => "Record not found"
                    );
                }
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid payload provided"
                );
            }
        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying addPLO. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function editLPO(Request $request)
    {
//
        try {
            if(isset($request->claimID) && isset($request->amount))

            {
                $claim = Claim::where(['id'=>$request->claimID])->first();

                if(isset($claim->id))
                {

                    $updated=Claim::where(['id'=>$claim->id])->first()->update([
                        "LPOAmount"=>$request->amount,
                        "updatedBy"=>Auth::user()->id,
                        "dateModified"=>Carbon::now()
                    ]);

                    $saved=LPOTracker::create([
                        'claimNo'=>$claim->claimNo,
                        'policyNo'=>$claim->policyNo,
                        'initialAmount'=>$claim->LPOAmount,
                        'currentAmount'=>$request->amount,
                        'createdBy'=>$claim->createdBy,
                        'updatedBy'=>Auth::id(),
                        'dateCreated'=>$claim->LPODateCreated,
                        'dateModified'=>Carbon::now()
                    ]);
                        if ($updated && $saved){
                            $response = array(
                            "STATUS_CODE" => Config::SUCCESS_CODE,
                            "STATUS_MESSAGE" => "Updated and Saved successfully"
                            );
                        }
                }
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid payload provided"
                );
            }

        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying updatePLO. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }


    public function processCourtesy(Request $request)
    {
        try {
            if(isset($request->nofdays) && isset($request->rdate) && isset($request->charge) && isset($request->totalCharge))
            {
                $noodays = $request->nofdays;
                $rdate = $request->rdate;
                $charge = $request->charge;
                $totalCharge = $request->totalCharge;

                courtesyCar::create([
                    'vendorID'=>$request->vendorID,
                    'claimID'=>$request->claimID,
                    'numberOfDays'=>$noodays,
                    'returnDate'=>$rdate,
                    'charge'=>$charge,
                    'totalCharge'=>$totalCharge,
                    "createdBy" => Auth::user()->id,
                    "dateCreated"=>$this->functions->curlDate()
                ]);
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Courtesy Car processed Successfully"
                );
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Input all required data and try again"
                );
            }
        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
        }
        return json_encode($response);
    }
    public function showCourtesyCar(){

        $courtesyCars= CourtesyCar::with('claim')->with('vendor')->get();
        return view('adjuster.courtesy-cars', ['courtesyCars'=>$courtesyCars]);
    }
    public function getCharge(Request $request){
        $vendorID=$request->vendorID;
        $charge = Vendor::where(['id'=>$vendorID])->first();
        return json_encode($charge);
    }
    public function addDays(Request  $request){
        $rdate =  Carbon::now()->addDays($request->numberOfDays)->toDateTimeString();
        $date= date('Y-m-d H:i:s', strtotime($rdate));
        return $date;
    }
}
