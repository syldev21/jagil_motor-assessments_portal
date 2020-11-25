<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\Conf\Config;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
{
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }

    public function fetchReInspections(Request $request)
    {
        $id = Auth::id();
        $assessmentStatusIDs = array(Config::$STATUSES['ASSESSMENT']['PROVISIONAL-APPROVAL']['id'],Config::$STATUSES['ASSESSMENT']['APPROVED']['id']);

        try {
            $asmts=Assessment::whereIn('assessmentStatusID', $assessmentStatusIDs)
            ->where('segment','=',Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
            $assessments = Assessment::whereIn('assessmentStatusID', $assessmentStatusIDs)
                ->where('segment','=',Config::$ASSESSMENT_SEGMENTS['RE_INSPECTION']['ID'])
            ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
            return view('common.re-inspections', ['assessments' => $assessments, 'assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['APPROVED']['id'],'asmts'=>$asmts]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }

    public function fetchClaimsByType(Request $request)
    {
        $assessmentTypeID = $request->assessmentTypeID;
        try {
            $assessments = Assessment::where(['assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['APPROVED']['id'],'assessmentTypeID' =>$assessmentTypeID])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->get();
            return view('common.assessment-types',['assessments' => $assessments,'assessmentStatusID'=>Config::$STATUSES['ASSESSMENT']['APPROVED']['id'],'assessmentTypeID'=>$assessmentTypeID]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
}
