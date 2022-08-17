<?php

namespace App\Http\Controllers\homeFibre;

use App\Conf\Config;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\Http\Controllers\Controller;
use App\SafaricomClaim;
use App\SafaricomClaimDocument;
use App\SafClaimDocument;
use App\User;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;
use Illuminate\Support\Facades\File;

class SafaricomHomeFibreController extends Controller
{
    private $log;
    private $functions;
    private $utility;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
        $this->utility = new Utility();
    }

    public function index()
    {
        $data = array();
        $response = $this->utility->getData($data, '/api/v1/b2b/general/home-insurance/summary', 'POST');
        $claim_data = json_decode($response->getBody()->getContents());
        if ($claim_data->status == 'success') {
            $summary = json_decode(json_encode($claim_data->data), true);
        } else {
            $summary = [];
        }
        return view('layouts.home-fibre.master', ['summary' => $summary]);
    }

    public function fetchCustomers()
    {
        $data = array();
        $response = $this->utility->getData($data, '/api/v1/b2b/general/home-insurance/all-customers', 'POST');
        $claim_data = json_decode($response->getBody()->getContents());
        if ($claim_data->status == 'success') {
            $customers = json_decode(json_encode($claim_data->data), true);
        } else {
            $customers = [];
        }
        return view('safaricom-home-fibre.customers', ['customers' => $customers]);
    }

    public function fetchPayments()
    {
        $data = array();
        $response = $this->utility->getData($data, '/api/v1/b2b/general/home-insurance/payments', 'POST');
        $claim_data = json_decode($response->getBody()->getContents());
        if ($claim_data->status == 'success') {
            $payments = json_decode(json_encode($claim_data->data), true);
        } else {
            $payments = [];
        }
        return view(
            'safaricom-home-fibre.payments', ['payments' => $payments]);
    }

    public function fetchCustomerPayments(Request $request)
    {
        $ci_code = $request->ci_code;
        $data = array(
            "code" => $ci_code
        );
        $response = $this->utility->getData($data, '/api/v1/b2b/general/home-insurance/customer-payments', 'POST');
        $claim_data = json_decode($response->getBody()->getContents());
        if ($claim_data->status == 'success') {
            $payments = json_decode(json_encode($claim_data->data), true);
        } else {
            $payments = [];
        }
        return view('safaricom-home-fibre.customer-payments', ['payments' => $payments]);
    }

    public function fetchPolicyDetails(Request $request)
    {
        $ci_code = $request->ci_code;
        $email = $request->email;
        $phone = $request->phone;
        $data = array(
            "unique_id" => $ci_code
        );
        $response = $this->utility->getData($data, '/api/v1/saf-home/get-policy-details', 'POST');
        $claim_data = json_decode($response->getBody()->getContents());
        if ($claim_data->status == 'success') {
            $policies = json_decode(json_encode($claim_data->data), true);
        } else {
            $policies = [];
        }
        return view('safaricom-home-fibre.customer-policy-details', ['policies' => $policies, 'ci_code' => $ci_code, 'email' => $email, 'phone' => $phone]);
    }

    public function sendPolicyDocument(Request $request)
    {
        $email = $request->email;
        $policyNumber = $request->policyNumber;
        $name = $request->name;
        $nameStrippedData = preg_replace('/\s+/', ' ', $name);
        $nameArray = explode(' ', trim($nameStrippedData));
        $firstName = $nameArray[0];
        $subject = 'HOME INSURANCE POLICY No. ' . $policyNumber;
        $attachment = public_path('SUMMARY_OF_HOME_INSURANCE_COVER.pdf');
        $body = "<div style='width: 600px; margin: 0 auto'>
    <img class='logo' src='https://assessments.jubileeinsurance.com/images/logo/jubilee_logo.png' title='Jubilee logo' alt='Jubilee logo' width='200px' height='70px' style='display: block; margin: 0 auto'>
    <h3>Dear $firstName,</h3>
    Thank you for insuring your home contents with Jubilee Allianz Insurance.<br/>
    Please find attached your policy document for your perusal and records.<br/>
    Kindly feel free to contact us  on 0719222111/ 0709949000 for any clarification.<br/>
    <br/>
</div>";
        $message = [
            'subject' => $subject,
            'from' => Config::JUBILEE_NO_REPLY_EMAIL,
            'to' => $email,
            'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
            'attachment' => $attachment,
            'html' => $body
        ];
        $response = InfobipEmailHelper::sendEmail($message, $email);
        $decodedResponse = json_decode(json_encode($response),true);
        if(count($decodedResponse) == 1)
        {
            $response = array(
                "STATUS_CODE" => Config::SUCCESS_CODE,
                "STATUS_MESSAGE" => "Policy Document emailed successfully"
            );
        }else
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
        }
        return json_encode($response);
    }
    public function fetchPortfolio(Request $request){
        $payments = session("payments");
        $ci_code = $request->ci_code;
        $email = $request->email;
        $phone = $request->phone;
        $data = array(
            "unique_id" => $ci_code
        );
        $response = $this->utility->getData($data, '/api/v1/saf-home/get-policy-details', 'POST');
        $claim_data = json_decode($response->getBody()->getContents());
        if ($claim_data->status == 'success') {
            $policies = json_decode(json_encode($claim_data->data), true);
        } else {
            $policies = [];
        }

//        dd($policies);
        if (Carbon::now()->format("Y-m-d  H:i:s") > $policies[0]["to_date"]){
            $status="Lapsed";
        }elseif (Carbon::now()->addDays(30)->format("Y-m-d  H:i:s") == $policies[0]["to_date"]){
            $status="Due for renewal";
        }elseif (Carbon::now()->addDays(30)->format("Y-m-d  H:i:s") < $policies[0]["to_date"] && Carbon::now()->format("Y-m-d  H:i:s") > $policies[0]["from_date"]){
            $status="Active";
        }
        session(["policies"=>$policies]);
        $customer_portforlio = [$policies, $status, $payments
        ];

        return view("safaricom-home-fibre.customer.portfolio", ["customer_portforlio"=>$customer_portforlio]);
    }
    public function fetchCPayments(Request $request){
        $policies = session("policies");
        $ci_code = $request->ci_code;
        $data = array(
            "code" => $ci_code
        );
        $response = $this->utility->getData($data, '/api/v1/b2b/general/home-insurance/customer-payments', 'POST');
        $claim_data = json_decode($response->getBody()->getContents());
        if ($claim_data->status == 'success') {
            $payments = json_decode(json_encode($claim_data->data), true);
        } else {
            $payments = [];
        }
        session(["payments"=>$payments]);

        if ($policies!=null && $payments!=null){
            $oustanding_amount = $policies[0]["premium"]-$payments[0]["amount"];
        }else{
            $oustanding_amount = "";
        }
        return view("safaricom-home-fibre.customer.payments", ["payments"=>$payments, "policies"=>$policies, "oustanding_amount"=>$oustanding_amount]);
    }
    public function fetchMyClaims(){
        $policies = session("policies");
        $uniqeCode=Auth::user()->ci_code;
        $claims = SafaricomClaim::where(["ci_code"=>$uniqeCode])->get();

        return view("safaricom-home-fibre.customer.claims", ["claims"=>$claims, "policies"=>$policies]);
    }
    public function lauchClaimForm(){
        $policies= session("policies");
        $name=Auth::user()->name;
        return view("safaricom-home-fibre.customer.shf_file_uploads", ["policies"=>$policies, "name"=>$name]);
    }
    public function downloadClaimForm(Request  $request){

        $file= public_path("safclaimform/HOME_FIBER_CLAIM_FORM_Interactive.pdf");

        $headers = array(
            'Content-Type: application/pdf',
//            'Content-Transfer-Encoding' => 'Binary'
        );

        return Response::download($file, 'claim_form.pdf', $headers);
    }

    public function validatePageOne(Request $request){
        $policies= session("policies");
        $name=Auth::user()->name;
        return view("safaricom-home-fibre.customer.claim-form-p1", ["policies"=>$policies, "name"=>$name]);
    }
    public function previousPageOne(Request $request){
        return view("safaricom-home-fibre.customer.completed-claim-form");
    }
    public function saveSafaricomClaim(Request $request)
    {
        $request->validate([
            "lossDescription" => "required",
            "uploadClaimFormpdf" => "required",
            "abstract_file" => "required"
        ],
        [
            "lossDescription.required" => "Please add your claim description",
            "uploadClaimFormpdf.required" => "Please attach a duly filled claim form",
            "abstract_file.required" => "Please attach a police abstract"
        ]);



            $safaricomClaimID = SafaricomClaim::insertGetId([
                'lossDescription' => $request->lossDescription,
                'ci_code' => Auth::user()->ci_code,
                "updatedBy" => Auth::id(),
                "createdBy" => Auth::id(),
                "dateModified" => Carbon::now(),
                "dateCreated" => Carbon::now()
            ]);

            $files = [
                [$request->file('uploadClaimFormpdf'), $request->claim_form],
                [$request->file('abstract_file'), $request->abstract_form],
                [$request->file('file2'), $request->handset_certificate],
                [$request->file('file3'), $request->proforma_invoice]
            ];
            $pdfFilePaths = [];
            foreach ($files as $file) {
                if ($file[0] != null) {
                    $extension = $file[0]->getClientOriginalExtension();
                    $path = $file[0]->getRealPath();
                    $size = $file[0]->getSize();
                    $picture = $file[1] . "." . $extension;

                    $file[0]->move(public_path('/claim_documents/' . Auth::id() . '/'), $picture);
                    $documents = SafClaimDocument::create([
                        "claimID" => $safaricomClaimID,
                        "name" => $picture,
                        "mime" => $extension,
                        "size" => $size,
                        "pdfType" => Config::PDF_TYPES['CLAIM_FORM']['ID'],
                        "documentType" => Config::$DOCUMENT_TYPES["PDF"]["ID"],
                        "url" => $path,
                        "segment" => ""
                    ]);
                    array_push($pdfFilePaths, public_path('/claim_documents/' . Auth::id() . '/' . $picture));

                }
            }


            $emails = Config::SAF_EMAIL["EMAIL"];
            $first_name_customer = Auth::user()->firstName;
            $email_customer = Auth::user()->email;

            $first_name = explode(" ", Config::SAF_EMAIL['NAME'])[0];

            $message_adjuster = "A new claim has been submitted. Kindly action.";
            $message_customer = "We acknowledge the receipt of your claim. The claim is under review.";
            $claims_adjuster=Config::SAF_EMAIL['NAME'];
            $claims_adjuster_phone=Config::SAF_EMAIL['PHONE'];

            $header = "<p>
                       <br/>
                       <br/>
                        <i>Dear $first_name,
                        <br/>
                        </i>
                </p>";
            $footer = "<p>
                       Thanks.
                       <br/>
                       <br/>
                        <i>Regards,
                        <br/>
                        <br/>
                        </i>
                </p>";


            $msg = $header . $message_adjuster . $footer;
            $message = [
                'subject' => Auth::user()->name . '_' . 'NEW CLAIM' . '_' . $this->functions->curlDate(),
                'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                'to' => 'sylvester.ouma@jubileekenya.com',
                'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                'attachment' => $pdfFilePaths,
                'html' => $msg,
            ];
        if (sizeof($pdfFilePaths) == 1){
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => 'You need to attach at least two documents (abstract and claim form)'
            );
        }else{


            $email_sent = InfobipEmailHelper::sendEmail($message);
//            dd($email_sent);
        }
