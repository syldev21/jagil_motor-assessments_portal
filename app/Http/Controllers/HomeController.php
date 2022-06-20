<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\Claim;
use App\Conf\Config;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helper\GeneralFunctions;
use App\Helper\CustomLogger;
use App\Utility;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $log;
    private $functions;
    private $utility;
    public function __construct()
    {
        $this->middleware('auth');
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
        $this->utility = new Utility();
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function assessments()
    {
        $user = Auth::user();
        return view('dashboard',['user' => $user]);
    }
    public function mainDashboard()
    {
        $user = Auth::user();
        if(auth()->user()->hasRole(Config::$ROLES['NHIF']) && auth()->user()->userTypeID==Config::$USER_TYPES["EXTERNAL"]["ID"])
        {
            $view = "NHIF.index";

        }else if(auth::user()->hasRole(Config::$ROLES['CUSTOMER-SERVICE'])&& auth()->user()->userTypeID==Config::$USER_TYPES["HOME FIBER CUSTOMER"]["ID"]){
            $view='layouts.home-fibre.master';
        }else
        {
            $view = "dashboard.main";
        }
        return view($view,['user' => $user]);
    }
    public function markNotification(Request $request)
    {
        $notificationId = request('id');
        $userUnreadNotification = auth()->user()
            ->unreadNotifications
            ->where('id', $notificationId)
            ->first();
        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }
    }
    public function dashboard(Request $request)
    {
        $assessments = DB::table('assessments')
            ->selectRaw('count(*) as total')
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id']."' and segment<> '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."' then 1 end) as assigned")
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id']."' and segment<> '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."' then 1 end) as drafted")
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['ASSESSED']['id']."' and segment<> '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."' then 1 end) as assessed")
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id']."'and segment<> '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."'  then 1 end) as provisionallyApproved")
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['APPROVED']['id']."' and segment<> '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."'  then 1 end) as approved")
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id']."' and segment<> '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."'  then 1 end) as changesDue")
            ->first();

        $supplementaries = DB::table('assessments')
            ->selectRaw('count(*) as total')
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['ASSIGNED']['id']."' and segment= '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."' then 1 end) as assigned")
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id']."' and segment= '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."' then 1 end) as drafted")
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['ASSESSED']['id']."' and segment= '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."' then 1 end) as assessed")
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id']."'and segment= '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."' then 1 end) as provisionallyApproved")
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['APPROVED']['id']."' and segment= '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."' then 1 end) as approved")
            ->selectRaw("count(case when assessmentStatusID = '".Config::$STATUSES['ASSESSMENT']['CHANGES-DUE']['id']."' and segment= '".Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']."' AND active = '".Config::ACTIVE."' then 1 end) as changesDue")
            ->first();
        $numberOfClaims = Claim::where('claimStatusID', '=',Config::$STATUSES['CLAIM']['UPLOADED']['id'] )
            ->where('active',Config::ACTIVE)
            ->count();
        $numberOfArchivedClaims = Claim::where('active',Config::INACTIVE)->count();

        $flagThreshold = Carbon::now()->subDays(Config::FLAG_THRESHOLD)->toDateTimeString();
        $provisonal =Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'];
        $changesDue = Config::$STATUSES["ASSESSMENT"]["CHANGES-DUE"]["id"];
        $assigned = Config::$STATUSES["ASSESSMENT"]["ASSIGNED"]["id"];
        $draft = Config::$STATUSES["ASSESSMENT"]["IS-DRAFT"]["id"];
        $assessed= Config::$STATUSES["ASSESSMENT"]["ASSESSED"]["id"];
        $flaggedAssessments= Assessment::where('segment', "!=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
            ->where('active','=',Config::ACTIVE)
            ->whereRaw(
                ("CASE WHEN assessmentStatusID='$provisonal' THEN approvedAt <  '$flagThreshold'
             WHEN assessmentStatusID='$changesDue' THEN changeRequestAt < '$flagThreshold'
             WHEN assessmentStatusID='$assigned' THEN dateCreated < '$flagThreshold'
             WHEN assessmentStatusID='$draft' THEN assessedAt < '$flagThreshold'
             WHEN assessmentStatusID='$assessed' THEN assessedAt < '$flagThreshold'
             ELSE 0 END"))->count();
        $flaggedSupplementaries= Assessment::where('segment', "=", Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])
            ->where('active','=',Config::ACTIVE)
            ->whereRaw(
                ("CASE WHEN assessmentStatusID='$provisonal' THEN approvedAt <  '$flagThreshold'
             WHEN assessmentStatusID='$changesDue' THEN changeRequestAt < '$flagThreshold'
             WHEN assessmentStatusID='$assigned' THEN dateCreated < '$flagThreshold'
             WHEN assessmentStatusID='$draft' THEN assessedAt < '$flagThreshold'
             WHEN assessmentStatusID='$assessed' THEN assessedAt < '$flagThreshold'
             ELSE 0 END"))->count();
        return view('dashboard.index',['assessments'=>$assessments,'supplementaries'=>$supplementaries,'numberOfClaims'=>$numberOfClaims,'flaggedAssessments'=>$flaggedAssessments,'flaggedSupplementaries'=>$flaggedSupplementaries,'numberOfArchivedClaims'=>$numberOfArchivedClaims]);
    }
}
