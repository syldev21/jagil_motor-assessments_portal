<?php


namespace App\Http\Controllers\travel;


use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Http\Controllers\Controller;
use App\Utility;
use Illuminate\Http\Request;

class TravelController extends Controller
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
        return view('travel.index');
    }
    public function fetchPolicies(Request $request)
    {
        $data = array(
            "policyStatus" => isset($request->status) ? $request->status : '',
            "policyNumber" => isset($request->policyNumber) ? $request->policyNumber : '',
            "fromDate"=> isset($request->fromDate) ? $request->fromDate : '',
            "toDate"=>isset($request->toDate) ? $request->toDate : ''
        );
        $response = $this->utility->getData($data, '/api/v1/b2b/general/travel/fetch-travel-policy', 'POST');
        $policy_data = json_decode($response->getBody()->getContents());
        if ($policy_data->status == 'success') {
            $travelPolicies = json_decode(json_encode($policy_data->data), true);
        } else {
            $travelPolicies = [];
        }
        return view('travel.policies',['policies'=>$travelPolicies,'policyStatus'=> isset($request->status) ? $request->status : '']);
    }
}
