<?php

namespace App\Http\Controllers\homeFibre;

use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
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
        return view('layouts.home-fibre.master',['summary'=>$summary]);
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
        return view('safaricom-home-fibre.customers',['customers'=>$customers]);
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
        return view('safaricom-home-fibre.payments',['payments'=>$payments]);
    }
    public function fetchCustomerPayments(Request $request)
    {
        $ci_code= $request->ci_code;
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
        return view('safaricom-home-fibre.customer-payments',['payments'=>$payments]);
    }
    public function fetchPolicyDetails(Request $request)
    {
        $ci_code= $request->ci_code;
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
        return view('safaricom-home-fibre.customer-policy-details',['policies'=>$policies,'ci_code'=>$ci_code]);
    }
}