//        $email_sent = InfobipEmailHelper::sendEmail($message);

            if ($safaricomClaimID && $documents && $email_sent) {
                File::deleteDirectory(public_path('/claim_documents/' . Auth::id() . '/'));

                $header = "<p>
                       <br/>
                       <br/>
                        <i>Dear $first_name_customer,
                        <br/>
                        </i>
                </p>";
                $footer = "<p>
                       Thanks.
                       <br/>
                       <br/>
                        <i>Regards,
                        <br/>
                        <br/>
                        </i>
                        $claims_adjuster-Claims Adjuster,<br>
                        $claims_adjuster_phone<br>
                        Jubilee General Allianz Insurance Limited Company (K)
                </p>";


                $msg = $header . $message_customer . $footer;
                $message = [
                    'subject' => Auth::user()->name . '_' . 'SUBMITTED CLAIM' . '_' . $this->functions->curlDate(),
                    'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'to' => 'sylvesterouma282@gmail.com',
                    'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                    'html' => $msg,
                ];
                $email_customer_sent = InfobipEmailHelper::sendEmail($message);

               if ($email_customer_sent){
                   $response = array(
                       "STATUS_CODE" => Config::SUCCESS_CODE,
                       "STATUS_MESSAGE" => "Home Fiber Claim Submitted successfully"
                   );
               }
            } else {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }

            return json_encode($response);
    }
    public function fetchAllClaims(){

        if (isset(Auth::user()->ci_code)){
            $user=Auth::user();
            $ci_code=$user->ci_code;
            $data = array(
                "unique_id" => $ci_code
            );
            $response = $this->utility->getData($data, '/api/v1/saf-home/get-policy-details', 'POST');
            $claim_data = json_decode($response->getBody()->getContents());
            if ($claim_data->status == 'success') {
                $policies = json_decode(json_encode($claim_data->data), true);
            } else {
                $policies = [];
            }

                    $updateUser=$user->update(['policy_number'=>isset($policies[0]['policy_number'])?$policies[0]['policy_number']:null, 'kra_pin'=>isset($policies[0]['kra_pin'])?$policies[0]['kra_pin']:null, 'assured_code'=>isset($policies[0]['assured_code'])?$policies[0]['assured_code']:null]);



            $claims = SafaricomClaim::join('users', ["safaricom_home_claims.ci_code"=>"users.ci_code"])
                ->where(['users.ci_code'=>$ci_code])->get();
        }else{
            $claims = SafaricomClaim::join('users', ['safaricom_home_claims.ci_code'=>'users.ci_code'])->get();
            $policies = [];
        }


        return view("safaricom-home-fibre.customer.claims", ["claims"=>$claims, "policies"=>$policies]);
    }
}
