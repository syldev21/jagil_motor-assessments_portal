<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\AssessmentItem;
use App\CustomerMaster;
use App\Document;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\JobDetail;
use App\User;
use Illuminate\Http\Request;

class AssessmentManagerController extends Controller
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
        $assessmentStatusID = $request->assessmentStatusID;
        try {
            $assessments = Assessment::where(["assessmentStatusID"=>$assessmentStatusID])->orderBy('dateCreated', 'DESC')->with('claim')->get();
            return view('assessment-manager.assessments', ["assessments" => $assessments,'assessmentStatusID'=>$assessmentStatusID]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }

    }

    public function assessmentReport(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $assessment = Assessment::where(["id" => $assessmentID])->with("claim")->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID])->with('part')->get();
        $jobDetails = JobDetail::where(["assessmentID" => $assessmentID])->get();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured= CustomerMaster::where(["customerCode" => $customerCode])->first();
        $documents = Document::where(["assessmentID" => $assessmentID])->get();
        $adjuster = User::where(['id'=> $assessment->claim->createdBy])->first();
        $assessor = User::where(['id'=> $assessment->assessedBy])->first();
        return view("assessment-manager.assessment-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor]);
    }
}
