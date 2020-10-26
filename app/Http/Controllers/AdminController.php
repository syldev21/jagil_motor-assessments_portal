<?php

namespace App\Http\Controllers;

use App\Conf\Config;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Role;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }
    public function assignRoleForm(Request $request)
    {
        $users = User::all();
        $roles = Role::all();
        return view('admin.index',["roles" => $roles,"users" => $users]);
    }

    public function assignRole(Request $request)
    {
        try {
            if(isset($request->roles))
            {
                $user = User::find($request->userID);

                $ids = Role::whereIn('name', $request->roles)->pluck('id')->toArray();

                $user->syncRoles($ids);
            }else
            {
                $user = User::find($request->userID);

                $ids=$user->roles->pluck('id')->toArray();
                $user->roles()->detach($ids);
            }

            $response = array(
                "STATUS_CODE" => Config::SUCCESS_CODE,
                "STATUS_MESSAGE" => "Role assigned successfully"
            );
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to assign a claim. Error message " . $e->getMessage());
        }

        return json_encode($response);

    }
    public function listUsers(Request $request)
    {
        if(auth()->user()->hasRole(Config::$ROLES['HEAD-ASSESSOR']))
        {
            $users = User::role([Config::$ROLES['ASSESSOR'],Config::$ROLES['HEAD-ASSESSOR'],Config::$ROLES['ASSISTANT-HEAD'],Config::$ROLES['ASSESSMENT-MANAGER']])->get();

        }else
        {
            $users = User::with('roles')->get();
        }
        return view("admin.users",['users' =>$users]);
    }
    public function registerUserForm(Request $request)
    {
        $roles = Role::all();
        return view('admin.add-user',["roles" =>$roles]);
    }
    public function registerUser(Request $request)
    {
        try{
            if(isset($request->firstName) && isset($request->middleName) && isset($request->lastName)
            && isset($request->email) && isset($request->userType) && isset($request->MSISDN))
            {
                User::create([
                    "firstName"=>$request->firstName,
                    "middleName" => $request->middleName,
                    "lastName" => $request->lastName,
                    "email" => $request->email,
                    "name" => $request->firstName." ".$request->middleName." ".$request->lastName,
                    "userType" => $request->userType,
                    "MSISDN" => $request->MSISDN
                ]);
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Congratulations User created successfully"
                );
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid registration details"
                );
            }
        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );

            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to register user. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }
}
