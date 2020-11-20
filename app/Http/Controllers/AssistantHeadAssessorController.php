<?php

namespace App\Http\Controllers;

use App\Claim;
use App\Assessment;
use App\Conf\Config;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AssistantHeadAssessorController extends Controller
{
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }
    public function assessments(Request $request)
    {
        try {
            $assessmentStatusID = $request->assessmentStatusID;
            $assessments = Assessment::where("totalCost","<",Config::HEAD_ASSESSOR_THRESHOLD)
                ->join('users','users.id','=','assessments.assessedBy')
                ->where('users.userTypeID','=',Config::$USER_TYPES['INTERNAL']['ID'])
                ->select('assessments.*')
                ->orderBy('assessments.dateCreated', 'DESC')->with('claim')->with('assessor')->with('approver')->with('final_approver')->get();
            return view('assistant-head-assessor.assessments',["assessments" => $assessments,'assessmentStatusID'=>$assessmentStatusID]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
}
