<?php

namespace App\Http\Controllers\homeFibre;

use App\Conf\Config;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\Http\Controllers\Controller;
use App\Utility;
use Illuminate\Http\Request;

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
        $this->fetchCPayments($customers);
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
        return view('safaricom-home-fibre.payments', ['payments' => $payments]);
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
    public function fetchPortfolio(){
        return view("safaricom-home-fibre.customer.portfolio");
    }
    public function fetchCPayments($customers){
        dd($customers);
        return view("safaricom-home-fibre.customer.payments");
    }
    public function fetchMyClaims(){
        return view("safaricom-home-fibre.customer.claims");
    }
}
