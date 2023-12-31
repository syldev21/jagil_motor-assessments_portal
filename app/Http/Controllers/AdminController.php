<?php

namespace App\Http\Controllers;

use App\Conf\Config;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Part;
use App\Role;
use App\SalvageRegister;
use App\User;
use App\Vendor;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;

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

    public  function setStatus(Request $request)
    {
        if($request->status=='true')
        {
            $res=User::find($request->id)->update(['status'=>true]);
            if($res)
            {
                $response= response()->json(["status"=>"successful"]);
            }else{
                $response= response()->json(["status"=>"failed"]);
            }


        }else{
            $result=User::find($request->id)->update(['status'=>false]);
            if($result)
            {
                $response= response()->json(["status"=>"successful"]);
            }else{
                $response= response()->json(["status"=>"failed"]);
            }

        }

        return $response;


    }

    public function getUser(Request $request)
    {
      $res= User::find($request->id)->status;
      if($res == '1')
      {

          $response= response()->json(["status"=>'1']);
      }else{

          $response= response()->json(["status"=>'0']);
      }

      return $response;
    }


    public function assignPermission(Request $request)
    {
        try {
            if(isset($request->permissions))
            {
                $user = User::find($request->userID);
                $ids = $request->permissions;
                $user->syncPermissions($ids);
            }else
            {
                $user = User::find($request->userID);

                $ids=$user->permissions->pluck('id')->toArray();
                $user->permissions()->detach($ids);
            }

            $response = array(
                "STATUS_CODE" => Config::SUCCESS_CODE,
                "STATUS_MESSAGE" => "Permission assigned successfully"
            );
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to assign permission. Error message " . $e->getMessage());
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
        if(isset($request->id)){
            return view("admin.users",['users' =>$users,'userID'=>$request->id]);
        }
        return view("admin.users",['users' =>$users]);
    }
    public function listParts(Request $request)
    {
        $parts = Cache::remember('parts', Config::CACHE_EXPIRY_PERIOD, function () {
            return Part::select("id", "name")->get();
        });
        return view("admin.parts",['parts' =>$parts]);
    }
    public function addPart(Request $request)
    {
        $request->name;
        try {
            if(isset($request->name))
            {
                Part::create([
                    "name" =>  $request->name,
                    "dateCreated" => $this->functions->curlDate(),
                    "createdBy" => Auth::id()
                ]);
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Part added successfully"
                );
                Artisan::call("cache:clear");
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid payload"
                );
            }
        }catch (Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
        }
        return json_encode($response);
    }
    public function registerUserForm(Request $request)
    {
        $roles = Role::all();
        return view('admin.add-user',["roles" =>$roles]);
    }
    public function registerUser(Request $request)
    {
        try{
            if(isset($request->firstName) && isset($request->lastName)
            && isset($request->email) && isset($request->userType) && isset($request->MSISDN))
            {
                User::create([
                    "firstName"=>$request->firstName,
                    "middleName" => isset($request->middleName) ? $request->middleName : '',
                    "lastName" => $request->lastName,
                    "email" => $request->email,
                    "name" => $request->firstName." ".$request->middleName." ".$request->lastName,
                    "userTypeID" => $request->userType,
                    "MSISDN" => $request->MSISDN,
                    "password"=>bcrypt(Config::DEFAULT_PASSWORD)
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
    public function permissions(Request $request)
    {
        $userID=$request->userID;
        $user = User::where(['id'=>$userID])->first();
        $permissions = Permission::all();
        return view('admin.permissions',['permissions'=>$permissions,'user'=>$user]);
    }
    public function addPermission(Request $request)
    {
        try {
            if(isset($request->name))
            {
                Permission::create([
                   "name"=>$request->name,
                   "guard_name" =>"web"
                ]);
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Permission added successfully"
                );
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid Data entered"
                );
            }
        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to a permission. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }
    public function fetchVendors()
    {
        $vendors =Vendor::all();
        return view('admin.vendors',['vendors'=>$vendors]);
    }
    public function addVendorForm()
    {
        return view('admin.add-vendor');
    }

    public function addVendor(Request $request)
    {
        try {
            if(isset($request->firstName) && isset($request->lastName) && isset($request->email)
           && isset($request->MSISDN) && isset($request->idNumber) && isset($request->kraPin) && isset($request->location) && isset($request->vendorType))
            {
                $fullName = $request->firstName." ".$request->lastName;
                Vendor::create([
                    "firstName"=>$request->firstName,
                    "lastName"=>$request->lastName,
                    "fullName"=>$fullName,
                    "email"=>$request->email,
                    "MSISDN"=>$request->MSISDN,
                    "idNumber"=>$request->idNumber,
                    "kraPin"=>$request->kraPin,
                    "location"=>$request->location,
                    "type"=>$request->vendorType,
                    "status"=>Config::ACTIVE,
                    "createdBy"=>Auth::user()->id,
                    "dateCreated"=>$this->functions->curlDate()
                ]);
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Vendor successfully added"
                );
            }else
            {
                $response = array(
                    "STATUS_CODE" => Config::INVALID_PAYLOAD,
                    "STATUS_MESSAGE" => "Invalid Payload submitted"
                );
            }
        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to add a vendor. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }
    public function changeStatus(){
        return view("status");
}
}
