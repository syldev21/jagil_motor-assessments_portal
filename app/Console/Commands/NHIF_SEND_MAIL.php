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
//                'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImVjMWJmZGFjMzE3ZTA1NTE1YmNmYmVkZGIzMjFiYmI3NjFkZWFkZWM1ZjEyOWUyM2U3MTYwYzYxMWU2NDQ1Yjg1MDVhOGRhYjcxMWM4N2QwIn0.eyJhdWQiOiIyIiwianRpIjoiZWMxYmZkYWMzMTdlMDU1MTViY2ZiZWRkYjMyMWJiYjc2MWRlYWRlYzVmMTI5ZTIzZTcxNjBjNjExZTY0NDViODUwNWE4ZGFiNzExYzg3ZDAiLCJpYXQiOjE2NTYyOTA2OTIsIm5iZiI6MTY1NjI5MDY5MiwiZXhwIjoxNjU2Mjk0MjkyLCJzdWIiOiI4MjU0Iiwic2NvcGVzIjpbXX0.A3DG54sQnmUIe-ljcD9yRgRLUGS-SeaefOqOlnb5wix2-XV9GYB-KcyrrPQHBaE9gAnGk8tW_JT7mm0xtjg-Dg5LeEvfOwcY_n1TPTtGDQtIjf5rXve6pVaEPMX9kqQQhizfHpZNpwu4XCLR0Z52XXRgxqooUTjA-oiyJzRWSrQrYSmsIc06ADY_D4mYri5J_WERu2QGnjiHqVR_xETeIp8qLhE9RzAKaWcvV0BJQzHt36jdSeU9Go3ky0AonzI0AgeiH5ohqHoXR7gCAew0M_dtt6gfphHmCJ4ijiUKdMWohhQcq7Euv-de4mzAIfQ6qTvZhX2q3Yjwh_PBb-voAadjRtia5H32vN5KQP02wDfZgazWIu383enF6pGquRh-t67lHUXDBdJT0a__NBgdU4XxAeA49z0Q0VLqz8jobwfnjNxx4VMuZbgROe3K1CiBMCcG2utpqarYVaCH94UljZNayLsIrkiLHPVOuGfhtPTt29U14FErhJjfbRajORxHy_EH_fpS6AztkLz7CHpamyn21OCqfBjQz8zXQH6N7zBSq5peEuLL66wXIjU9_O5VaT2gWnX3wpqibSzadiJ-Wv7oa-qL1WqZ8BSLiD1hhpovsNSq7NktCAj4XIM0w8s1FZS_EUqhqinX7hO6G0AzGXBFq_Zrpon3t-4JUXG6iXQ',
                'Cookie: XSRF-TOKEN=eyJpdiI6IitPYjN6U0dVSWJNSmxoaVNrdkRENUE9PSIsInZhbHVlIjoiMWV6bVdtT0ZMenpzeFN2R0xQRGZzeDMwbFpBOW1UcHZcL0V4R0tvQUtLUXpwOE1VY1pIcVdVeTBhUWRncTFWSUFkZ3laSjlEcndsQTZEYlpZdzJDVlRBPT0iLCJtYWMiOiI1YjVjYmI2M2NmNGZjMDk3ZmVkOTY5N2E1YTJlM2I2NjMwNWY3ZmI0ZGNhZjRjMmM5YzRlZmZlNzE4ZjA4NTQxIn0%3D; laravel_session=eyJpdiI6IjNuTkhVbFdzMWNtUHNNMUNoaFpaZ1E9PSIsInZhbHVlIjoiNUxoaTdNMnM4anFFYkxBZXJKWU90bzByV0ZzSnlTK05pbGxRblozeHk4VEpGYnpNUVZRU3Y4cmNvNnR3cmlaS2NHeUNmODFWSzU4aHNlYmdhNktZbUE9PSIsIm1hYyI6IjE2MTU0ZTBjNzVmZDM2M2I0MjZmMTM5OGU4ODFhZGJhOTg0NGI3NTFkYTZhNDA2MDI5YmZkNjUxMzcxYTA5OWYifQ%3D%3D'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $claimObject = json_decode($response);

        $claim_data = $claimObject->data;

        if (!empty($claim_data)){
            foreach ($claim_data as $claim){
                $claim_number = $claim->clm_no;
                $id = $claim->id;
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
                if ($email_sent){
                    dump("sent successfully");
                    ClaimMock::where("id", "=", $id)->update(["claimNo"=>$claim_number]);
                    $data=$id;

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://digitalappsuat.jubileekenya.com/api/v1/b2b/general/claim/p11-update-flag',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => array('id' => $data),
                        CURLOPT_HTTPHEADER => array(
//                        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImNiYmVjYjUyYWIzZWVhYTUwMWJhYzQyNWE2ZjE5ODI5MGYxZDZjMDJiZWUwZDhiMTdiMDRjOWQ4NDYxYTViZDkyNDhjYTU0Nzc3NDFiZTRhIn0.eyJhdWQiOiIyIiwianRpIjoiY2JiZWNiNTJhYjNlZWFhNTAxYmFjNDI1YTZmMTk4MjkwZjFkNmMwMmJlZTBkOGIxN2IwNGM5ZDg0NjFhNWJkOTI0OGNhNTQ3Nzc0MWJlNGEiLCJpYXQiOjE2NTYzMTgzNTAsIm5iZiI6MTY1NjMxODM1MCwiZXhwIjoxNjU2MzIxOTUwLCJzdWIiOiI4MjU0Iiwic2NvcGVzIjpbXX0.FllJvhr_bq9fK_Q_dXD9OHNmK7e5ZFXUqWlEb4tiIlJAdw9KnKjCMOskbTGySDTuAEy_gPIhRKXXdG5iDdjCHiTSwLOS1U9BK4IjbGTm0lGkez9xDTNL8h4YnTWSlSUU2FHJ5Z8vctnSuHpyBfYBUs-AgbZocaZDXBH7Pe67RURHoIkG_cBicjA1z_nUKU3zs5tbv_IrXqnn3Z8N7pAEjBLQ9w4eIL0ZrbdOsVDH-MiapE2op3C90Tm8V9pMWuYgWcIGoMoZmvQCLx6iDsbOjt3kvDxVmKz42Uh12GHknhfnInYtmM9CqAShaOdKRm7OCuybjSDS_iFeeB4fcCSLxkMYSQy7aOVwwNNDvVZsxjAA-zFiKLhsZRccm8xesGfjP6sTp78ialL8nGb-rqOqj1M-CEdJzns6N5jrqvGlhHf2RK37xLB5nuENh-pP_rVb27dhGI852Foq51pGJVM9r4gYQkaWIzfcQWZ0e4IB2hDTtViyeSlX6Mj4fZljdsKs9aqpw_4MmyPKoG0TGqUxmFSFveOE4WbWv4IO8aIgMyOj8NPYmoMnI2O9l5NphV8Apqq2GlceMH2mFKRcIRmSykPfKenhlWFgJ-L6KQfU3wbiKSMOOT7PsttmZ403sC04mWE6JwmybKSkTN-BGirZHPWoGYyTvfx-o0AAbStcIWM',
                            'Authorization: Bearer '. $access_token,
                            'Cookie: XSRF-TOKEN=eyJpdiI6InVOdGpKMldqdkNGMnJHS3FzWGZvUkE9PSIsInZhbHVlIjoiMDB2MThSSXpZV081cE05OTNRSk1ubE9rSEU3WEh0dXJFbkc0Znd1XC9tVERSa2ZjTVpXQVwvRDh5a05nTUZFajBVZjJVXC9QUjdhYjNlcXZYTmpqQnVSZkE9PSIsIm1hYyI6IjU1NjJjZmIwYmU1YjgyZTc1ODc3NDVjODAxYTFlNThiODhiMDVjMTdkN2M0MWY3YWQ5MTdmMDk0ZTY1YWRjNTMifQ%3D%3D; laravel_session=eyJpdiI6IlhlcmZzTENKV1BkVGVlREpFRzZncUE9PSIsInZhbHVlIjoiMXUrTURzbnRMeEFsdjdCKytadzRpbE5cL1NGbmFwaG1XdmpyZFhmYVpFM0NKWldHOThoaFwvdW82OFJ3dVNBU3NIbmRQckt4RnR5YllsUVV4UzlsekpFdz09IiwibWFjIjoiZjM1YWM2MzAzMDNmOTJjYzRiMTc3Y2UyM2U3MjQ1OWZmNzNkNmYzNjkxMGU3M2FkZDEzZTBmM2VkYzE3ODBkYiJ9'
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    echo $response;


                }else{
                    dump("email not sent. you will try again");
                }
            }

        }else{
            dump("no new claim is created");
        }

    }
}
