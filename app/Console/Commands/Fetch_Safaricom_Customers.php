<?php

namespace App\Console\Commands;

use App\Conf\Config;
use App\User;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Fetch_Safaricom_Customers extends Command
{
    private $utility;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fetch_safaricom_customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch SHF customers and their details';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->utility = new Utility();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Home Fibre Customers - fetching safaricom customer has started...");
        $this->fetchCustomers();
    }
    public function fetchCustomers(){
//        $users = User::whereKraPin(null)->where("userTypeID", 3)->get();
//        foreach ($users as $saf_user){
//            $ci_code=$saf_user->ci_code;
//            $data = array(
//                "unique_id" => $ci_code
//            );
//            $response = $this->utility->getData($data, '/api/v1/saf-home/get-policy-details', 'POST');$claim_data = json_decode($response->getBody()->getContents());
//            if ($claim_data->status == 'success') {
//                $policies = json_decode(json_encode($claim_data->data), true);
//            } else {
//                $policies = [];
//            }
//
//            $updateUser=$saf_user->update(['policy_number'=>isset($policies[0]['policy_number'])?$policies[0]['policy_number']:"", 'kra_pin'=>isset($policies[0]['kra_pin'])?$policies[0]['kra_pin']:"", 'assured_code'=>isset($policies[0]['assured_code'])?$policies[0]['assured_code']:'']);
//            if ($updateUser){
//                dump("user updated successfully");
//            }else{
//                dump("user not updated");
//            }
//        }
//        dd();
//        $users = User::where(["userTypeID" => 3])->update(['policy_number'=>isset($policies[0]['policy_number'])?$policies[0]['policy_number']:NULL, 'kra_pin'=>isset($policies[0]['kra_pin'])?$policies[0]['kra_pin']:NULL, 'assured_code'=>isset($policies[0]['assured_code'])?$policies[0]['assured_code']:NULL]);
//
//        dd();
//        $users = User::whereKraPin(null)->where("userTypeID", 3)->get();
//        dd(count($users));
        $data = array();
        $response = $this->utility->getData($data, '/api/v1/b2b/general/home-insurance/all-customers', 'POST');
        $claim_data = json_decode($response->getBody()->getContents());
        if ($claim_data->status == 'success') {
            $customers = json_decode(json_encode($claim_data->data), true);
        } else {
            $customers = [];
        }
        foreach ($customers as $customer){
            if ($customer['client_email'] != null){

                $nameArray = explode(" ", $customer['client_name']);
                if (count($nameArray) > 2){
                    $middleName=$nameArray[2];
                }else{
                    $middleName='';
                }
                $user = User::where('email', '=', $customer['client_email'])->first();
                if ($user === null){
                    $createUser = User::updateOrCreate([
                        'name'=>$customer['client_name'],
                        'firstName'=>$nameArray[0],
                        'middleName'=>$middleName,
                        'lastName'=>end($nameArray),
                        'idNumber'=>'',
                        'email'=>$customer['client_email'],
                        'physical_address'=>$customer['physical_address'],
                        'branch_id'=>'',
                        'MSISDN'=>$customer['client_phone'],
                        'userTypeID'=>3,
                        'ci_code'=>$customer['code'],
                        'c_product'=>$customer['product'],
                        'kra_pin'=>null,
                        'location'=>null,
                        'latitude'=>null,
                        'longitude'=>null,
                        'loggedInAt'=>null,
                        'email_verified_at'=>Carbon::now()->format("Y-m-d  H:i:s"),
                        'emailVerifiedAt'=>Carbon::now()->format("Y-m-d  H:i:s"),
                        'loggedOutAt'=>Carbon::now()->format("Y-m-d  H:i:s"),
                        'minAmount'=>1500,
                        'maxAmount'=>1500,
                        'password'=>Hash::make(Config::DEFAULT_PASSWORD),
                        'loginAttemps'=>1,
                        'active'=>'1',
                        'status'=>'1',
                        'online'=>'1',
                        'durationOnline'=>0,
                        'signature'=>'',
                        'accountLocked'=>0,
                        'dateModified'=>Carbon::now()->format("Y-m-d  H:i:s"),
                        'dateCreated'=>Carbon::now()->format("Y-m-d  H:i:s"),
                        'remember_token'=>''
                    ]);

                    if ($createUser){
                        dump("SHF customer created successfully");
                    }
                }else{
                    dump("this customer is already created");
                }
            }
        }

        $users = User::whereKraPin(null)->where("userTypeID", 3)->get();
        foreach ($users as $saf_user){
            $ci_code=$user->ci_code;
            $data = array(
                "unique_id" => $ci_code
            );
            $response = $this->utility->getData($data, '/api/v1/saf-home/get-policy-details', 'POST');$claim_data = json_decode($response->getBody()->getContents());
            if ($claim_data->status == 'success') {
                $policies = json_decode(json_encode($claim_data->data), true);
            } else {
                $policies = [];
            }

            $updateUser=$saf_user->update(['policy_number'=>isset($policies[0]['policy_number'])?$policies[0]['policy_number']:NULL, 'kra_pin'=>isset($policies[0]['kra_pin'])?$policies[0]['kra_pin']:NULL, 'assured_code'=>isset($policies[0]['assured_code'])?$policies[0]['assured_code']:NULL]);
            if ($updateUser){
                dump("user updated successfully");
            }else{
                dump("user not updated");
            }
        }
    }
}
