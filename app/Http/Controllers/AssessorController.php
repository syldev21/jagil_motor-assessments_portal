<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\AssessmentItem;
use App\CarMake;
use App\CarModel;
use App\Conf\Config;
use App\Document;
use App\Garage;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\JobDetail;
use App\Part;
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
            $assessments = Assessment::orderBy('dateCreated', 'DESC')->with('claim')->get();
            return view('assessor.assessments',["assessments" => $assessments]);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }

    public function fillAssessmentReport(Request $request,$assessmentID)
    {
        $draftAssessment = Assessment::where(['id' => $assessmentID,'assessmentStatusID'=>Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id']])->with('claim')->first();
        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
        $carModels = CarModel::all();
        $remarks = Remarks::all();
        $parts = Part::all();
        $assessmentItems = AssessmentItem::where(["assessmentID"=> isset($draftAssessment->id) ? $draftAssessment->id : 0])->with("part")->get();
        $jobDetails = JobDetail::where(["assessmentID"=> isset($draftAssessment->id) ? $draftAssessment->id : 0])->get();
        echo json_encode($jobDetails);
        exit();
        return view('assessor.assessment-report',['assessment' => $assessment,'carModels' => $carModels,'remarks' => $remarks,'parts'=>$parts,'assessmentItems'=> $assessmentItems,"jobDetails"=>$jobDetails,"draftAssessment"=>$draftAssessment]);
    }
    public function fillReInspectionReport(Request $request,$assessmentID)
    {
        $assessments = Assessment::where(['id' => $assessmentID])->with('claim')->first();
        $assessmentItems = AssessmentItem::where(["assessmentID"=> $assessmentID])->with("part")->get();
        return view('assessor.re-inspection-report',['assessments' => $assessments,'assessmentItems'=>$assessmentItems]);
    }
    public function submitAssessment(Request $request)
    {
        try {
            $totalImages = $request->totalImages;
            $assessmentID = $request->assessmentID;
            $partsData = json_decode($request->partsData, true);
            $jobsData = json_decode($request->jobsData, true);
            $isDraft = $request->isDraft;
            $assessmentType = $request->assessmentType;
            $curDate = $this->functions->curlDate();
            //Loop for getting files with index like image0, image1
            for ($x = 0; $x < $totalImages; $x++) {

                if ($request->hasFile('images' . $x)) {
                    $file = $request->file('images' . $x);
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $path = $file->getRealPath();
                    $size = $file->getSize();
                    $picture = date('His') . '-' . $filename;
                    //Save files in below folder path, that will make in public folder
                    $file->move(public_path('documents/'), $picture);
                    Document::create([
                        "assessmentID" => $assessmentID,
                        "name" => $picture,
                        "mime" => $extension,
                        "size" => $size,
                        "documentType" => Config::$DOCUMENT_TYPES["IMAGE"]["ID"],
                        "url" => $path,
                        "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"]
                    ]);
                }
            }
            $assessmentItems = array();
            $total = !empty($jobsData['total']) ? $jobsData['total'] : 0;
            $labour = !empty($jobsData['labour']) ? $jobsData['labour'] : 0;
            $paint = !empty($jobsData['paint']) ? $jobsData['paint'] : 0;
            $miscellaneous = !empty($jobsData['miscellaneous']) ? $jobsData['miscellaneous'] : 0;
            $primer = !empty($jobsData['primer']) ? $jobsData['primer'] : 0;
            $jigging = !empty($jobsData['jigging']) ? $jobsData['jigging'] : 0;
            $reconstruction = !empty($jobsData['reconstruction'])? $jobsData['reconstruction'] : 0;
            $gas = !empty($jobsData['gas'])? $jobsData['gas'] : 0;
            $welding = !empty($jobsData['welding']) ? $jobsData['welding'] : 0;
            $dam = !empty($jobsData['dam']) ? $jobsData['dam'] : 0;
            $bumper = !empty($jobsData['bumper']) ? $jobsData['bumper'] : 0;
            $sumTotal = !empty($jobsData['sumTotal']) ? $jobsData['sumTotal'] : 0;
            $pav = !empty($jobsData['pav']) ? $jobsData['pav'] : 0;
            $salvage = !empty($jobsData['salvage']) ? $jobsData['salvage'] : 0;
            $totalLoss = !empty($jobsData['totalLoss']) ? $jobsData['totalLoss'] : 0;
            $cause = !empty($jobsData['cause']) ? $jobsData['cause'] : null;
            $note = !empty($jobsData['note']) ? $jobsData['note'] : null;
                foreach ($partsData as $partDetail)
                {
                    $part = $partDetail['vehiclePart'];
                    $quantity = $partDetail['quantity'];
                    $total = $partDetail['total'];
                    $cost = $partDetail['cost'];
                    $contribution = $partDetail['contribution'];
                    $discount = $partDetail['discount'];
                    $remarks = $partDetail['remarks'];
                    $category = $partDetail['category'];
                    $assessmentItem = array(
                        "assessmentID" => $assessmentID,
                        "partID" => $part,
                        "quantity" => $quantity,
                        "contribution" => $contribution,
                        "discount" => $discount,
                        "cost" => $cost,
                        "total" => $total,
                        "remarks" => $remarks,
                        "assessmentItemType" => $assessmentType,
                        "category" => $category,
                        "segment" => Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                        "createdBy" => Config::ACTIVE,
                        "dateCreated" => $curDate
                    );
                    $assessmentItems[] = $assessmentItem;
                }

                $collection = collect($assessmentItems);

                $unique = $collection->unique('partID');

                $save = AssessmentItem::insert($unique->values()->all());
                if ($save) {
                    //Sum of parts
                    $sum = AssessmentItem::where('assessmentID', $assessmentID)->sum('total');

                    //Sum of other charges
                    $others = (is_numeric($labour)) + (is_numeric($paint)) + (is_numeric($miscellaneous)) + (is_numeric($primer))
                        + (is_numeric($jigging)) + (is_numeric($reconstruction)) + (is_numeric($gas)) + (is_numeric($welding))
                        + (is_numeric($bumper)) + (is_numeric($dam));

                    if ($assessmentType == 1) {
                        $total = ($sum + $others) * 1.14;
                    } elseif ($assessmentType == 2) {
                        $total = ($sum * 0.9) + $others;
                    } elseif ($assessmentType == 3) {
                        $total = ($sum + $others) * 1.14;
                    }
                    $assessmentStatusID = $isDraft == 1 ? Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'] : Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'];
                    Assessment::where(['id' => $assessmentID])->update([
                        "cause" => $cause,
                        "note" => $note,
                        "salvage" => $salvage,
                        "pav" => $pav,
                        "totalCost" => $total,
                        "totalLoss" => $totalLoss,
                        "assessmentTypeID" =>Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                        "assessmentStatusID" => $assessmentStatusID
                    ]);
                    $detail = JobDetail::where('assessmentID', $assessmentID)->exists();
                    $jobs = array();

                    if ($detail) {

                    } else {
                        if($labour> 0)
                        {
                            $job = array(
                                "assessmentID" => $assessmentID,
                                "name" => Config::$JOB_TYPES["LABOUR"]["TITLE"],
                                "jobType" => Config::$JOB_TYPES["LABOUR"]["ID"],
                                "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                                "cost" => $labour
                            );
                            $jobs[] = $job;
                        }
                        if($paint> 0)
                        {
                            $job = array(
                                "assessmentID" => $assessmentID,
                                "name" => Config::$JOB_TYPES["PAINTING"]["TITLE"],
                                "jobType" => Config::$JOB_TYPES["PAINTING"]["ID"],
                                "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                                "cost" => $paint
                            );
                            $jobs[] = $job;
                        }
                        if($miscellaneous> 0)
                        {
                            $job = array(
                                "assessmentID" => $assessmentID,
                                "name" => Config::$JOB_TYPES["MISCELLANEOUS"]["TITLE"],
                                "jobType" => Config::$JOB_TYPES["MISCELLANEOUS"]["ID"],
                                "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                                "cost" => $miscellaneous
                            );
                            $jobs[] = $job;
                        }
                        if($primer> 0)
                        {
                            $job = array(
                                "assessmentID" => $assessmentID,
                                "name" => Config::$JOB_TYPES["PRIMER"]["TITLE"],
                                "jobType" => Config::$JOB_TYPES["PRIMER"]["ID"],
                                "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                                "cost" => $primer
                            );
                            $jobs[] = $job;
                        }
                        if($jigging> 0)
                        {
                            $job = array(
                                "assessmentID" => $assessmentID,
                                "name" => Config::$JOB_TYPES["JIGGING"]["TITLE"],
                                "jobType" => Config::$JOB_TYPES["JIGGING"]["ID"],
                                "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                                "cost" => $jigging
                            );
                            $jobs[] = $job;
                        }
                        if($reconstruction> 0)
                        {
                            $job = array(
                                "assessmentID" => $assessmentID,
                                "name" => Config::$JOB_TYPES["RECONSTRUCTION"]["TITLE"],
                                "jobType" => Config::$JOB_TYPES["RECONSTRUCTION"]["ID"],
                                "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                                "cost" => $reconstruction
                            );
                            $jobs[] = $job;
                        }
                        if($gas> 0)
                        {
                            $job = array(
                                "assessmentID" => $assessmentID,
                                "name" => Config::$JOB_TYPES["AC_GAS"]["TITLE"],
                                "jobType" => Config::$JOB_TYPES["AC_GAS"]["ID"],
                                "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                                "cost" => $gas
                            );
                            $jobs[] = $job;
                        }
                        if($welding> 0)
                        {
                            $job = array(
                                "assessmentID" => $assessmentID,
                                "name" => Config::$JOB_TYPES["WELDING_GAS"]["TITLE"],
                                "jobType" => Config::$JOB_TYPES["WELDING_GAS"]["ID"],
                                "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                                "cost" => $welding
                            );
                            $jobs[] = $job;
                        }
                        if($bumper> 0)
                        {
                            $job = array(
                                "assessmentID" => $assessmentID,
                                "name" => Config::$JOB_TYPES["BUMPER_FIBRE"]["TITLE"],
                                "jobType" => Config::$JOB_TYPES["BUMPER_FIBRE"]["ID"],
                                "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                                "cost" => $bumper
                            );
                            $jobs[] = $job;
                        }
                        if($dam> 0)
                        {
                            $job = array(
                                "assessmentID" => $assessmentID,
                                "name" => Config::$JOB_TYPES["DAM_KIT"]["TITLE"],
                                "jobType" => Config::$JOB_TYPES["DAM_KIT"]["ID"],
                                "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                                "cost" => $dam
                            );
                            $jobs[] = $job;
                        }
                        foreach ($jobs as $job)
                        {
                            JobDetail::create($job);
                        }
                    }
                }
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to create an assessments. Error message " . $e->getMessage());
        }
    }
}
