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
        return view("safaricom-home-fibre.customer.portfolio", ["policies"=>$policies, "status"=>$status, "payments"=>$payments]);
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
        $oustanding_amount = $policies[0]["premium"]-$payments[0]["amount"];
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
        return view("safaricom-home-fibre.customer.claim_file_upload", ["policies"=>$policies, "name"=>$name]);
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

           $safaricomClaimID=SafaricomClaim::insertGetId([
            'lossDescription'=>$request->lossDescription,
            'ci_code'=>Auth::user()->ci_code,
            "updatedBy"=>Auth::id(),
            "createdBy"=>Auth::id(),
            "dateModified"=>Carbon::now(),
            "dateCreated"=>Carbon::now()
        ]);

        $files= [
            [$request->file('file'), $request->claim_form],
            [$request->file('file1'), $request->abstract_form],
            [$request->file('file2'), $request->handset_certificate],
            [$request->file('file3'), $request->proforma_invoice]
        ];

        foreach ($files as $file){
            $extension = $file[0]->getClientOriginalExtension();
            $path = $file[0]->getRealPath();
            $size = $file[0]->getSize();
            $picture = $file[1].".".$extension;

            $file[0]->move(public_path('/claim_documents/'.Auth::id().'/'), $picture);
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
        }

        $emails = Config::SAF_EMAIL["EMAIL"];

        $first_name = explode(" ", Config::SAF_EMAIL['NAME'])[0];

        $message = "A new claim has been submitted. Kindly action.";

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


        $pdfFilePaths = [
            public_path('\claim_documents\\'.Auth::id().'\claimForm.pdf'),
            public_path('\claim_documents\\'.Auth::id().'\abstract.pdf'),
            public_path('\claim_documents\\'.Auth::id().'\handsetCertificate.pdf'),
            public_path('\claim_documents\\'.Auth::id().'\proformaInvoice.pdf'),
            ];

        $msg =$header.$message.$footer;
        $message = [
            'subject' => Auth::user()->name.'_'.'NEW CLAIM'.'_'.$this->functions->curlDate(),
            'from' => Config::JUBILEE_NO_REPLY_EMAIL,
            'to' => 'sylvesterouma282@gmail.com',
            'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
            'attachment' => $pdfFilePaths,
            'cc' => "sylvester.ouma@jubileekenya.com",
            'html' => $msg,
        ];
        $email_sent=InfobipEmailHelper::sendEmail($message);

        if($safaricomClaimID && $documents && $email_sent)
        {
            File::deleteDirectory(public_path('/claim_documents/'.Auth::id().'/'));
            $response = array(
                "STATUS_CODE" => Config::SUCCESS_CODE,
                "STATUS_MESSAGE" => "Home Fiber Claim Submitted successfully"
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
}
