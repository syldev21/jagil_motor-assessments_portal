<?php


namespace App\Http\Controllers\NHIF;
use App\Assessment;
use App\Claim;
use App\ClaimDocument;
use App\ClaimMock;
use App\Conf\Config;
use App\CourtesyCar;
use App\Document;
use App\FollowerClaim;
use App\FollowerProportion;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\Http\Controllers\Controller;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Count;
use function GuzzleHttp\Promise\all;


class NHIFController extends Controller
{
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }

    public function index()
    {
        return view('NHIF.index');
    }

    public function addClaimForm()
    {
        return view('NHIF.add-claim-form');
    }
    public function saveNhifClaim(Request $request)
    {
       $validator=Validator::make($request->all(),[
            "placeOfLoss"=>"required",
            "causeOfLoss"=>"required",
            "dateOfInjury"=>"required",
            "dateReceived"=>"required",
            "lossDescription"=>"required",
            "policyType"=>"required",
            "typeOfInjury"=>"required",
            "claimant"=>"required",
        ]);
        $claim = ClaimMock::orderBy("id","DESC")->first();
        if(isset($claim->claimNo))
        {
            $claimNoArray = explode('/',$claim->claimNo);

            $lastArrayElement = end($claimNoArray);
            $lastArrayElement++;
            array_pop($claimNoArray);
            array_push($claimNoArray,$lastArrayElement);
            $newClaim = implode('/',$claimNoArray);
        }else
        {
            $newClaim = "C/101/1002/2020/003000";
        }


        $agent = ClaimMock::orderBy('id','DESC')->first();
        if(isset($agent->agent))
        {
            $agentNoArray = explode('/',$agent->agent);

            $lastArrayElement = end($agentNoArray);
            $lastArrayElement++;
            array_pop($agentNoArray);
            array_push($agentNoArray,$lastArrayElement);
            $newAgent = implode('/',$agentNoArray);
        }else
        {
            $newAgent = "A/101/1000/2022/003000";
        }
        $insured="NATIONAL HOSPITAL  INSURANCE FUND";

//        $WEF = "Jan 01, 2022 00:00:00";
//        $WET = "Mar 31, 2022 23:59:00";

        $civilFDate=explode(" ", Carbon::parse(Config::POLICY_TYPES["CIVIL_SERVANTS_AND_NYS"]["WEF"])->format('Y-m-d H:i:s'));
        $civilTDate=explode(" ", Carbon::parse(Config::POLICY_TYPES["CIVIL_SERVANTS_AND_NYS"]["WET"])->format('Y-m-d H:i:s'));
        $civilPolicyDates=array($civilFDate[0], $civilTDate[0]);

        $policeFDate=explode(" ", Carbon::parse(Config::POLICY_TYPES["KENYA_POLICE_AND_KENYA_PRISONS"]["WEF"])->format('Y-m-d H:i:s'));
        $policeTDate=explode(" ", Carbon::parse(Config::POLICY_TYPES["KENYA_POLICE_AND_KENYA_PRISONS"]["WET"])->format('Y-m-d H:i:s'));
        $policePolicyDates=array($policeFDate[0], $policeTDate[0]);

        if ($request->policyType == Config::POLICY_TYPES["CIVIL_SERVANTS_AND_NYS"]["ID"]){
            $fromDate=$civilPolicyDates[0];
            $toDate=$civilPolicyDates[1];
        }else{
            $fromDate=$policePolicyDates[0];
            $toDate=$policePolicyDates[1];
        }


        $dInjury=explode(" ", Carbon::parse($request->dateOfInjury)->format('Y-m-d H:i:s'));
        $dReceived=explode(" ", Carbon::parse($request->dateReceived)->format('Y-m-d H:i:s'));
        $dateOfInjury=$dInjury[0];
        $dateReceived=$dReceived[0];

        $emails = array(Config::CLAIM_NOTIFICATION_CONTACTS["HARRIET"]["EMAIL"],Config::CLAIM_NOTIFICATION_CONTACTS["LINUS"]["EMAIL"],Config::CLAIM_NOTIFICATION_CONTACTS["NABWIRE"]["EMAIL"]);
        $cc1=User::where(["id"=>1059])->first()->email;
        $cc2=User::where(["id"=>1049])->first()->email;
        $ccEmails=array("Sylvester.Ouma@jubileekenya.com", "SylvesterOuma282@gmail.com");
        $claim_number=$newClaim;
        if ($request->policyType == Config::POLICY_TYPES["KENYA_POLICE_AND_KENYA_PRISONS"]["ID"]){
            $messageClaim = Config::POLICY_TYPES["KENYA_POLICE_AND_KENYA_PRISONS"]["TITLE"];
//            dump($messageClaim);
        }else{
            $messageClaim = Config::POLICY_TYPES["CIVIL_SERVANTS_AND_NYS"]["TITLE"];
//            dd($messageClaim);
        }
        $emMessageClaim= "<b>"."<i>".$messageClaim."</i>"."</b>";

        $message = $emMessageClaim." NHIF Policy has been registered to Premia with claim number <span><strong><em>$claim_number</em></strong></span>. Kindly action.";

        $header = "<p>
                       <br/>
                       <br/>
                        <i>Dear Adjuster,
                        <br/>
                        </i>
                </p>";
        $footer = "<p>
                       Thanks.
                       <br/>
                       <br/>
                        <i>Regards,
                        <br/>
                        ".Auth::user()->name.".
                        <br/>
                        </i>
                </p>";


        $msg =$header.$message.$footer;
        $message = [
            'subject' => $claim_number.'_'.$this->functions->curlDate(),
            'from' => Config::JUBILEE_NO_REPLY_EMAIL,
            'to' => $emails,
            'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
            'cc' => $ccEmails,
            'html' => $msg,
        ];



        if ($validator->fails()){
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::VALIDATOR["REQUIRED_FIELDS"]["TITLE"]
            );
        }
        elseif ($dateOfInjury < $fromDate || $dateOfInjury>$toDate){
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::VALIDATOR["ALLOWED_DATE_RANGE"]["TITLE"]
            );
        }
        elseif ($dateReceived < $dateOfInjury){
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::VALIDATOR["EARLY_DATE"]["TITLE"]
            );
        }elseif ($dateReceived > Carbon::now()->format('Y-m-d H:i:s')){
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::VALIDATOR["LATER_DATE"]["TITLE"].Carbon::now()->format("Y-m-d  H:i:s")
            );
        }else{
            $email_sent=InfobipEmailHelper::sendEmail($message);
//            dd($rea);

            $nhifClaimID = ClaimMock::insertGetId([
                "claimNo"=>$newClaim,
                "policyNo"=>$request->policyType,
                "agent"=>$newAgent,
                "insured"=>$insured,
                "claimant"=>$request->claimant,
                "postalAddress"=>$request->postalAddress,
                "postalCode"=>$request->postalCode,
                "telephone"=>$request->telephone,
                "mobile"=>$request->mobile,
                "email"=>$request->email,
                "occupation"=>$request->occupation,
                "dateOfBirth"=>Carbon::parse($request->dateOfBirth)->format('Y-m-d H:i:s'),
                "IDNumber"=>$request->IDNumber,
                "placeOfLoss"=>$request->placeOfLoss,
                "causeOfLoss"=>$request->causeOfLoss,
                "typeOfInjury"=>$request->typeOfInjury,
                "dateOfInjury"=>$dateOfInjury,
                "dateReceived"=>$dateReceived,
                "lossDescription"=>$request->lossDescription,
                "status"=>Config::NHIF_DISPLAY_STATUSES["SUBMITTED"]["ID"],
                "modifiedBy"=>Auth::id(),
                "createdBy"=>Auth::id(),
                "dateModified"=>Carbon::now(),
                "dateCreated"=>Carbon::now()
            ]);
             $file = $request->file('file');
//            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $path = $file->getRealPath();
            $size = $file->getSize();
            $picture = $newClaim.".pdf";

            //Save files in below folder path, that will make in public folder
            $file->move(public_path('claim_documents/'), $picture);
            $documents = ClaimDocument::create([
                "claimID" => $nhifClaimID,
                "name" => $picture,
                "mime" => $extension,
                "size" => $size,
                "pdfType" => Config::PDF_TYPES['CLAIM_FORM']['ID'],
                "documentType" => $documentType = Config::$DOCUMENT_TYPES["PDF"]["ID"],
                "url" => $path,
                "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"]
            ]);

            if ($nhifClaimID && $documents && $email_sent->messages[0]->status->groupId==1){
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "NHIF claim Saved successfully"
                );
            }
            else
            {

                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }
        }



        return json_encode($response);
    }

    public function fetchClaims(Request $request)
    {


        $claimStatusID = $request->claimStatusID;
        $claimNumber = $request->claimNumber;
        $fDate = $request->fromDate;
        $tDate = $request->toDate;

        try {
            if (!isset($fDate) && !isset($tDate) && !isset($claimNumber)) {
                $claims = ClaimMock::where('status', "=", $claimStatusID)->orderBy('dateCreated', 'DESC')->get();

            } elseif (isset($claimNumber)) {
                $claims = ClaimMock::where(['status' => $claimStatusID,'claimNo' => $claimNumber])->orderBy('dateCreated', 'DESC')->get();

            } elseif (isset($fDate) && isset($tDate) && !isset($claimNumber)) {
                $fromDate = Carbon::parse($fDate)->format('Y-m-d H:i:s');
                $toDate = Carbon::parse($tDate)->format('Y-m-d H:i:s');
                $claims = ClaimMock::whereBetween('dateCreated', [$fromDate, $toDate])->orderBy('dateCreated', 'DESC')->get();
            } else {
                $claims = array();
//            dd($claims);
            }
            return view("NHIF.fetch-nhif-policies", ["claims" => $claims, "claimStatusID" => $claimStatusID]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
    public function fetchProportions(){
        $claims=FollowerClaim::all();

        return view("NHIF.fetch_proportions", ["claims"=>$claims]);


    }
    public function uploadDocuments(Request $request)
    {
        try {
            $totalImages = $request->totalImages;
            $claimID = $request->claimID;
//            echo $claimID;
//            exit();
            $claimData = ClaimMock::where(['id'=>$claimID])->first();
            $claimNo =  str_replace("/","_",$claimData->claimNo);

            //Loop for getting files with index like image0, image1
            if ($request->hasFile('claimForm')) {
                $claim = 'claim';
                $pdfs = ClaimDocument::where(['claimID' => $claimID, 'documentType' => Config::$DOCUMENT_TYPES["PDF"]["ID"],'pdfType'=>Config::PDF_TYPES['CLAIM_FORM']['ID']])
                    ->whereNotNull('claimID')
                    ->get();
                if (count($pdfs) > 0) {
                    $affectedPdfRows = ClaimDocument::where(['claimID' => $claimID, 'documentType' => Config::$DOCUMENT_TYPES["PDF"]["ID"],'pdfType'=>Config::PDF_TYPES['CLAIM_FORM']['ID']])
                        ->whereNotNull('claimID')
                        ->delete();
                    if ($affectedPdfRows > 0) {
                        foreach ($pdfs as $pdf) {
                            $image_path = "claim_documents/" . $pdf->name;  // Value is not URL but directory file path
                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }
                        }
                    }
                }
                $file = $request->file('claimForm');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $path = $file->getRealPath();
                $size = $file->getSize();
                $picture = $claimNo.".pdf";
                //Save files in below folder path, that will make in public folder
                $file->move(public_path('claim_documents/'), $picture);
                $documents = ClaimDocument::create([
                    "claimID" => $claimID,
                    "name" => $picture,
                    "mime" => $extension,
                    "size" => $size,
                    "pdfType" => Config::PDF_TYPES['CLAIM_FORM']['ID'],
                    "documentType" => $documentType = Config::$DOCUMENT_TYPES["PDF"]["ID"],
                    "url" => $path,
                    "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"]
                ]);
                if ($totalImages == 0) {
                    $response = array(
                        "STATUS_CODE" => Config::SUCCESS_CODE,
                        "STATUS_MESSAGE" => "Congratulations! Your documents has been uploaded successfully"
                    );
                    return json_encode($response);
                }
            }
            $documentsArray = [];
            if ($totalImages > 0) {
                for ($x = 0; $x < $totalImages; $x++) {
                    if ($request->hasFile('images' . $x)) {
                        $file = $request->file('images' . $x);
                        $filename = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $path = $file->getRealPath();
                        $size = $file->getSize();
                        $picture = date('His') . '-' . $filename;
                        //Save files in below folder path, that will make in public folder
                        $file->move(public_path('claim_documents/'), $picture);
                        $documents = array(
                            "claimID" => $claimID,
                            "name" => $picture,
                            "mime" => $extension,
                            "size" => $size,
                            "documentType" => Config::$DOCUMENT_TYPES["IMAGE"]["ID"],
                            "url" => $path,
                            "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"]
                        );
                        $documentsArray[] = $documents;
                    }
                }
                $collection = collect($documentsArray);
                $save = ClaimDocument::insert($collection->values()->all());
                if ($save) {
                    $response = array(
                        "STATUS_CODE" => Config::SUCCESS_CODE,
                        "STATUS_MESSAGE" => "Congratulations! Your documents has been uploaded successfully"
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
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }
        } catch (\Exception $e) {
//            echo $e->getMessage();
//            exit();
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "Documents not uploaded an error. An error occurred " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function claimDetails(Request $request){
        $data=$request->status;

        try {


            $claim = ClaimMock::where(["status"=>$request->status,"claimNo"=>$request->claimNo])->first();

            $documents=ClaimDocument::where(['claimID'=>$claim['id']])->get();
            return view('nhif.claim-details', ['claim' => $claim, "documents"=>$documents, "data"=>$data]);
        } catch (\Exception $e) {
//            echo $e->getMessage();
//            exit();
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch claim details. Error message " . $e->getMessage());
        }
    }

    public function uploadDocumentsForm(Request $request)
    {
        $claim = ClaimMock::where(['id' => $request->claimID])->first();
        return view('NHIF.file-upload', ["claim"=>$claim]);
    }
    public function filterUsers(Request $request){

        $users=User::where("id", 1040)->get("firstName", "lastName");
        dd($users);


            return view("nhif.fetch-nhif-policies", ["users"=>$users]);

    }
}
