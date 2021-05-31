<?php

namespace App\Http\Controllers;

use App\Conf\Config;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Renewal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RenewalsController extends Controller
{

    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }

    public function getselect(Request $request)
    {
        $val = $request->val;
        $elements = \DB::select('select distinct '. $val.' from pr_renewals');
        // dd($elements);
        return view('renewals.getselect',['elements'=> $elements, 'val'=> $val]);
    }

    public function policyRenewal()
    {
       // $user = Auth::user();
       return view('layouts.policy-renewal.master');
    }

    public function fetchRenewals(Request $request)
    {
        $period = $request->period;
        $filter = $request->val;
        $renewals = array();

        if ($period == Config::$PERIOD['TODAY']['ID']) {
            $renewalDate = Carbon::now()->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);
            $renewals = Renewal::whereDate('policyToDate', '=', $formattedDate)->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);
        } elseif ($period == Config::$PERIOD['TOMORROW']['ID']) {
            $renewalDate = Carbon::now()->addDays(Config::$PERIOD['TOMORROW']['DAYS'])->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);
            $renewals =Renewal::whereDate('policyToDate','=', $formattedDate)->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);

        }elseif ($period == Config::$PERIOD['TOMORROW']['ID'])
        {
            $renewalDate = Carbon::now()->addDays(Config::$PERIOD['TOMORROW']['DAYS'])->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);
            $renewals =Renewal::whereDate('policyToDate','=', $formattedDate)->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);

        } elseif ($period == Config::$PERIOD['ONE_WEEK']['ID'])
        {
            $renewalDate = Carbon::now()->addDays(Config::$PERIOD['ONE_WEEK']['DAYS'])->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);
            $renewals =Renewal::whereDate('policyToDate','=', $formattedDate)->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);

        }elseif ($period == Config::$PERIOD['ONE_MONTH']['ID'])
        {
            $renewalDate = Carbon::now()->addDays(Config::$PERIOD['ONE_MONTH']['DAYS'])->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);
            $renewals =Renewal::whereDate('policyToDate','=', $formattedDate)->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);

        }elseif ($period == Config::$PERIOD['TWO_MONTHS']['ID'])
        {
            $renewalDate = Carbon::now()->addDays(Config::$PERIOD['TWO_MONTHS']['DAYS'])->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);

            $renewals =Renewal::whereDate('policyToDate','=', $formattedDate)->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);


        }elseif ($period == Config::$PERIOD['THREE_MONTHS']['ID'])
        {
            $renewalDate = Carbon::now()->addDays(Config::$PERIOD['THREE_MONTHS']['DAYS'])->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);
            $renewals =Renewal::whereDate('policyToDate','=', $formattedDate)->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);
            // echo $formattedDate;
        }

        return view('renewals.index', ['renewals' => $renewals, 'formattedDate' => $formattedDate,'period'=>$period]);
    }

    public function getMoreRenewals(Request $request)
        {
            if($request->ajax())
            {
            $period = '5';
            $renewalDate = Carbon::now()->addDays(Config::$PERIOD['TWO_MONTHS']['DAYS'])->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);
            $renewals =Renewal::whereDate('policyToDate','=', $formattedDate)->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);
            return view('renewals.dynamicIndex', ['renewals' => $renewals, 'formattedDate' => $formattedDate,'period'=>$period])->render();
            }
        }


    public function updatingRenewalPremium(Request $request)
    {
        $renewalPremium = $request->val;
        $id = $request->id;
        $policyNumber = $request->id2;

        $count=Renewal::where('policyNumber',$policyNumber)->count();




        $result = Renewal::where('id',$id)->update(['renewalPremium'=>$renewalPremium, 'approved' => 1]);
        $count2=Renewal::where('policyNumber',$policyNumber)->where('approved',1)->count();


        if($result > 0 && ($count==$count2))
        {
            return view('renewals.status');


        }else{
          return  $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => "We are experiencing a technical problem!"
            );

        }

    }

    public function approveParentDetails(Request $request)
    {

        $id = $request->id;
        $policyNumber = $request->id2;

        $count=Renewal::where('policyNumber',$policyNumber)->count();

        $count2=Renewal::where('policyNumber',$policyNumber)->where('approved',1)->count();


        if($count==$count2)
        {



            return "true";


        }else{
          return "false";


        }

    }
        public function approveAllParents(Request $request)
    {
        $renewalPremium = $request->val;
        $id = $request->id;

        $result = Renewal::find($id);
        if(  isset($result->approvedAll) &&  $result->approvedAll > 0)
        {
            return "checked";

        }else{
            $result2 = Renewal::where('id',$id)->update(['approvedAll' => 1]);
            if($result2 > 0)
            {
                return "checked";
            }
        }

    }

    public function filterRenewals(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $vehicleRegNumber = $request->vehicleRegNumber;
        if(isset($vehicleRegNumber))
        {
            $formattedDate = $this->functions->formatDate($toDate);

            $renewals =Renewal::where('vehicleRegNo','=', $vehicleRegNumber)->orWhere('policyNumber','=', $vehicleRegNumber)->with('departments')->with('divisions')->get()->unique('policyNumber');

            return view('renewals.filterByRegNo', ['renewals' => $renewals, 'formattedDate' => $formattedDate,'period'=>1,]);



        }elseif (isset($fromDate) && isset($toDate))
        {

            $formattedDate = $this->functions->formatDate($toDate);
            $formattedFromDate = $this->functions->formatDate($fromDate);
            $renewals =Renewal::whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))
                ->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))
                ->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);

        }else
        {
            $renewalDate = Carbon::now()->addDays(Config::$PERIOD['TWO_MONTHS']['DAYS'])->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);
            $renewals =Renewal::whereDate('policyToDate','=', $formattedDate)->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);
        }

        return view('renewals.filtered-renewals', ['renewals' => $renewals, 'formattedDate' => $formattedDate,'period'=>1, 'formattedFromDate'=>$formattedFromDate]);
    }





    public function getMoreFilteredRenewals(Request $request)
    {
        if($request->ajax())
        {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $vehicleRegNumber = $request->vehicleRegNumber;
        if(isset($vehicleRegNumber))
        {

            $formattedDate = $this->functions->formatDate($toDate);
            $renewals =Renewal::where('vehicleRegNo','=', $vehicleRegNumber)->orWhere('policyNumber','=', $vehicleRegNumber)->with('departments')->with('divisions')->get()->unique('policyNumber');

            return view('renewals.filterByRegNo', ['renewals' => $renewals, 'formattedDate' => $formattedDate,'period'=>1]);



        }elseif (isset($fromDate) && isset($toDate))
        {

            $formattedDate = $this->functions->formatDate($toDate);
            $formattedFromDate = $this->functions->formatDate($fromDate);
            $renewals =Renewal::whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))
                ->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))
                ->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);
        }else
        {
            $renewalDate = Carbon::now()->addDays(Config::$PERIOD['TWO_MONTHS']['DAYS'])->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);
            $formattedFromDate = $this->functions->formatDate($fromDate);
            $renewals =Renewal::whereDate('policyToDate','=', $formattedDate)->with('departments')->with('divisions')->groupBy('policyNumber')->paginate(150);
        }
        return view('renewals.filtered-renewals', ['renewals' => $renewals, 'formattedDate' => $formattedDate,'period'=>1, 'formattedFromDate'=>$formattedFromDate])->render();
     }
    }
    public function moreFilterByRange(Request $request)
    {
        $val1 = $request->val1;
        $val2 = $request->val2;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;


        if (isset($fromDate) && isset($toDate))
        {
            $formattedDate = $this->functions->formatDate($toDate);
            $formattedFromDate = $this->functions->formatDate($fromDate);

                if($val2 == "make"){
                    // dd($val2."====".$val1."========".$this->functions->formatDate($fromDate)."======". $this->functions->formatDate($toDate));
                    $renewals = Renewal::where('make',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);

                 }
                 if($val2 == "model"){
                    $renewals = Renewal::where('model',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);

                 }
                  if($val2 == "coverType"){
                    $renewals = Renewal::where('coverType',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);


                 }


                 if($val2 == "productDesc"){
                    $renewals = Renewal::where('productDesc',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);


                 }



                  if($val2 == "lossRatio_below_60"){
                    $renewals = Renewal::where('lossRatio','<','61')->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);


                 }
                  if($val2 == "lossRatio_above_60"){
                    $renewals = Renewal::where('lossRatio','>','60')->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);


                 }
                 return view('renewals.filterByRange', ['renewals' => $renewals, 'formattedDate' => $formattedDate,'formattedFromDate'=>$formattedFromDate]);




        }

    }

