<?php

namespace App\Http\Controllers;

use App\Conf\Config;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\PasswordRests;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    //
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }

    public function forgot(Request $request) {
        $credentials = request()->validate(['email' => 'required|email']);
        try {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);
            $link = str_random(64);
            if ($validator->passes()) {

                PasswordRests::create([
                    'email' => $request->email,
                    'token' => $link,
                    'expiryDate' => Carbon::now(Config::TIME_ZONE)->addMinute(10),
                    'dateCreated' => $this->functions->curlDate()
                ]);
                $users = User::where(["email" => $request->email])->limit(1)->get();
                if (count($users) > 0) {
                    $data = [
                        'email' => $request->email,
                        'link' => $link
                    ];

                    $email_add = $data['email'];
                    $email = [
                        'subject' => 'Reset password',
                        'from' => Config::JUBILEE_NO_REPLY_EMAIL,
                        'to' => $email_add,
                        'replyTo' => Config::JUBILEE_NO_REPLY_EMAIL,
                        'html' => "
                    Hello,   <br>
                    Please click the link below to reset your password <br>

                    ".config('app.url')."/password/reset/" . $data['link'] . " <br><br>

                    Jubilee Insurance
                ",
                    ];
                    InfobipEmailHelper::sendEmail($email, $email_add);
                    $response = array(
                        "STATUS_CODE" => Config::SUCCESS_CODE,
                        "STATUS_MESSAGE" => "Heads up! A password reset link has been sent to your email"
                    );
                } else {
                    $response = array(
                        "STATUS_CODE" => Config::NO_RECORDS_FOUND,
                        "STATUS_MESSAGE" => "User with the provided email does not exit"
                    );
                }
            } else {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid Email address"
                );
            }
        } catch (\Exception $e) {

            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE.$e->getMessage()
            );

            $this->log->motorAssessmentErrorLogger->error("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to send a password reset email. Error message " . $e->getMessage());
        }

        return json_encode($response);
    }

    public function reset(Request $request) {
        $curl = $this->functions->curlDate();
        $validator = Validator::make($request->all(), [
//            'email' => 'required|email',
//            'token' => 'required|string',
//            'password' => 'required|string|confirmed|min:6',
//            'password_confirmation' => 'required|min:6'
        ]);

        if ($validator->passes()) {
            $passwordRests = PasswordRests::where(["email" => $request->email,"token" => $request->token])->limit(1)->get();
            if(count($passwordRests) > 0)
            {
                $users = User::where(["email" => $request->email])->limit(1)->get();
                if(count($users) >0)
                {
                    User::where(["email" => $request->email])->update([
                        "password"=> bcrypt($request->password),
                        "dateModified" => $curl
                    ]);
                    $response = array(
                        "STATUS_CODE" => Config::SUCCESS_CODE,
                        "STATUS_MESSAGE" => "Heads up! Your password has been reset successfully"
                    );
                }else
                {
                    $response = array(
                        "STATUS_CODE" => Config::NO_RECORDS_FOUND,
                        "STATUS_MESSAGE" => "User with the provided email does not exist"
                    );
                }
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::NO_RECORDS_FOUND,
                    "STATUS_MESSAGE" => "Invalid token"
                );
            }
        }else
        {
            $response = array(
                "STATUS_CODE" => Config::INVALID_PAYLOAD,
                "STATUS_MESSAGE" => "Invalid Email address"
            );
        }

        return json_encode($response);
    }

    public function verifyEmail(Request $request)
    {
        return view('authentication.user-forget-password');
    }
    public function resetPage(Request $request, $id)
    {
        $data = array(
            "id" => $id
        );
        return view('authentication.user-reset-password',$data);
    }

}
