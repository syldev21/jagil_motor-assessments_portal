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
        if(isset($request->roleID) && isset($request->userID))
        {
            $user =  User::find($request->userID);
            $user->assignRole([$request->roleID]);
            $response = array(
                "STATUS_CODE" => Config::SUCCESS_CODE,
                "STATUS_MESSAGE" => "Role assigned successfully"
            );
        }else
        {
            $response = array(
                "STATUS_CODE" => Config::INVALID_PAYLOAD,
                "STATUS_MESSAGE" => "Invalid payload"
            );
        }

        return json_encode($response);

    }
}