public function filterBy(Request $request)
    {
        $val1 = $request->val1;
        $val2 = $request->val2;
        $period=$request->period;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;


        if (isset($fromDate) && isset($toDate))
        {
            $formattedDate = $this->functions->formatDate($toDate);
            $formattedFromDate = $this->functions->formatDate($fromDate);

                if($val2 == "make"){
                    // dd($val2."====".$val1."========".$this->functions->formatDate($fromDate)."======". $this->functions->formatDate($toDate));
                    $renewals = Renewal::where('make',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);

                 }
                 if($val2 == "model"){
                    $renewals = Renewal::where('model',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);

                 }
                  if($val2 == "coverType"){
                    $renewals = Renewal::where('coverType',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);


                 }


                 if($val2 == "productDesc"){
                    $renewals = Renewal::where('productDesc',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);


                 }



                  if($val2 == "lossRatio_below_60"){
                    $renewals = Renewal::where('lossRatio','<','61')->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);


                 }
                  if($val2 == "lossRatio_above_60"){
                    $renewals = Renewal::where('lossRatio','>','60')->whereDate('policyToDate','>=', $this->functions->formatDate($fromDate))->whereDate('policyToDate','<=', $this->functions->formatDate($toDate))->groupBy('policyNumber')->paginate(150);


                 }
                 return view('renewals.filterByRange', ['renewals' => $renewals, 'formattedDate' => $formattedDate,'formattedFromDate'=>$formattedFromDate]);




        }
        else{

        if ($period == Config::$PERIOD['TWO_MONTHS']['ID']) {
            $renewalDate = Carbon::now()->addDays(Config::$PERIOD['TWO_MONTHS']['DAYS'])->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);
            // $formattedDate = '2021-05-31';
            // 2021 - 05 - 01
             if($val2 == "make"){
               $renewals =Renewal::where(['make'=>$val1])->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);
            }
             if($val2 == "model"){
               $renewals =Renewal::where(['model'=>$val1])->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);
            }
             if($val2 == "coverType"){
               $renewals =Renewal::where(['coverType'=>$val1])->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);

            }


            if($val2 == "productDesc"){
               $renewals =Renewal::where(['productDesc'=>$val1])->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);

            }



             if($val2 == "lossRatio_below_60"){
               $renewals =Renewal::where('lossRatio','<','61')->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);

            }
             if($val2 == "lossRatio_above_60"){
               $renewals =Renewal::where('lossRatio','>','60')->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);

            }

            //  return view('renewals.filterBy', ['renewals' => $renewals, 'formattedDate' => $formattedDate,]);
            return view('renewals.filterBy', ['renewals' => $renewals, 'formattedDate' => $formattedDate,]);

        }

    }
    }




    public function moreFilterBy(Request $request)
    {
        $val1 = $request->val1;
        $val2 = $request->val2;
        $period=$request->period;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;


        if (isset($fromDate) && isset($toDate))
        {
            $formattedDate = $this->functions->formatDate($fromDate);

                if($val2 == "make"){
                    // dd($val2."====".$val1."========".$this->functions->formatDate($fromDate)."======". $this->functions->formatDate($toDate));
                    $renewals = Renewal::where('make',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($toDate))->whereDate('policyToDate','<=', $this->functions->formatDate($fromDate))->groupBy('policyNumber')->paginate(150);

                 }
                 if($val2 == "model"){
                    $renewals = Renewal::where('model',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($toDate))->whereDate('policyToDate','<=', $this->functions->formatDate($fromDate))->groupBy('policyNumber')->paginate(150);

                 }
                  if($val2 == "coverType"){
                    $renewals = Renewal::where('coverType',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($toDate))->whereDate('policyToDate','<=', $this->functions->formatDate($fromDate))->groupBy('policyNumber')->paginate(150);

                 }


                 if($val2 == "productDesc"){
                    $renewals = Renewal::where('productDesc',$val1)->whereDate('policyToDate','>=', $this->functions->formatDate($toDate))->whereDate('policyToDate','<=', $this->functions->formatDate($fromDate))->groupBy('policyNumber')->paginate(150);


                 }



                  if($val2 == "lossRatio_below_60"){
                    $renewals = Renewal::where('lossRatio','<','61')->whereDate('policyToDate','>=', $this->functions->formatDate($toDate))->whereDate('policyToDate','<=', $this->functions->formatDate($fromDate))->groupBy('policyNumber')->paginate(150);


                 }
                  if($val2 == "lossRatio_above_60"){
                    $renewals = Renewal::where('lossRatio','>','60')->whereDate('policyToDate','>=', $this->functions->formatDate($toDate))->whereDate('policyToDate','<=', $this->functions->formatDate($fromDate))->groupBy('policyNumber')->paginate(150);


                 }




        }
        else{

        if ($period == Config::$PERIOD['TWO_MONTHS']['ID']) {
            $renewalDate = Carbon::now()->addDays(Config::$PERIOD['TWO_MONTHS']['DAYS'])->toDateTimeString();
            $formattedDate = $this->functions->formatDate($renewalDate);
            // $formattedDate = '2021-05-31';
            // 2021 - 05 - 01
             if($val2 == "make"){
               $renewals =Renewal::where(['make'=>$val1])->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);

            }
             if($val2 == "model"){
               $renewals =Renewal::where(['model'=>$val1])->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);

            }
             if($val2 == "coverType"){
               $renewals =Renewal::where(['coverType'=>$val1])->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);

            }


            if($val2 == "productDesc"){
               $renewals =Renewal::where(['productDesc'=>$val1])->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);

            }



             if($val2 == "lossRatio_below_60"){
               $renewals =Renewal::where('lossRatio','<','61')->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);
            }
             if($val2 == "lossRatio_above_60"){
               $renewals =Renewal::where('lossRatio','>','60')->whereDate('policyToDate','=', $formattedDate)->groupBy('policyNumber')->paginate(150);

            }

            //  return view('renewals.filterBy', ['renewals' => $renewals, 'formattedDate' => $formattedDate,]);
        }
    }
    return view('renewals.filterBy', ['renewals' => $renewals, 'formattedDate' => $formattedDate,])->render();
    }




    public function importData()
    {
        $File = "C:\\xampp7.1.33\\htdocs\\learn\\renewals_data.csv";

        $arrResult  = array();
        $handle     = fopen($File, "r");
        if(empty($handle) === false) {
            while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                $arrResult[] = $data;
            }
            fclose($handle);
        }
        $finalData = array();
        foreach ($arrResult as $r)
        {
            $froDate = strtr($r[2], '/', '-');
            $toDate = strtr($r[3], '/', '-');
            $data = array(
                'sysID' => $r[0],
                'policyNumber' => $r[1],
                'policyFromDate' => date("Y-m-d H:i:s",strtotime($froDate)),
                'policyToDate' => date("Y-m-d H:i:s",strtotime($toDate)),
                'productCode' => $r[4],
                'productDesc' => $r[5],
                'coverTypeCode' => $r[6],
                'coverType' => $r[7],
                'vehicleUsageCode' => $r[8],
                'vehicleUsage' => $r[9],
                'make' => $r[10],
                'model' => $r[11],
                'vehicleRegNo' => $r[12],
                'YOM' => $r[13],
                'premiumAmount' =>$r[14],
                'lossRatio' => $r[15],
                'claimAmount' => $r[16],
                'loadFactor' => $r[17],
                'premiumCode' => $r[18],
                'coverDescription' => $r[19],
                'premiumDescription' => $r[20],
                'premiumSiFc' => $r[21],
                'applicationRate' => $r[22],
                'applicationRatePer' => $r[23],
                'applicationMinimumPremium' => $r[24],
                'premiumFC' => $r[25],
                'FAPPremium'=>$r[26],
                'renewalPremium'=> $r[27],
                'UWRenewalPremium' =>$r[28],
                'coverErrYn' => $r[29],
                'policyUwYn' => $r[30],
                'coverUwYn' => $r[31],
                'customerCode' => $r[32],
                'policyHolderCustomerCode' => $r[33],
                'customerName' => $r[34],
                'assuredCode' => $r[35],
                'assuredName' => $r[36]
            );
            Renewal::create([
                'sysID' => $r[0],
                'policyNumber' => $r[1],
                'policyFromDate' => date("Y-m-d H:i:s", strtotime($froDate)),
                'policyToDate' => date("Y-m-d H:i:s", strtotime($toDate)),
                'productCode' => $r[4],
                'productDesc' => $r[5],
                'coverTypeCode' => $r[6],
                'coverType' => $r[7],
                'vehicleUsageCode' => $r[8],
                'vehicleUsage' => $r[9],
                'make' => $r[10],
                'model' => $r[11],
                'vehicleRegNo' => $r[12],
                'YOM' => $r[13],
                'premiumAmount' =>!empty($r[14]) ? $r[14] : 0.00,
                'lossRatio' => !empty($r[15]) ? $r[15] : 0.00,
                'claimAmount' => !empty($r[16]) ? $r[16] : 0.00,
                'loadFactor' => !empty($r[17]) ? $r[17] : 0.00,
                'premiumCode' => $r[18],
                'coverDescription' => $r[19],
                'premiumDescription' => $r[20],
                'premiumSiFc' => $r[21],
                'applicationRate' => !empty($r[22]) ? $r[22] : 0.00,
                'applicationRatePer' => !empty($r[23]) ? $r[23] : 0.00,
                'applicationMinimumPremium' => !empty($r[24]) ? $r[24] : 0.00,
                'premiumFC' => !empty($r[25]) ? $r[25] : 0.00,
                'FAPPremium'=>!empty($r[26]) ? $r[26] : 0.00,
                'renewalPremium'=> !empty($r[27]) ? $r[27] : 0.00 ,
                'UWRenewalPremium' => !empty($r[28]) ? $r[28] : 0.00,
                'coverErrYn' => $r[29],
                'policyUwYn' => $r[30],
                'coverUwYn' => $r[31],
                'customerCode' => $r[32],
                'policyHolderCustomerCode' => $r[33],
                'customerName' => $r[34],
                'assuredCode' => $r[35],
                'assuredName' => $r[36]
            ]);
            array_push($finalData,$data);
        }
    }
}
