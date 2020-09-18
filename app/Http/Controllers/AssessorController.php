<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\CarMake;
use App\CarModel;
use App\Garage;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Remarks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessorController extends Controller
{
    //
    private $log;
    private $functions;

    function __construct()
    {
        $this->log = new CustomLogger();
        $this->functions = new GeneralFunctions();
    }
    public function fetchAssessments(Request $request)
    {
        $id = Auth::id();
        try {
            $assessments = Assessment::with('claim')->get();
            return view('assessor.assessments',["assessments" => $assessments]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }

    public function fillAssessmentReport(Request $request,$assessmentID)
    {
        $assessments = Assessment::where(['id' => $assessmentID])->with('claim')->first();
        $carModels = CarModel::all();
        $remarks = Remarks::all();
        return view('assessor.assessment-report',['assessments' => $assessments,'carModels' => $carModels,'remarks' => $remarks]);
    }

}
