<?php

namespace App\Console\Commands;

use App\ClaimDocument;
use App\ClaimMock;
use App\Conf\Config;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NHIF_SEND_MAIL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:nhifSendMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to nhif business with a claim number';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->functions = new GeneralFunctions();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("NHIF Sending email - sending email has started...");
        $this->sendEmail();
    }
    public function sendEmail(){
        $utility = new Utility();
        $access_token = $utility->getToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://digitalappsuat.jubileekenya.com/api/v1/b2b/general/claim/p11-fetch-claim',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token,
                'Cookie: XSRF-TOKEN=eyJpdiI6IlAzYXhzT2FcL21jMXpqVWpibm9XU2JnPT0iLCJ2YWx1ZSI6Ikhaak1Ta2NWREE3UjN0WVVLVml5bm4xMXBscFRvVHh1M0ZRVFVMR1Y0VVJQY3ZqbnZnclZiUW9QNldZN3l0bFFJcHVuWVY4UXdQTEZDZU1VdTFXVzBBPT0iLCJtYWMiOiI0Y2RjOTMzN2MwYzcwZTgxNjA5NmFjODdhNzk3MTRiMmJiMmUwYjliMmFjZjI1MGE4ODZmMDMwYmI0ZDJiMGVjIn0%3D; laravel_session=eyJpdiI6IlwvRytTTFJsVHRpVWRHZ21icHBHcElnPT0iLCJ2YWx1ZSI6IlNYVktOWEY5d013TVcrd3ZyYjBJOTVWWTVnYklKUUdhV0dJNTQ3QlA2c3JHc1BLWkppNlZOSFpRTlR4dzhnWEtMQXRtTkU2QkdhUk1ralRIdWdMR3pRPT0iLCJtYWMiOiIzOTAxZWEyYWNhNTcxZGI0NmEwYTM2NjM4YTY4Y2Q0ZTdiMjc1ZmU2NDIxM2QwZWM5OThhN2JkYWNlNzI5ZGJjIn0%3D'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $claimObject = json_decode($response);

        $claim_data = $claimObject->data;
        dd($claim_data);

        foreach ($claim_data as $claim){
            $claim_number = $claim->clm_no;;


        $emails = Config::CLAIM_NOTIFICATION_CONTACTS["EMAIL_TO"]["EMAIL"];
        $ccEmails = Config::CLAIM_NOTIFICATION_CONTACTS["CC_EMAIL"]["EMAIL"];


        $message = "NHIF Policy has been registered to Premia with claim number <span><strong><em>$claim_number</em></strong></span>. Kindly action.";

        $header = "<p>
                       <br/>
                       <br/>
                        <i>Dear Sirs,
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


        $msg =$header.$message.$footer;
        $message = [
            'subject' => $claim_number.'_'.$this->functions->curlDate(),
            'from' => Config::JUBILEE_NO_REPLY_EMAIL,
            'to' => 'sylvesterouma282@gmail.com',
            'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
            'cc' => "sylvester.ouma@jubileekenya.com",
            'html' => $msg,
        ];
            $email_sent=InfobipEmailHelper::sendEmail($message);
        }
            if ($email_sent){
                dump("sent successfully");
                dd($email_sent);
            }else{
                dd("email not sent. you will try again");
            }

    }
}
