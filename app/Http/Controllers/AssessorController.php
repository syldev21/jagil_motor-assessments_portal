<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\AssessmentItem;
use App\CarModel;
use App\Claim;
use App\Conf\Config;
use App\CustomerMaster;
use App\Document;
use App\Garage;
use App\Helper\CustomLogger;
use App\Helper\GeneralFunctions;
use App\Helper\InfobipEmailHelper;
use App\Helper\SMSHelper;
use App\JobDetail;
use App\Notifications\NewAssessmentNotification;
use App\Notifications\NewClaimNotification;
use App\Part;
use App\PriceChange;
use App\ReInspection;
use App\Remarks;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;

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

    public function assessments(Request $request)
    {
        $id = Auth::id();
        $assessmentStatusID = $request->assessmentStatusID;
        try {
            $segmentIds = array(Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],Config::$ASSESSMENT_SEGMENTS['RE_INSPECTION']['ID']);
            $asmts=Assessment::where(['assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'], "assessedBy" => Auth::id(),'segment'=>5])->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
            $assessments = Assessment::where(['assessmentStatusID' => $assessmentStatusID, "assessedBy" => $id])
                ->whereIn('segment', $segmentIds)
                ->with('claim')->with('user')->with('approver')->with('final_approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
            return view('assessor.assessments', ['assessments' => $assessments, 'assessmentStatusID' => $assessmentStatusID,'asmts'=>$asmts]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
    public function supplementaries(Request $request)
    {
        $id = Auth::id();
        $assessmentStatusID = $request->assessmentStatusID;
        try {
            $assessments = Assessment::where(['assessmentStatusID' => $assessmentStatusID, "assessedBy" => $id,'segment'=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->with('claim')->with('user')->with('approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
            return view('assessor.supplementaries', ['assessments' => $assessments, 'assessmentStatusID' => $assessmentStatusID,'assessmentStatusID'=>$assessmentStatusID,'id'=>$id]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }
    public function fillAssessmentReport(Request $request, $assessmentID)
    {
//        $draftAssessment = Assessment::where(['id' => $assessmentID, 'assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id']])->with('claim')->first();
        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
        $drafted = isset($assessment->assessmentStatusID) ? $assessment->assessmentStatusID : 0;
        if($drafted == Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'])
        {
            $draftAssessment = $assessment;
        }else
        {
            $draftAssessment = array();
        }
        $carDetails = CarModel::where(["modelCode" => isset($assessment->claim->carModelCode) ? $assessment->claim->carModelCode : 0])->first();
//        $remarks = Remarks::select("id","name")->get();
//        $parts = Part::select("id","name")->get();
        $remarks = Cache::remember('remarks',Config::CACHE_EXPIRY_PERIOD,function (){
            return Remarks::select("id","name")->get();
        });
        $parts = Cache::remember('parts',Config::CACHE_EXPIRY_PERIOD,function (){
            return Part::select("id","name")->get();
        });
        $claim = Claim::where(['id' => $request->claimID])->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => isset($draftAssessment->id) ? $draftAssessment->id : 0])->with("part")->get();
        $jobDetails = JobDetail::where(["assessmentID" => isset($draftAssessment->id) ? $draftAssessment->id : 0])->get();
        $jobDraftDetail = [];
        foreach ($jobDetails as $jobDetail) {

            if ($jobDetail->jobType == Config::$JOB_TYPES["LABOUR"]["ID"]) {
                $jobDraftDetail["Labour"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["PAINTING"]["ID"]) {
                $jobDraftDetail["Painting"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["MISCELLANEOUS"]["ID"]) {
                $jobDraftDetail["Miscellaneous"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["PRIMER"]["ID"]) {
                $jobDraftDetail["2k Primer"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["JIGGING"]["ID"]) {
                $jobDraftDetail["Jigging"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["RECONSTRUCTION"]["ID"]) {
                $jobDraftDetail["Reconstruction"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["AC_GAS"]["ID"]) {
                $jobDraftDetail["AC/Gas"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["WELDING_GAS"]["ID"]) {
                $jobDraftDetail["Welding/Gas"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["BUMPER_FIBRE"]["ID"]) {
                $jobDraftDetail["Bumper Fibre"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["DAM_KIT"]["ID"]) {
                $jobDraftDetail["Dam Kit"] = $jobDetail->cost;
            }
        }
        return view('assessor.assessment-report', ['assessment' => $assessment, 'remarks' => $remarks, 'parts' => $parts, 'assessmentItems' => $assessmentItems, "jobDraftDetail" => $jobDraftDetail, "draftAssessment" => $draftAssessment, "carDetails" => $carDetails,'claim'=>$claim]);
    }
    public function fillSupplementaryReport(Request $request, $assessmentID)
    {
        $supplementaryAssessment = Assessment::where(['id' => $assessmentID])->first();
        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
        $carDetails = CarModel::where(["modelCode" => isset($assessment->claim->carModelCode) ? $assessment->claim->carModelCode : 0])->first();
//        $remarks = Remarks::all();
//        $parts = Part::all();
        $remarks = Cache::remember('remarks',Config::CACHE_EXPIRY_PERIOD,function (){
            return Remarks::select("id","name")->get();
        });
        $parts = Cache::remember('parts',Config::CACHE_EXPIRY_PERIOD,function (){
            return Part::select("id","name")->get();
        });
        $assessmentItems = AssessmentItem::where(["assessmentID" => isset($supplementaryAssessment->id) ? $supplementaryAssessment->id : 0,'segment'=> Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->with("part")->get();
        $jobDetails = JobDetail::where(["assessmentID" => isset($supplementaryAssessment->id) ? $supplementaryAssessment->id : 0,"segment"=> Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->get();
        $jobDraftDetail = [];
        foreach ($jobDetails as $jobDetail) {

            if ($jobDetail->jobType == Config::$JOB_TYPES["LABOUR"]["ID"]) {
                $jobDraftDetail["Labour"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["PAINTING"]["ID"]) {
                $jobDraftDetail["Painting"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["MISCELLANEOUS"]["ID"]) {
                $jobDraftDetail["Miscellaneous"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["PRIMER"]["ID"]) {
                $jobDraftDetail["2k Primer"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["JIGGING"]["ID"]) {
                $jobDraftDetail["Jigging"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["RECONSTRUCTION"]["ID"]) {
                $jobDraftDetail["Reconstruction"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["AC_GAS"]["ID"]) {
                $jobDraftDetail["AC/Gas"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["WELDING_GAS"]["ID"]) {
                $jobDraftDetail["Welding/Gas"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["BUMPER_FIBRE"]["ID"]) {
                $jobDraftDetail["Bumper Fibre"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["DAM_KIT"]["ID"]) {
                $jobDraftDetail["Dam Kit"] = $jobDetail->cost;
            }
        }

        return view('assessor.supplementary-report', ['assessment' => $assessment, 'remarks' => $remarks, 'parts' => $parts, 'assessmentItems' => $assessmentItems, "jobDraftDetail" => $jobDraftDetail, "supplementaryAssessment" => $supplementaryAssessment, "carDetails" => $carDetails]);
    }

    public function fillReInspectionReport(Request $request, $assessmentID)
    {
        $assessments = Assessment::where(['id' => $assessmentID])->with('claim')->with('reInspection')->first();
        $inspections = ReInspection::where(['assessmentID'=>isset($assessments) ? $assessments->id : 0])->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID])->with("part")->get();
        return view('assessor.re-inspection-report', ['assessments' => $assessments, 'assessmentItems' => $assessmentItems,'inspections'=>$inspections]);
    }

    public function uploadDocuments(Request $request)
    {
        try {
            $totalImages = $request->totalImages;
            $claimID = $request->claimID;
            //Loop for getting files with index like image0, image1
            if ($request->hasFile('claimForm')) {
                $claim='claim';
                $pdfs = Document::where(['claimID' => $claimID, 'documentType' => Config::$DOCUMENT_TYPES["PDF"]["ID"]])->where('name','like','%' .$claim. '%')->get();
                if (count($pdfs) > 0) {
                    $affectedPdfRows = Document::where(['claimID' => $claimID, 'documentType' => Config::$DOCUMENT_TYPES["PDF"]["ID"]])->where('name','like','%' .$claim. '%')->delete();
                    if ($affectedPdfRows > 0) {
                        foreach ($pdfs as $pdf) {
                            $image_path = "documents/" . $pdf->name;  // Value is not URL but directory file path
                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }
                        }
                    }
                }
                $file = $request->file('claimForm');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $path = $file->getRealPath();
                $size = $file->getSize();
                $picture = date('His') . '-' . 'claim'.'-'. $filename;
                //Save files in below folder path, that will make in public folder
                $file->move(public_path('documents/'), $picture);
                $documents = Document::create([
                    "claimID" => $claimID,
                    "name" => $picture,
                    "mime" => $extension,
                    "size" => $size,
                    "documentType" => $documentType = Config::$DOCUMENT_TYPES["PDF"]["ID"],
                    "url" => $path,
                    "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"]
                ]);
                if($totalImages == 0)
                {
                    $response = array(
                        "STATUS_CODE" => Config::SUCCESS_CODE,
                        "STATUS_MESSAGE" => "Congratulations! Your documents has been uploaded successfully"
                    );
                    return json_encode($response);
                }
            }
            $documentsArray = [];
            if ($totalImages > 0) {
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
                        $documents = array(
                            "claimID" => $claimID,
                            "name" => $picture,
                            "mime" => $extension,
                            "size" => $size,
                            "documentType" => Config::$DOCUMENT_TYPES["IMAGE"]["ID"],
                            "url" => $path,
                            "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"]
                        );
                        $documentsArray[] = $documents;
                    }
                }
                $collection = collect($documentsArray);
                $save = Document::insert($collection->values()->all());
                if ($save) {
                    $response = array(
                        "STATUS_CODE" => Config::SUCCESS_CODE,
                        "STATUS_MESSAGE" => "Congratulations! Your documents has been uploaded successfully"
                    );
                } else {
                    $response = array(
                        "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                        "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                    );
                }
            } else {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "Documents not uploaded an error. An error occurred " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function submitAssessment(Request $request)
    {
        try {
            $claimID=$request->claimID;
            $totalImages = $request->totalImages;
            $assessmentID = $request->assessmentID;
            $partsData = json_decode($request->partsData, true);
            $jobsData = json_decode($request->jobsData, true);
            $isDraft = $request->isDraft;
            $assessmentType = $request->assessmentType;
            $curDate = $this->functions->curlDate();
            $drafted = $request->drafted;
            $invoice='invoice';
            if ($drafted == 1) {
                $affectedRows = AssessmentItem::where(["assessmentID" => $assessmentID])->delete();
                if ($affectedRows > 0) {
                    $affectedJobDetailRows = JobDetail::where(["assessmentID" => $assessmentID])->delete();
                    if ($affectedJobDetailRows > 0) {
                        $documents = Document::where(["assessmentID" => $assessmentID])->get();
                        if (count($documents) > 0) {
                            $affectedDocumentRows = Document::where(["assessmentID" => $assessmentID])->delete();
                            if ($affectedDocumentRows > 0) {
                                foreach ($documents as $document) {
                                    $image_path = "documents/" . $document->name;  // Value is not URL but directory file path
                                    if (File::exists($image_path)) {
                                        File::delete($image_path);
                                    }
                                }
                            }
                        }
                    }
                }

            }

            if ($request->hasFile('claimForm')) {
                $pdfs = Document::where(['claimID' => $claimID, 'documentType' => Config::$DOCUMENT_TYPES["PDF"]["ID"]])->where('name','like','%' .$invoice. '%')->get();
                if (count($pdfs) > 0) {
                    $affectedPdfRows = Document::where(['claimID' => $claimID, 'documentType' => Config::$DOCUMENT_TYPES["PDF"]["ID"]])->where('name','like','%' .$invoice. '%')->delete();
                    if ($affectedPdfRows > 0) {
                        foreach ($pdfs as $pdf) {
                            $image_path = "documents/" . $pdf->name;  // Value is not URL but directory file path
                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }
                        }
                    }
                }
                $file = $request->file('claimForm');
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $path = $file->getRealPath();
                $size = $file->getSize();
                $picture = date('His') . '-'. 'invoice'.'-'. $filename;
                //Save files in below folder path, that will make in public folder
                $file->move(public_path('documents/'), $picture);
                $documents = Document::create([
                    "claimID" => $claimID,
                    "name" => $picture,
                    "mime" => $extension,
                    "size" => $size,
                    "documentType" => $documentType = Config::$DOCUMENT_TYPES["PDF"]["ID"],
                    "url" => $path,
                    "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"]
                ]);

            }
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
            $reconstruction = !empty($jobsData['reconstruction']) ? $jobsData['reconstruction'] : 0;
            $gas = !empty($jobsData['gas']) ? $jobsData['gas'] : 0;
            $welding = !empty($jobsData['welding']) ? $jobsData['welding'] : 0;
            $sumTotal = !empty($jobsData['sumTotal']) ? $jobsData['sumTotal'] : 0;
            $pav = !empty($jobsData['pav']) ? $jobsData['pav'] : 0;
            $salvage = !empty($jobsData['salvage']) ? $jobsData['salvage'] : 0;
            $totalLoss = !empty($jobsData['totalLoss']) ? $jobsData['totalLoss'] : 0;
            $cause = !empty($jobsData['cause']) ? $jobsData['cause'] : null;
            $note = !empty($jobsData['note']) ? $jobsData['note'] : null;
            $chassisNumber = !empty($jobsData['chassisNumber']) ? $jobsData['chassisNumber'] : '';
            if($chassisNumber != '')
            {
                $assessment= Assessment::where(['id' =>$assessmentID])->first();
                Claim::where(['id'=>$assessment->claimID])->update([
                    "chassisNumber"=>$chassisNumber
                ]);
            }
            foreach ($partsData as $partDetail) {
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
                    + (is_numeric($jigging)) + (is_numeric($reconstruction)) + (is_numeric($gas)) + (is_numeric($welding));

                if ($assessmentType == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                    $total = ($sum + $others) * 1.14;
                } elseif ($assessmentType == Config::ASSESSMENT_TYPES['CASH_IN_LIEU']) {
                    $total = ($sum * 0.9) + $others;
                } elseif ($assessmentType == Config::ASSESSMENT_TYPES['TOTAL_LOSS']) {
                    $total = ($sum + $others) * 1.14;
                }
                $assessorName = Auth::user()->firstName.' '.Auth::user()->lastName;
                $assessmentStatusID = $isDraft == 1 ? Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'] : Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'];
                Assessment::where(['id' => $assessmentID])->update([
                    "cause" => $cause,
                    "note" => $note,
                    "salvage" => $salvage,
                    "pav" => $pav,
                    "totalCost" => $sumTotal,
                    "totalLoss" => $totalLoss,
                    "assessmentTypeID" => $assessmentType,
                    "assessmentStatusID" => $assessmentStatusID,
                    "assessedAt" => $curDate,
                    "dateModified" => $curDate
                ]);
                $detail = JobDetail::where('assessmentID', $assessmentID)->exists();
                $jobs = array();

                if ($detail) {

                } else {
                    if ($labour > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["LABOUR"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["LABOUR"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                            "cost" => $labour
                        );
                        $jobs[] = $job;
                    }
                    if ($paint > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["PAINTING"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["PAINTING"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                            "cost" => $paint
                        );
                        $jobs[] = $job;
                    }
                    if ($miscellaneous > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["MISCELLANEOUS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["MISCELLANEOUS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                            "cost" => $miscellaneous
                        );
                        $jobs[] = $job;
                    }
                    if ($primer > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["PRIMER"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["PRIMER"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                            "cost" => $primer
                        );
                        $jobs[] = $job;
                    }
                    if ($jigging > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["JIGGING"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["JIGGING"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                            "cost" => $jigging
                        );
                        $jobs[] = $job;
                    }
                    if ($reconstruction > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["RECONSTRUCTION"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["RECONSTRUCTION"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                            "cost" => $reconstruction
                        );
                        $jobs[] = $job;
                    }
                    if ($gas > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["AC_GAS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["AC_GAS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                            "cost" => $gas
                        );
                        $jobs[] = $job;
                    }
                    if ($welding > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["WELDING_GAS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["WELDING_GAS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID'],
                            "cost" => $welding
                        );
                        $jobs[] = $job;
                    }
                    $collection = collect($jobs);

                    $save = JobDetail::insert($collection->values()->all());
                    if ($save) {
                        if ($isDraft == 1) {
                            $response = array(
                                "STATUS_CODE" => Config::SUCCESS_CODE,
                                "STATUS_MESSAGE" => "Congratulation!, You have successfully Saved an assessment as Draft"
                            );
                        } else if ($isDraft == 0) {
                            $response = array(
                                "STATUS_CODE" => Config::SUCCESS_CODE,
                                "STATUS_MESSAGE" => "Congratulation!, You have successfully created an assessment"
                            );
                            if ($assessmentType == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                                if ($total > Config::HEAD_ASSESSOR_THRESHOLD) {
                                    $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                    if (count($headAssessors) > 0) {
                                        foreach ($headAssessors as $headAssessor) {
                                            $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                            $data = [
                                                'claim' => $assessment->claim->claimNo,
                                                'reg' => $assessment->claim->vehicleRegNo,
                                                'headAssessor' => $headAssessor->firstName,
                                                'email' => $headAssessor->email
                                            ];
                                            $email_add = $data['email'];

                                            $email = [
                                                'subject' => 'Survey Report - ' . $data['reg'],
                                                'from_user_email' => 'noreply@jubileeinsurance.com',
                                                'message' => "
                                    Hi, <br>
                                    This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                                    " . $assessorName . " has completed their assessment report. <br>
                                    Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                                    <u><i><strong>Details are as below:</strong></i></u>
                                    <strong>Status: </strong> " . Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                                    <strong>Amount: </strong> " . $total . "  <br>
                                    <strong>Salvage: </strong>" . $salvage . " <br>
                                    <strong>PAV: </strong>" . $pav . "
                                    <br><br>

                                    Regards, <br><br>
                                    System Administrator, <br>
                                    I.T Department <br>
                                    Jubilee Insurance.
                                ",
                                            ];
                                            $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                             SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                              Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                        }
                                    }
                                }else
                                {
                                    $assistantHeadAssessors = User::role(Config::$ROLES['ASSISTANT-HEAD'])->get(); // Returns only users with the role 'Head Assessor'
                                    if (count($assistantHeadAssessors) > 0) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        foreach ($assistantHeadAssessors as $assistantHeadAssessor) {
                                            $data = [
                                                'claim' => $assessment->claim->claimNo,
                                                'reg' => $assessment->claim->vehicleRegNo,
                                                'assistantHeadAssessor' => $assistantHeadAssessor->firstName,
                                                'email' => $assistantHeadAssessor->email
                                            ];
                                            $email_add = $data['email'];

                                            $email = [
                                                'subject' => 'Survey Report - ' . $data['reg'],
                                                'from_user_email' => 'noreply@jubileeinsurance.com',
                                                'message' => "
                                    Hi, <br>
                                    This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                                    " . $assessorName . " has completed their assessment report. <br>
                                    Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                                    <u><i><strong>Details are as below:</strong></i></u>
                                    <strong>Status: </strong> " .Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                                    <strong>Amount: </strong> " . $total . "  <br>
                                    <strong>Salvage: </strong>" . $salvage . " <br>
                                    <strong>PAV: </strong>" . $pav . "
                                    <br><br>

                                    Regards, <br><br>
                                    System Administrator, <br>
                                    I.T Department <br>
                                    Jubilee Insurance.
                                ",
                                            ];
                                              $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                              SMSHelper::sendSMS('Hello ' . $assistantHeadAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $assistantHeadAssessor->MSISDN);
                                              Notification::send($assistantHeadAssessor, new NewAssessmentNotification($assessment));
                                        }
                                    }
                                }
                            } elseif ($assessmentType == Config::ASSESSMENT_TYPES['CASH_IN_LIEU']) {
                                $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                if (count($headAssessors) > 0) {
                                    foreach ($headAssessors as $headAssessor) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        $data = [
                                            'claim' => $assessment->claim->claimNo,
                                            'reg' => $assessment->claim->vehicleRegNo,
                                            'headAssessor' => $headAssessor->firstName,
                                            'email' => $headAssessor->email
                                        ];
                                        $email_add = $data['email'];

                                        $email = [
                                            'subject' => 'Survey Report - '.$data['reg'],
                                            'from_user_email' => 'noreply@jubileeinsurance.com',
                                            'message' =>"
                            Hi, <br>
                            This is in reference to claim number <strong>".$data['claim']." </strong><br>
                            ".$assessorName." has completed their assessment report. <br>
                            Login to the <a href=".url('/').">portal</a> to view it. <br>
                            <u><i><strong>Details are as below:</strong></i></u>
                            <strong>Status: </strong> ".Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType]."  <br>
                            <strong>Amount: </strong> ".$total." <br>
                            <strong>Salvage: </strong>".$salvage." <br>
                            <strong>PAV: </strong>".$pav."
                            <br><br>

                            Regards, <br><br>
                            System Administrator, <br>
                            I.T Department <br>
                            Jubilee Insurance.
                        ",
                                        ];
                                        $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                        SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                        Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                    }
                                }

                            } elseif ($assessmentType == Config::ASSESSMENT_TYPES['TOTAL_LOSS']) {
                                $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                if (count($headAssessors) > 0) {
                                    foreach ($headAssessors as $headAssessor) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        $data = [
                                            'claim' => $assessment->claim->claimNo,
                                            'reg' => $assessment->claim->vehicleRegNo,
                                            'headAssessor' => $headAssessor->firstName,
                                            'email' => $headAssessor->email
                                        ];
                                        $email_add = $data['email'];

                                        $email = [
                                            'subject' => 'Survey Report - '.$data['reg'],
                                            'from_user_email' => 'noreply@jubileeinsurance.com',
                                            'message' =>"
                            Hi, <br>
                            This is in reference to claim number <strong>".$data['claim']." </strong><br>
                            ".$assessorName." has completed their assessment report. <br>
                            Login to the <a href=".url('/').">portal</a> to view it. <br>
                            <u><i><strong>Details are as below:</strong></i></u>
                            <strong>Status: </strong> ".Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType]."  <br>
                            <strong>Amount: </strong> ".$total." <br>
                            <strong>Salvage: </strong>".$salvage." <br>
                            <strong>PAV: </strong>".$pav."
                            <br><br>

                            Regards, <br><br>
                            System Administrator, <br>
                            I.T Department <br>
                            Jubilee Insurance.
                        ",
                                        ];
                                          $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                          SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                          Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                    }
                                }
                            }
                        } else {
                            $response = array(
                                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                            );
                            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                                "An assessment was not created. An error occurred");
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to create an assessments. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }
    public function submitSupplementary(Request $request)
    {
        try {
            $totalImages = $request->totalImages;
            $assessmentID = $request->assessmentID;
            $partsData = json_decode($request->partsData, true);
            $jobsData = json_decode($request->jobsData, true);
            $isDraft = $request->isDraft;
            $assessmentType = $request->assessmentType;
            $curDate = $this->functions->curlDate();
            $assess = Assessment::where(["id"=>$assessmentID])->first();
            $claim = Claim::where(['id'=>isset($assess->claimID) ? $assess->claimID : 0])->first();

            $assessmentItems = array();
            $total = !empty($jobsData['total']) ? $jobsData['total'] : 0;
            $labour = !empty($jobsData['labour']) ? $jobsData['labour'] : 0;
            $paint = !empty($jobsData['paint']) ? $jobsData['paint'] : 0;
            $miscellaneous = !empty($jobsData['miscellaneous']) ? $jobsData['miscellaneous'] : 0;
            $primer = !empty($jobsData['primer']) ? $jobsData['primer'] : 0;
            $jigging = !empty($jobsData['jigging']) ? $jobsData['jigging'] : 0;
            $reconstruction = !empty($jobsData['reconstruction']) ? $jobsData['reconstruction'] : 0;
            $gas = !empty($jobsData['gas']) ? $jobsData['gas'] : 0;
            $welding = !empty($jobsData['welding']) ? $jobsData['welding'] : 0;
            $sumTotal = !empty($jobsData['sumTotal']) ? $jobsData['sumTotal'] : 0;
            $pav = !empty($jobsData['pav']) ? $jobsData['pav'] : 0;
            $salvage = !empty($jobsData['salvage']) ? $jobsData['salvage'] : 0;
            $totalLoss = !empty($jobsData['totalLoss']) ? $jobsData['totalLoss'] : 0;
            $note = !empty($jobsData['note']) ? $jobsData['note'] : null;
            $assessmentStatusID = $isDraft == 1 ? Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'] : Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'];
            $supplementaryID = Assessment::insertGetId([
                "claimID"=> $claim->id,
                "assessmentID" => $assessmentID,
                "assessedBy" => Auth::user()->id,
                "note" => $note,
                "salvage" => $salvage,
                "pav" => $pav,
                "totalCost" => $sumTotal,
                "totalLoss" => $totalLoss,
                "assessmentTypeID" => $assessmentType,
                "assessmentStatusID" => $assessmentStatusID,
                "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                "assessedAt" => $curDate,
                "dateModified" => $curDate
            ]);
            $drafted = $request->drafted;
            //Loop for getting files with index like image0, image1
            if($supplementaryID > 0){
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
                        "assessmentID" => $supplementaryID,
                        "name" => $picture,
                        "mime" => $extension,
                        "size" => $size,
                        "documentType" => Config::$DOCUMENT_TYPES["IMAGE"]["ID"],
                        "url" => $path,
                        "segment" => Config::$ASSESSMENT_SEGMENTS["SUPPLEMENTARY"]["ID"]
                    ]);
                }
            }
            foreach ($partsData as $partDetail) {
                $part = $partDetail['vehiclePart'];
                $quantity = $partDetail['quantity'];
                $total = $partDetail['total'];
                $cost = $partDetail['cost'];
                $contribution = $partDetail['contribution'];
                $discount = $partDetail['discount'];
                $remarks = $partDetail['remarks'];
                $category = $partDetail['category'];
                $assessmentItem = array(
                    "assessmentID" => $supplementaryID,
                    "partID" => $part,
                    "quantity" => $quantity,
                    "contribution" => $contribution,
                    "discount" => $discount,
                    "cost" => $cost,
                    "total" => $total,
                    "remarks" => $remarks,
                    "assessmentItemType" => $assessmentType,
                    "category" => $category,
                    "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
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
                $sum = AssessmentItem::where(['assessmentID'=> $assessmentID,"segment"=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->sum('total');

                //Sum of other charges
                $others = (is_numeric($labour)) + (is_numeric($paint)) + (is_numeric($miscellaneous)) + (is_numeric($primer))
                    + (is_numeric($jigging)) + (is_numeric($reconstruction)) + (is_numeric($gas)) + (is_numeric($welding));

                if ($assessmentType == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                    $total = ($sum + $others) * 1.14;
                } elseif ($assessmentType == Config::ASSESSMENT_TYPES['CASH_IN_LIEU']) {
                    $total = ($sum * Config::MARK_UP) + $others;
                } elseif ($assessmentType == Config::ASSESSMENT_TYPES['TOTAL_LOSS']) {
                    $total = ($sum + $others) * 1.14;
                }
                $assessorName = Auth::user()->firstName.' '.Auth::user()->lastName;
                $detail = JobDetail::where(['assessmentID'=> $supplementaryID,"segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->exists();
                $jobs = array();

                if ($detail) {

                } else {
                    if ($labour > 0) {
                        $job = array(
                            "assessmentID" => $supplementaryID,
                            "name" => Config::$JOB_TYPES["LABOUR"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["LABOUR"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $labour
                        );
                        $jobs[] = $job;
                    }
                    if ($paint > 0) {
                        $job = array(
                            "assessmentID" => $supplementaryID,
                            "name" => Config::$JOB_TYPES["PAINTING"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["PAINTING"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $paint
                        );
                        $jobs[] = $job;
                    }
                    if ($miscellaneous > 0) {
                        $job = array(
                            "assessmentID" => $supplementaryID,
                            "name" => Config::$JOB_TYPES["MISCELLANEOUS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["MISCELLANEOUS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $miscellaneous
                        );
                        $jobs[] = $job;
                    }
                    if ($primer > 0) {
                        $job = array(
                            "assessmentID" => $supplementaryID,
                            "name" => Config::$JOB_TYPES["PRIMER"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["PRIMER"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $primer
                        );
                        $jobs[] = $job;
                    }
                    if ($jigging > 0) {
                        $job = array(
                            "assessmentID" => $supplementaryID,
                            "name" => Config::$JOB_TYPES["JIGGING"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["JIGGING"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $jigging
                        );
                        $jobs[] = $job;
                    }
                    if ($reconstruction > 0) {
                        $job = array(
                            "assessmentID" => $supplementaryID,
                            "name" => Config::$JOB_TYPES["RECONSTRUCTION"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["RECONSTRUCTION"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $reconstruction
                        );
                        $jobs[] = $job;
                    }
                    if ($gas > 0) {
                        $job = array(
                            "assessmentID" => $supplementaryID,
                            "name" => Config::$JOB_TYPES["AC_GAS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["AC_GAS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $gas
                        );
                        $jobs[] = $job;
                    }
                    if ($welding > 0) {
                        $job = array(
                            "assessmentID" => $supplementaryID,
                            "name" => Config::$JOB_TYPES["WELDING_GAS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["WELDING_GAS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $welding
                        );
                        $jobs[] = $job;
                    }
                    $collection = collect($jobs);

                    $save = JobDetail::insert($collection->values()->all());
                    if ($save) {
                        if ($isDraft == 1) {
                            $response = array(
                                "STATUS_CODE" => Config::SUCCESS_CODE,
                                "STATUS_MESSAGE" => "Congratulation!, You have successfully Saved an assessment as Draft"
                            );
                        } else if ($isDraft == 0) {
                            $response = array(
                                "STATUS_CODE" => Config::SUCCESS_CODE,
                                "STATUS_MESSAGE" => "Congratulation!, You have successfully created an assessment"
                            );
                            if ($assessmentType == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                                if ($total > Config::HEAD_ASSESSOR_THRESHOLD) {
                                    $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                    if (count($headAssessors) > 0) {
                                        foreach ($headAssessors as $headAssessor) {
                                            $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                            $data = [
                                                'claim' => $assessment->claim->claimNo,
                                                'reg' => $assessment->claim->vehicleRegNo,
                                                'headAssessor' => $headAssessor->firstName,
                                                'email' => $headAssessor->email
                                            ];
                                            $email_add = $data['email'];

                                            $email = [
                                                'subject' => 'Assessment Report - ' . $data['reg'],
                                                'from_user_email' => 'noreply@jubileeinsurance.com',
                                                'message' => "
                                    Hi, <br>
                                    This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                                    " . $assessorName . " has completed their assessment report. <br>
                                    Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                                    <u><i><strong>Details are as below:</strong></i></u>
                                    <strong>Status: </strong> " . Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                                    <strong>Amount: </strong> " . $total . "  <br>
                                    <strong>Salvage: </strong>" . $salvage . " <br>
                                    <strong>PAV: </strong>" . $pav . "
                                    <br><br>

                                    Regards, <br><br>
                                    System Administrator, <br>
                                    I.T Department <br>
                                    Jubilee Insurance.
                                ",
                                            ];
                                            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                                                "Head assessor details ".json_encode($data));
                                              $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                              SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                              Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                        }
                                    }
                                }else
                                {
                                    $assistantHeadAssessors = User::role(Config::$ROLES['ASSISTANT-HEAD'])->get(); // Returns only users with the role 'Head Assessor'
                                    if (count($assistantHeadAssessors) > 0) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        foreach ($assistantHeadAssessors as $assistantHeadAssessor) {
                                            $data = [
                                                'claim' => $assessment->claim->claimNo,
                                                'reg' => $assessment->claim->vehicleRegNo,
                                                'assistantHeadAssessor' => $assistantHeadAssessor->firstName,
                                                'email' => $assistantHeadAssessor->email
                                            ];
                                            $email_add = $data['email'];

                                            $email = [
                                                'subject' => 'Assessment Report - ' . $data['reg'],
                                                'from_user_email' => 'noreply@jubileeinsurance.com',
                                                'message' => "
                                    Hi, <br>
                                    This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                                    " . $assessorName . " has completed their assessment report. <br>
                                    Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                                    <u><i><strong>Details are as below:</strong></i></u>
                                    <strong>Status: </strong> " .Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                                    <strong>Amount: </strong> " . $total . "  <br>
                                    <strong>Salvage: </strong>" . $salvage . " <br>
                                    <strong>PAV: </strong>" . $pav . "
                                    <br><br>

                                    Regards, <br><br>
                                    System Administrator, <br>
                                    I.T Department <br>
                                    Jubilee Insurance.
                                ",
                                            ];
                                              $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                              SMSHelper::sendSMS('Hello ' . $assistantHeadAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $assistantHeadAssessor->MSISDN);
                                              Notification::send($assistantHeadAssessor, new NewAssessmentNotification($assessment));
                                        }
                                    }
                                }
                            } elseif ($assessmentType == Config::ASSESSMENT_TYPES['CASH_IN_LIEU']) {
                                $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                if (count($headAssessors) > 0) {
                                    foreach ($headAssessors as $headAssessor) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        $data = [
                                            'claim' => $assessment->claim->claimNo,
                                            'reg' => $assessment->claim->vehicleRegNo,
                                            'headAssessor' => $headAssessor->firstName,
                                            'email' => $headAssessor->email
                                        ];
                                        $email_add = $data['email'];

                                        $email = [
                                            'subject' => 'Assessment Report - '.$data['reg'],
                                            'from_user_email' => 'noreply@jubileeinsurance.com',
                                            'message' =>"
                            Hi, <br>
                            This is in reference to claim number <strong>".$data['claim']." </strong><br>
                            ".$assessorName." has completed their assessment report. <br>
                            Login to the <a href=".url('/').">portal</a> to view it. <br>
                            <u><i><strong>Details are as below:</strong></i></u>
                            <strong>Status: </strong> ".Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType]."  <br>
                            <strong>Amount: </strong> ".$total." <br>
                            <strong>Salvage: </strong>".$salvage." <br>
                            <strong>PAV: </strong>".$pav."
                            <br><br>

                            Regards, <br><br>
                            System Administrator, <br>
                            I.T Department <br>
                            Jubilee Insurance.
                        ",
                                        ];
                                        $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                        SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                        Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                    }
                                }

                            } elseif ($assessmentType == Config::ASSESSMENT_TYPES['TOTAL_LOSS']) {
                                $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                if (count($headAssessors) > 0) {
                                    foreach ($headAssessors as $headAssessor) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        $data = [
                                            'claim' => $assessment->claim->claimNo,
                                            'reg' => $assessment->claim->vehicleRegNo,
                                            'headAssessor' => $headAssessor->firstName,
                                            'email' => $headAssessor->email
                                        ];
                                        $email_add = $data['email'];

                                        $email = [
                                            'subject' => 'Assessment Report - '.$data['reg'],
                                            'from_user_email' => 'noreply@jubileeinsurance.com',
                                            'message' =>"
                            Hi, <br>
                            This is in reference to claim number <strong>".$data['claim']." </strong><br>
                            ".$assessorName." has completed their assessment report. <br>
                            Login to the <a href=".url('/').">portal</a> to view it. <br>
                            <u><i><strong>Details are as below:</strong></i></u>
                            <strong>Status: </strong> ".Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType]."  <br>
                            <strong>Amount: </strong> ".$total." <br>
                            <strong>Salvage: </strong>".$salvage." <br>
                            <strong>PAV: </strong>".$pav."
                            <br><br>

                            Regards, <br><br>
                            System Administrator, <br>
                            I.T Department <br>
                            Jubilee Insurance.
                        ",
                                        ];
                                        $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                        SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                        Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                    }
                                }
                            }
                        } else {
                            $response = array(
                                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                            );
                            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                                "An assessment was not created. An error occurred");
                        }
                    }
            }
        }else
            {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }}else
            {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
            }
        }catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to create an assessments. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function assessmentReport(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $approved=PriceChange::where('assessmentID',$assessmentID)->first();
        $aproved=isset($approved)?$approved:'false';
        $assessment = Assessment::where(["id" => $assessmentID])
            ->with("claim")->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID,'segment'=>Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID']])->with('part')->get();
        $jobDetails = JobDetail::where(["assessmentID" => $assessmentID,'segment'=>Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID']])->get();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured= CustomerMaster::where(["customerCode" => $customerCode])->first();
        $documents = Document::where(["assessmentID" => $assessmentID,"segment"=>Config::$ASSESSMENT_SEGMENTS['ASSESSMENT']['ID']])->get();
        $adjuster = User::where(['id'=> $assessment->claim->createdBy])->first();
        $assessor = User::where(['id'=> $assessment->assessedBy])->first();
        return view("assessor.view-assessment-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor,'aproved'=>$aproved]);
    }
    public function supplementaryReport(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $assessment = Assessment::where(["id" => $assessmentID,'segment'=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->with("claim")->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID,'segment'=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->with('part')->get();
        $jobDetails = JobDetail::where(["assessmentID" => $assessmentID,'segment'=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->get();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured= CustomerMaster::where(["customerCode" => $customerCode])->first();
        $documents = Document::where(["assessmentID" => $assessmentID,"segment"=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->get();
        $adjuster = User::where(['id'=> $assessment->claim->createdBy])->first();
        $assessor = User::where(['id'=> $assessment->assessedBy])->first();
        return view("assessor.view-supplementary-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor]);
    }
    public function submitReInspection(Request $request)
    {
        try {
            $assessmentID = $request->assessmentID;
            $totalImages = $request->totalImages;
            $repaired = json_decode($request->repaired,true);
            $replaced = json_decode($request->replaced,true);
            $cil = json_decode($request->cil,true);
            $reused = json_decode($request->reused,true);
            $notes = isset($request->notes) ? $request->notes : '';
            $assessment = Assessment::where(['id'=> $request->assessmentID])->first();
            $claim = Claim::where(["id"=> $assessment->claimID])->first();

            $labor = 0;
            $addLabor = 0;
            $subAmount = 0;
            $priceChange = 0;
            $assessmentTotal = 0;
            $supplementaryTotal = 0;
            $finalTotal = 0;

            if ($request->labor != '' && $request->labor != 0) {
                $labor = $request->labor;
            }

            if ($request->add_labor != '' && $request->add_labor != 0) {
                $addLabor = $request->add_labor;
            }

            //Assessment Total
            $sumAssessmentParts = AssessmentItem::where('assessmentID', $request->assessmentID)->sum('total');

            $sumAssessmentDetails = JobDetail::where(["assessmentID"=>$request->assessmentID])->sum('cost');
            $status = $assessment->assessmentTypeID;

            if ($status == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                $assessmentTotal = (($sumAssessmentParts + ($sumAssessmentDetails) * Config::CURRENT_TOTAL_PERCENTAGE)/Config::INITIAL_PERCENTAGE);
            } else {
                $assessmentTotal = (($sumAssessmentParts * Config::MARK_UP) + $sumAssessmentDetails);
            }
            if (isset($repaired) || isset($replaced) || isset($cil) || isset($reused)) {
                if (isset($repaired)) {
                    AssessmentItem::where('assessmentID', $assessmentID)
                        ->whereIn('id', $repaired)
                        ->update([
                            'reInspectionType' => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            'reInspection' => Config::ACTIVE
                        ]);
                }
                if (isset($replaced)) {
                    AssessmentItem::where('assessmentID', $assessmentID)
                        ->whereIn('id', $replaced)
                        ->update([
                            'reInspectionType' => Config::$JOB_CATEGORIES['REPLACE']['ID'],
                            'reInspection' => Config::ACTIVE
                        ]);
                }
                if (isset($cil)) {
                    AssessmentItem::where('assessmentID', $assessmentID)
                        ->whereIn('id', $cil)
                        ->update([
                            'reInspectionType' => Config::$JOB_CATEGORIES['CIL']['ID'],
                            'reInspection' => Config::ACTIVE
                        ]);
                }
                if (isset($reused)) {
                    AssessmentItem::where('assessmentID', $assessmentID)
                        ->whereIn('id', $reused)
                        ->update([
                            'reInspectionType' => Config::$JOB_CATEGORIES['REUSE']['ID'],
                            'reInspection' => Config::ACTIVE
                        ]);
                }

                $unReInspectedAmount = AssessmentItem::where('assessmentID', $assessmentID)
                    ->where('reInspection', 0)
                    ->sum('total');

                $award = AssessmentItem::where('assessmentID', $assessmentID)
                    ->where('reInspection', 0)
                    ->where('reInspectionType', Config::$JOB_CATEGORIES['CIL']['ID'])
                    ->sum('total');

                $unReInspectedParts = AssessmentItem::where('assessmentID', $assessmentID)
                    ->where('reInspection', 0)
                    ->get();

                if ($status == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
//                    $priceChange = $priceChange * 1.14;
                    $unReInspectedAmount = (Config::CURRENT_TOTAL_PERCENTAGE/Config::INITIAL_PERCENTAGE) * $unReInspectedAmount;
                    $labor = $labor * (Config::CURRENT_TOTAL_PERCENTAGE/Config::INITIAL_PERCENTAGE);
                    $addLabor = $addLabor * (Config::CURRENT_TOTAL_PERCENTAGE/Config::INITIAL_PERCENTAGE);
                    $finalTotal = ($assessmentTotal + $addLabor) - ($unReInspectedAmount + $labor);
                    // dd($assessmentTotal , $addLabor , $priceChange , $supplementaryTotal, $unReInspectedAmount , $labor);
                } elseif ($status == Config::ASSESSMENT_TYPES['CASH_IN_LIEU']) {
                    $priceChange = $priceChange * Config::MARK_UP;
                    $unReInspectedAmount = Config::MARK_UP * $unReInspectedAmount;
                    $finalTotal = ($assessmentTotal + $addLabor) - ($unReInspectedAmount + $labor);
                }

                if ($status == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                    $subAmount = (Config::MARK_UP * $award) + $labor;
                } elseif ($status == Config::ASSESSMENT_TYPES['CASH_IN_LIEU']) {
                    $subAmount = (Config::MARK_UP * $award) + $labor;
                }
                $inspection = ReInspection::where(['assessmentID'=> $assessmentID])->first();
                if (isset($inspection->id)) {
                    $inspectionID = $inspection->inspectionID;
                    ReInspection::where('assessmentID',$assessmentID)
                        ->update([
                            'labor' => $labor,
                            'addLabor' => $addLabor,
                            'notes' => $notes,
                            'total' => $finalTotal,
                            'notes' => $request->notes,
                            'modifiedBy' => Auth::user()->id,
                            'dateModified' => date('Y-m-d H:i:s')
                        ]);
                    if($totalImages >0)
                    {
                        $documents = Document::where(['inspectionID' => $inspection->inspectionID])->get();
                        if (count($documents) > 0) {
                            $affectedPdfRows = Document::where(['inspectionID' => $inspection->inspectionID])->delete();
                            if ($affectedPdfRows > 0) {
                                foreach ($documents as $document) {
                                    $image_path = "documents/" . $document->name;  // Value is not URL but directory file path
                                    if (File::exists($image_path)) {
                                        File::delete($image_path);
                                    }
                                }
                            }
                        }
                    }
                }else
                {
                    $inspectionID = ReInspection::insertGetId([
                        'assessmentID' => $assessmentID,
                        'labor' => $labor,
                        'addLabor' => $addLabor,
                        'notes' => $notes,
                        'total' => $finalTotal,
                        'createdBy' => Auth::user()->id,
                        'notes' => $request->notes,
                        'dateCreated' => date('Y-m-d H:i:s'),
                    ]);
                }
                if($totalImages > 0)
                {
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
                            //                    $inspectionID = ReInspection::where([''=>$assessmentID])->first();
                            Document::create([
                                "inspectionID" => $inspectionID,
                                "name" => $picture,
                                "mime" => $extension,
                                "size" => $size,
                                "documentType" => Config::$DOCUMENT_TYPES["IMAGE"]["ID"],
                                "url" => $path,
                                "segment" => Config::$ASSESSMENT_SEGMENTS["RE_INSPECTION"]["ID"]
                            ]);
                        }
                    }
                }
                $pdf = [
                    'assessor' => Auth::user()->name,
                    'amount' => $finalTotal,
                    'vehicle_reg' => $claim->vehicleRegNo,
                    'dateCreated' => $assessment->dateCreated,
                    'day' => date('Y-m-d'),
                    'insured' => $claim->customer,
                    'claim' => $claim->claimNo,
                    'subAmount' => $subAmount,
                    'parts' => $unReInspectedParts,
                    'labor' => $labor,
                    'addLabor' => $addLabor
                ];

                $string = str_replace('/', '-', $claim->claimNo);

//                $officerRole = Role::where('name', 'Re-inspection Officer')->first()->id;
//                $officers = DB::table('model_has_roles')->whereIn('role_id', [$officerRole])->pluck('user_id')->toArray();
//                $officerEmails = User::whereIn('id', $officers)->pluck('email')->toArray();
//                $adjusterRole = Role::where('name', 'Adjuster')->first()->id;
//                $adjusters = DB::table('user_has_roles')->whereIn('role_id', [$adjusterRole])->pluck('user_id')->toArray();
//                $adjusterEmails = User::whereIn('id', $adjusters)->pluck('email')->toArray();
//                $headAssessor = User::role('Head Assessor')->first();
//
//                $cc = array_merge($officerEmails, $adjusterEmails, [Auth::user()->email]);
//
//                $data = [
//                    'reg' => $claim->vehicleRegNo,
//                    'claim' => $claim->claimNo,
//                    'headName' => $headAssessor->name,
//                    'headEmail' => $headAssessor->email,
//                    'cc' => $cc
//                ];
                Claim::where(["id"=> $assessment->claimID])->update([
                    "claimStatusID" => Config::$STATUSES['CLAIM']['RE-INSPECTED']['id']
                ]);
                Assessment::where(['id'=> $request->assessmentID])->update([
                    "segment" =>Config::$ASSESSMENT_SEGMENTS['RE_INSPECTION']['ID']
                ]);
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Congratulations, Re-inspection saved successfully"
                );
            }

        }catch (\Exception $e)
        {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to create a re-inspection " . $e->getMessage());
        }

        return json_encode($response);
    }
    public function reInspectionReport(Request $request)
    {
        $assessmentID = $request->assessmentID;
        $assessment = Assessment::where(["id" => $assessmentID])->with("claim")->first();
        $assessmentItems = AssessmentItem::where(["assessmentID" => $assessmentID])->with('part')->get();
        $jobDetails = JobDetail::where(["assessmentID" => $assessmentID])->get();
        $customerCode = isset($assessment['claim']['customerCode']) ? $assessment['claim']['customerCode'] : 0;
        $insured= CustomerMaster::where(["customerCode" => $customerCode])->first();
        $reinspection = ReInspection::where(['assessmentID'=>$assessmentID])->first();
        $documents = Document::where(["inspectionID" => $reinspection->id])->get();
        $adjuster = User::where(['id'=> $assessment->claim->createdBy])->first();
        $assessor = User::where(['id'=> $assessment->assessedBy])->first();
        return view("assessor.view-re-inspection-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor]);
    }

    public function editAssessmentReport(Request $request, $assessmentID)
    {
        $draftAssessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
        $carDetails = CarModel::where(["modelCode" => isset($assessment->claim->carModelCode) ? $assessment->claim->carModelCode : 0])->first();
//        $remarks = Remarks::all();
//        $parts = Part::all();
        $remarks = Cache::remember('remarks',Config::CACHE_EXPIRY_PERIOD,function (){
            return Remarks::select("id","name")->get();
        });
        $parts = Cache::remember('parts',Config::CACHE_EXPIRY_PERIOD,function (){
            return Part::select("id","name")->get();
        });
        $assessmentItems = AssessmentItem::where(["assessmentID" => isset($draftAssessment->id) ? $draftAssessment->id : 0])->with("part")->get();
        $jobDetails = JobDetail::where(["assessmentID" => isset($draftAssessment->id) ? $draftAssessment->id : 0])->get();
        $jobDraftDetail = [];
        foreach ($jobDetails as $jobDetail) {

            if ($jobDetail->jobType == Config::$JOB_TYPES["LABOUR"]["ID"]) {
                $jobDraftDetail["Labour"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["PAINTING"]["ID"]) {
                $jobDraftDetail["Painting"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["MISCELLANEOUS"]["ID"]) {
                $jobDraftDetail["Miscellaneous"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["PRIMER"]["ID"]) {
                $jobDraftDetail["2k Primer"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["JIGGING"]["ID"]) {
                $jobDraftDetail["Jigging"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["RECONSTRUCTION"]["ID"]) {
                $jobDraftDetail["Reconstruction"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["AC_GAS"]["ID"]) {
                $jobDraftDetail["AC/Gas"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["WELDING_GAS"]["ID"]) {
                $jobDraftDetail["Welding/Gas"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["BUMPER_FIBRE"]["ID"]) {
                $jobDraftDetail["Bumper Fibre"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["DAM_KIT"]["ID"]) {
                $jobDraftDetail["Dam Kit"] = $jobDetail->cost;
            }
        }

        return view('assessor.edit-assessment-report', ['assessment' => $assessment, 'remarks' => $remarks, 'parts' => $parts, 'assessmentItems' => $assessmentItems, "jobDraftDetail" => $jobDraftDetail, "draftAssessment" => $draftAssessment, "carDetails" => $carDetails]);
    }
    public function editSupplementaryReport(Request $request, $assessmentID)
    {
        $draftAssessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
        $carDetails = CarModel::where(["modelCode" => isset($assessment->claim->carModelCode) ? $assessment->claim->carModelCode : 0])->first();
//        $remarks = Remarks::all();
//        $parts = Part::all();
        $remarks = Cache::remember('remarks',Config::CACHE_EXPIRY_PERIOD,function (){
            return Remarks::select("id","name")->get();
        });
        $parts = Cache::remember('parts',Config::CACHE_EXPIRY_PERIOD,function (){
            return Part::select("id","name")->get();
        });
        $assessmentItems = AssessmentItem::where(["assessmentID" => isset($draftAssessment->id) ? $draftAssessment->id : 0,'segment'=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->with("part")->get();
        $jobDetails = JobDetail::where(["assessmentID" => isset($draftAssessment->id) ? $draftAssessment->id : 0,'segment'=>Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID']])->get();
        $jobDraftDetail = [];
        foreach ($jobDetails as $jobDetail) {

            if ($jobDetail->jobType == Config::$JOB_TYPES["LABOUR"]["ID"]) {
                $jobDraftDetail["Labour"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["PAINTING"]["ID"]) {
                $jobDraftDetail["Painting"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["MISCELLANEOUS"]["ID"]) {
                $jobDraftDetail["Miscellaneous"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["PRIMER"]["ID"]) {
                $jobDraftDetail["2k Primer"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["JIGGING"]["ID"]) {
                $jobDraftDetail["Jigging"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["RECONSTRUCTION"]["ID"]) {
                $jobDraftDetail["Reconstruction"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["AC_GAS"]["ID"]) {
                $jobDraftDetail["AC/Gas"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["WELDING_GAS"]["ID"]) {
                $jobDraftDetail["Welding/Gas"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["BUMPER_FIBRE"]["ID"]) {
                $jobDraftDetail["Bumper Fibre"] = $jobDetail->cost;
            }
            if ($jobDetail->jobType == Config::$JOB_TYPES["DAM_KIT"]["ID"]) {
                $jobDraftDetail["Dam Kit"] = $jobDetail->cost;
            }
        }

        return view('assessor.edit-supplementary-report', ['assessment' => $assessment, 'remarks' => $remarks, 'parts' => $parts, 'assessmentItems' => $assessmentItems, "jobDraftDetail" => $jobDraftDetail, "draftAssessment" => $draftAssessment, "carDetails" => $carDetails]);
    }

    public function submitEditedAssessment(Request $request)
    {
        try {
            $totalImages = $request->totalImages;
            $assessmentID = $request->assessmentID;
            $partsData = json_decode($request->partsData, true);
            $jobsData = json_decode($request->jobsData, true);
            $isDraft = $request->isDraft;
            $assessmentType = $request->assessmentType;
            $curDate = $this->functions->curlDate();
            $drafted = $request->drafted;
            // echo(json_encode($partsData));
            // exit();
            if ($drafted == 1) {
                $affectedRows = AssessmentItem::where(["assessmentID" => $assessmentID])->delete();
                if ($affectedRows > 0) {
                    $affectedJobDetailRows = JobDetail::where(["assessmentID" => $assessmentID])->delete();
                    if ($affectedJobDetailRows > 0) {
                        $documents = Document::where(["assessmentID" => $assessmentID])->get();
                        if (count($documents) > 0) {
                            $affectedDocumentRows = Document::where(["assessmentID" => $assessmentID])->delete();
                            if ($affectedDocumentRows > 0) {
                                foreach ($documents as $document) {
                                    $image_path = "documents/" . $document->name;  // Value is not URL but directory file path
                                    if (File::exists($image_path)) {
                                        File::delete($image_path);
                                    }
                                }
                            }
                        }
                    }
                }
            }
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
//            $total = !empty($jobsData['total']) ? $jobsData['total'] : 0;
            $labour = !empty($jobsData['labour']) ? $jobsData['labour'] : 0;
            $paint = !empty($jobsData['paint']) ? $jobsData['paint'] : 0;
            $miscellaneous = !empty($jobsData['miscellaneous']) ? $jobsData['miscellaneous'] : 0;
            $primer = !empty($jobsData['primer']) ? $jobsData['primer'] : 0;
            $jigging = !empty($jobsData['jigging']) ? $jobsData['jigging'] : 0;
            $reconstruction = !empty($jobsData['reconstruction']) ? $jobsData['reconstruction'] : 0;
            $gas = !empty($jobsData['gas']) ? $jobsData['gas'] : 0;
            $welding = !empty($jobsData['welding']) ? $jobsData['welding'] : 0;
            $sumTotal = !empty($jobsData['sumTotal']) ? $jobsData['sumTotal'] : 0;
            $pav = !empty($jobsData['pav']) ? $jobsData['pav'] : 0;
            $salvage = !empty($jobsData['salvage']) ? $jobsData['salvage'] : 0;
            $totalLoss = !empty($jobsData['totalLoss']) ? $jobsData['totalLoss'] : 0;
            $cause = !empty($jobsData['cause']) ? $jobsData['cause'] : null;
            $note = !empty($jobsData['note']) ? $jobsData['note'] : null;
            $chassisNumber = !empty($jobsData['chassisNumber']) ? $jobsData['chassisNumber'] : '';
            if ($chassisNumber != '') {
                $assessment = Assessment::where(['id' => $assessmentID])->first();
                Claim::where(['id' => $assessment->claimID])->update([
                    "chassisNumber" => $chassisNumber
                ]);
            }
            foreach ($partsData as $partDetail) {

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
            // echo($assessmentItems);
            // exit();

            $collection = collect($assessmentItems);


            $unique = $collection->unique('partID');

            $save = AssessmentItem::insert($unique->values()->all());
            // print_r($collection);
            // exit();
            if ($save) {
                //Sum of parts
                $sum = AssessmentItem::where('assessmentID', $assessmentID)->sum('total');

                //Sum of other charges
                $others = (is_numeric($labour)) + (is_numeric($paint)) + (is_numeric($miscellaneous)) + (is_numeric($primer))
                    + (is_numeric($jigging)) + (is_numeric($reconstruction)) + (is_numeric($gas)) + (is_numeric($welding));

                if ($assessmentType == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                    $total = ($sum + $others) * 1.14;
                } elseif ($assessmentType == Config::ASSESSMENT_TYPES['CASH_IN_LIEU']) {
                    $total = ($sum * 0.9) + $others;
                } elseif ($assessmentType == Config::ASSESSMENT_TYPES['TOTAL_LOSS']) {
                    $total = ($sum + $others) * 1.14;
                }
                $assessorName = Auth::user()->firstName . ' ' . Auth::user()->lastName;
                $assessmentStatusID = $isDraft == 1 ? Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'] : Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'];
                Assessment::where(['id' => $assessmentID])->update([
                    "cause" => $cause,
                    "note" => $note,
                    "salvage" => $salvage,
                    "pav" => $pav,
                    "totalCost" => $sumTotal,
                    "totalLoss" => $totalLoss,
                    "assessmentTypeID" => $assessmentType,
                    "assessmentStatusID" => $assessmentStatusID,
                    "assessedAt" => $curDate,
                    "dateModified" => $curDate
                ]);
                $detail = JobDetail::where('assessmentID', $assessmentID)->exists();
                $jobs = array();

                if ($detail) {
                } else {
                    if ($labour > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["LABOUR"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["LABOUR"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"],
                            "cost" => $labour
                        );
                        $jobs[] = $job;
                    }
                    if ($paint > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["PAINTING"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["PAINTING"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"],
                            "cost" => $paint
                        );
                        $jobs[] = $job;
                    }
                    if ($miscellaneous > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["MISCELLANEOUS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["MISCELLANEOUS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"],
                            "cost" => $miscellaneous
                        );
                        $jobs[] = $job;
                    }
                    if ($primer > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["PRIMER"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["PRIMER"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"],
                            "cost" => $primer
                        );
                        $jobs[] = $job;
                    }
                    if ($jigging > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["JIGGING"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["JIGGING"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"],
                            "cost" => $jigging
                        );
                        $jobs[] = $job;
                    }
                    if ($reconstruction > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["RECONSTRUCTION"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["RECONSTRUCTION"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"],
                            "cost" => $reconstruction
                        );
                        $jobs[] = $job;
                    }
                    if ($gas > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["AC_GAS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["AC_GAS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"],
                            "cost" => $gas
                        );
                        $jobs[] = $job;
                    }
                    if ($welding > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["WELDING_GAS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["WELDING_GAS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"],
                            "cost" => $welding
                        );
                        $jobs[] = $job;
                    }
                    foreach ($jobs as $job) {
                        $jobDetail = JobDetail::create($job);
                        if ($isDraft == 1 & $jobDetail->id > 0) {
                            $response = array(
                                "STATUS_CODE" => Config::SUCCESS_CODE,
                                "STATUS_MESSAGE" => "Congratulation!, You have successfully Saved an assessment as Draft"
                            );
                        } else if ($isDraft == 0 & $jobDetail->id > 0) {
                            $response = array(
                                "STATUS_CODE" => Config::SUCCESS_CODE,
                                "STATUS_MESSAGE" => "Congratulation!, You have successfully created an assessment"
                            );
                            if ($assessmentType == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                                if ($total > Config::HEAD_ASSESSOR_THRESHOLD) {
                                    $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                    if (count($headAssessors) > 0) {
                                        foreach ($headAssessors as $headAssessor) {
                                            $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                            $data = [
                                                'claim' => $assessment->claim->claimNo,
                                                'reg' => $assessment->claim->vehicleRegNo,
                                                'headAssessor' => $headAssessor->firstName,
                                                'email' => $headAssessor->email
                                            ];
                                            $email_add = $data['email'];

                                            $email = [
                                                'subject' => 'Assessment Report - ' . $data['reg'],
                                                'from_user_email' => 'noreply@jubileeinsurance.com',
                                                'message' => "
                                    Hi, <br>
                                    This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                                    " . $assessorName . " has completed their assessment report. <br>
                                    Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                                    <u><i><strong>Details are as below:</strong></i></u>
                                    <strong>Status: </strong> " . Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                                    <strong>Amount: </strong> " . $total . "  <br>
                                    <strong>Salvage: </strong>" . $salvage . " <br>
                                    <strong>PAV: </strong>" . $pav . "
                                    <br><br>

                                    Regards, <br><br>
                                    System Administrator, <br>
                                    I.T Department <br>
                                    Jubilee Insurance.
                                ",
                                            ];
                                            //                                            $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                            //                                            SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                            Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                        }
                                    }
                                } else {
                                    $assistantHeadAssessors = User::role(Config::$ROLES['ASSISTANT-HEAD'])->get(); // Returns only users with the role 'Head Assessor'
                                    if (count($assistantHeadAssessors) > 0) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        foreach ($assistantHeadAssessors as $assistantHeadAssessor) {
                                            $data = [
                                                'claim' => $assessment->claim->claimNo,
                                                'reg' => $assessment->claim->vehicleRegNo,
                                                'assistantHeadAssessor' => $assistantHeadAssessor->firstName,
                                                'email' => $assistantHeadAssessor->email
                                            ];
                                            $email_add = $data['email'];

                                            $email = [
                                                'subject' => 'Assessment Report - ' . $data['reg'],
                                                'from_user_email' => 'noreply@jubileeinsurance.com',
                                                'message' => "
                                    Hi, <br>
                                    This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                                    " . $assessorName . " has completed their assessment report. <br>
                                    Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                                    <u><i><strong>Details are as below:</strong></i></u>
                                    <strong>Status: </strong> " . Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                                    <strong>Amount: </strong> " . $total . "  <br>
                                    <strong>Salvage: </strong>" . $salvage . " <br>
                                    <strong>PAV: </strong>" . $pav . "
                                    <br><br>

                                    Regards, <br><br>
                                    System Administrator, <br>
                                    I.T Department <br>
                                    Jubilee Insurance.
                                ",
                                            ];
                                            //                                            $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                            //                                            SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                            Notification::send($assistantHeadAssessor, new NewAssessmentNotification($assessment));
                                        }
                                    }
                                }
                            } elseif ($assessmentType == Config::ASSESSMENT_TYPES['CASH_IN_LIEU']) {
                                $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                if (count($headAssessors) > 0) {
                                    foreach ($headAssessors as $headAssessor) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        $data = [
                                            'claim' => $assessment->claim->claimNo,
                                            'reg' => $assessment->claim->vehicleRegNo,
                                            'headAssessor' => $headAssessor->firstName,
                                            'email' => $headAssessor->email
                                        ];
                                        $email_add = $data['email'];

                                        $email = [
                                            'subject' => 'Assessment Report - ' . $data['reg'],
                                            'from_user_email' => 'noreply@jubileeinsurance.com',
                                            'message' => "
                            Hi, <br>
                            This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                            " . $assessorName . " has completed their assessment report. <br>
                            Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                            <u><i><strong>Details are as below:</strong></i></u>
                            <strong>Status: </strong> " . Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                            <strong>Amount: </strong> " . $total . " <br>
                            <strong>Salvage: </strong>" . $salvage . " <br>
                            <strong>PAV: </strong>" . $pav . "
                            <br><br>

                            Regards, <br><br>
                            System Administrator, <br>
                            I.T Department <br>
                            Jubilee Insurance.
                        ",
                                        ];
                                        //                                        $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                        //                                        SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                        Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                    }
                                }
                            } elseif ($assessmentType == Config::ASSESSMENT_TYPES['TOTAL_LOSS']) {
                                $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                if (count($headAssessors) > 0) {
                                    foreach ($headAssessors as $headAssessor) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        $data = [
                                            'claim' => $assessment->claim->claimNo,
                                            'reg' => $assessment->claim->vehicleRegNo,
                                            'headAssessor' => $headAssessor->firstName,
                                            'email' => $headAssessor->email
                                        ];
                                        $email_add = $data['email'];

                                        $email = [
                                            'subject' => 'Assessment Report - ' . $data['reg'],
                                            'from_user_email' => 'noreply@jubileeinsurance.com',
                                            'message' => "
                            Hi, <br>
                            This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                            " . $assessorName . " has completed their assessment report. <br>
                            Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                            <u><i><strong>Details are as below:</strong></i></u>
                            <strong>Status: </strong> " . Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                            <strong>Amount: </strong> " . $total . " <br>
                            <strong>Salvage: </strong>" . $salvage . " <br>
                            <strong>PAV: </strong>" . $pav . "
                            <br><br>

                            Regards, <br><br>
                            System Administrator, <br>
                            I.T Department <br>
                            Jubilee Insurance.
                        ",
                                        ];
                                        //                                        $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                        //                                        SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                        Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                    }
                                }
                            }
                        } else {
                            $response = array(
                                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                            );
                            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                                "An assessment was not created. An error occurred");
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to create an assessments. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }
    public function submitEditedSupplementary(Request $request)
    {
        try {
            $totalImages = $request->totalImages;
            $assessmentID = $request->assessmentID;
            $partsData = json_decode($request->partsData, true);
            $jobsData = json_decode($request->jobsData, true);
            $isDraft = $request->isDraft;
            $assessmentType = $request->assessmentType;
            $curDate = $this->functions->curlDate();
            $drafted = $request->drafted;
            // echo(json_encode($partsData));
            // exit();
            if ($drafted == 1) {
                $affectedRows = AssessmentItem::where(["assessmentID" => $assessmentID])->delete();
                if ($affectedRows > 0) {
                    $affectedJobDetailRows = JobDetail::where(["assessmentID" => $assessmentID])->delete();
                    if ($affectedJobDetailRows > 0) {
                        $documents = Document::where(["assessmentID" => $assessmentID])->get();
                        if (count($documents) > 0) {
                            $affectedDocumentRows = Document::where(["assessmentID" => $assessmentID])->delete();
                            if ($affectedDocumentRows > 0) {
                                foreach ($documents as $document) {
                                    $image_path = "documents/" . $document->name;  // Value is not URL but directory file path
                                    if (File::exists($image_path)) {
                                        File::delete($image_path);
                                    }
                                }
                            }
                        }
                    }
                }
            }
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
                        "segment" => Config::$ASSESSMENT_SEGMENTS["SUPPLEMENTARY"]["ID"]
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
            $reconstruction = !empty($jobsData['reconstruction']) ? $jobsData['reconstruction'] : 0;
            $gas = !empty($jobsData['gas']) ? $jobsData['gas'] : 0;
            $welding = !empty($jobsData['welding']) ? $jobsData['welding'] : 0;
            $sumTotal = !empty($jobsData['sumTotal']) ? $jobsData['sumTotal'] : 0;
            $pav = !empty($jobsData['pav']) ? $jobsData['pav'] : 0;
            $salvage = !empty($jobsData['salvage']) ? $jobsData['salvage'] : 0;
            $totalLoss = !empty($jobsData['totalLoss']) ? $jobsData['totalLoss'] : 0;
            $cause = !empty($jobsData['cause']) ? $jobsData['cause'] : null;
            $note = !empty($jobsData['note']) ? $jobsData['note'] : null;
            $chassisNumber = !empty($jobsData['chassisNumber']) ? $jobsData['chassisNumber'] : '';

            if ($chassisNumber != '') {
                $assessment = Assessment::where(['id' => $assessmentID])->first();
                Claim::where(['id' => $assessment->claimID])->update([
                    "chassisNumber" => $chassisNumber
                ]);
            }
            foreach ($partsData as $partDetail) {

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
                    "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                    "createdBy" => Config::ACTIVE,
                    "dateCreated" => $curDate
                );
                $assessmentItems[] = $assessmentItem;
            }
            // echo($assessmentItems);
            // exit();

            $collection = collect($assessmentItems);


            $unique = $collection->unique('partID');

            $save = AssessmentItem::insert($unique->values()->all());
            // print_r($collection);
            // exit();
            if ($save) {
                //Sum of parts
                $sum = AssessmentItem::where('assessmentID', $assessmentID)->sum('total');

                //Sum of other charges
                $others = (is_numeric($labour)) + (is_numeric($paint)) + (is_numeric($miscellaneous)) + (is_numeric($primer))
                    + (is_numeric($jigging)) + (is_numeric($reconstruction)) + (is_numeric($gas)) + (is_numeric($welding));

                if ($assessmentType == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                    $total = ($sum + $others) * 1.14;
                } elseif ($assessmentType == Config::ASSESSMENT_TYPES['CASH_IN_LIEU']) {
                    $total = ($sum * 0.9) + $others;
                } elseif ($assessmentType == Config::ASSESSMENT_TYPES['TOTAL_LOSS']) {
                    $total = ($sum + $others) * 1.14;
                }
                $assessorName = Auth::user()->firstName . ' ' . Auth::user()->lastName;
                $assessmentStatusID = $isDraft == 1 ? Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id'] : Config::$STATUSES['ASSESSMENT']['ASSESSED']['id'];
                Assessment::where(['id' => $assessmentID])->update([
                    "cause" => $cause,
                    "note" => $note,
                    "salvage" => $salvage,
                    "pav" => $pav,
                    "totalCost" => $total,
                    "totalLoss" => $totalLoss,
                    "assessmentTypeID" => $assessmentType,
                    "assessmentStatusID" => $assessmentStatusID,
                    "assessedAt" => $curDate,
                    "dateModified" => $curDate
                ]);
                $detail = JobDetail::where('assessmentID', $assessmentID)->exists();
                $jobs = array();

                if ($detail) {
                } else {
                    if ($labour > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["LABOUR"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["LABOUR"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $labour
                        );
                        $jobs[] = $job;
                    }
                    if ($paint > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["PAINTING"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["PAINTING"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $paint
                        );
                        $jobs[] = $job;
                    }
                    if ($miscellaneous > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["MISCELLANEOUS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["MISCELLANEOUS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $miscellaneous
                        );
                        $jobs[] = $job;
                    }
                    if ($primer > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["PRIMER"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["PRIMER"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $primer
                        );
                        $jobs[] = $job;
                    }
                    if ($jigging > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["JIGGING"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["JIGGING"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $jigging
                        );
                        $jobs[] = $job;
                    }
                    if ($reconstruction > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["RECONSTRUCTION"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["RECONSTRUCTION"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $reconstruction
                        );
                        $jobs[] = $job;
                    }
                    if ($gas > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["AC_GAS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["AC_GAS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $gas
                        );
                        $jobs[] = $job;
                    }
                    if ($welding > 0) {
                        $job = array(
                            "assessmentID" => $assessmentID,
                            "name" => Config::$JOB_TYPES["WELDING_GAS"]["TITLE"],
                            "jobType" => Config::$JOB_TYPES["WELDING_GAS"]["ID"],
                            "jobCategory" => Config::$JOB_CATEGORIES['REPAIR']['ID'],
                            "segment" => Config::$ASSESSMENT_SEGMENTS['SUPPLEMENTARY']['ID'],
                            "cost" => $welding
                        );
                        $jobs[] = $job;
                    }
                    foreach ($jobs as $job) {
                        $jobDetail = JobDetail::create($job);
                        if ($isDraft == 1 & $jobDetail->id > 0) {
                            $response = array(
                                "STATUS_CODE" => Config::SUCCESS_CODE,
                                "STATUS_MESSAGE" => "Congratulation!, You have successfully Saved an assessment as Draft"
                            );
                        } else if ($isDraft == 0 & $jobDetail->id > 0) {
                            $response = array(
                                "STATUS_CODE" => Config::SUCCESS_CODE,
                                "STATUS_MESSAGE" => "Congratulation!, You have successfully created an assessment"
                            );
                            if ($assessmentType == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                                if ($total > Config::HEAD_ASSESSOR_THRESHOLD) {
                                    $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                    if (count($headAssessors) > 0) {
                                        foreach ($headAssessors as $headAssessor) {
                                            $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                            $data = [
                                                'claim' => $assessment->claim->claimNo,
                                                'reg' => $assessment->claim->vehicleRegNo,
                                                'headAssessor' => $headAssessor->firstName,
                                                'email' => $headAssessor->email
                                            ];
                                            $email_add = $data['email'];

                                            $email = [
                                                'subject' => 'Assessment Report - ' . $data['reg'],
                                                'from_user_email' => 'noreply@jubileeinsurance.com',
                                                'message' => "
                                    Hi, <br>
                                    This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                                    " . $assessorName . " has completed their assessment report. <br>
                                    Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                                    <u><i><strong>Details are as below:</strong></i></u>
                                    <strong>Status: </strong> " . Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                                    <strong>Amount: </strong> " . $total . "  <br>
                                    <strong>Salvage: </strong>" . $salvage . " <br>
                                    <strong>PAV: </strong>" . $pav . "
                                    <br><br>

                                    Regards, <br><br>
                                    System Administrator, <br>
                                    I.T Department <br>
                                    Jubilee Insurance.
                                ",
                                            ];
                                            //                                            $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                            //                                            SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                            Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                        }
                                    }
                                } else {
                                    $assistantHeadAssessors = User::role(Config::$ROLES['ASSISTANT-HEAD'])->get(); // Returns only users with the role 'Head Assessor'
                                    if (count($assistantHeadAssessors) > 0) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        foreach ($assistantHeadAssessors as $assistantHeadAssessor) {
                                            $data = [
                                                'claim' => $assessment->claim->claimNo,
                                                'reg' => $assessment->claim->vehicleRegNo,
                                                'assistantHeadAssessor' => $assistantHeadAssessor->firstName,
                                                'email' => $assistantHeadAssessor->email
                                            ];
                                            $email_add = $data['email'];

                                            $email = [
                                                'subject' => 'Assessment Report - ' . $data['reg'],
                                                'from_user_email' => 'noreply@jubileeinsurance.com',
                                                'message' => "
                                    Hi, <br>
                                    This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                                    " . $assessorName . " has completed their assessment report. <br>
                                    Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                                    <u><i><strong>Details are as below:</strong></i></u>
                                    <strong>Status: </strong> " . Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                                    <strong>Amount: </strong> " . $total . "  <br>
                                    <strong>Salvage: </strong>" . $salvage . " <br>
                                    <strong>PAV: </strong>" . $pav . "
                                    <br><br>

                                    Regards, <br><br>
                                    System Administrator, <br>
                                    I.T Department <br>
                                    Jubilee Insurance.
                                ",
                                            ];
                                            //                                            $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                            //                                            SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                            Notification::send($assistantHeadAssessor, new NewAssessmentNotification($assessment));
                                        }
                                    }
                                }
                            } elseif ($assessmentType == Config::ASSESSMENT_TYPES['CASH_IN_LIEU']) {
                                $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                if (count($headAssessors) > 0) {
                                    foreach ($headAssessors as $headAssessor) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        $data = [
                                            'claim' => $assessment->claim->claimNo,
                                            'reg' => $assessment->claim->vehicleRegNo,
                                            'headAssessor' => $headAssessor->firstName,
                                            'email' => $headAssessor->email
                                        ];
                                        $email_add = $data['email'];

                                        $email = [
                                            'subject' => 'Assessment Report - ' . $data['reg'],
                                            'from_user_email' => 'noreply@jubileeinsurance.com',
                                            'message' => "
                            Hi, <br>
                            This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                            " . $assessorName . " has completed their assessment report. <br>
                            Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                            <u><i><strong>Details are as below:</strong></i></u>
                            <strong>Status: </strong> " . Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                            <strong>Amount: </strong> " . $total . " <br>
                            <strong>Salvage: </strong>" . $salvage . " <br>
                            <strong>PAV: </strong>" . $pav . "
                            <br><br>

                            Regards, <br><br>
                            System Administrator, <br>
                            I.T Department <br>
                            Jubilee Insurance.
                        ",
                                        ];
                                        //                                        $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                        //                                        SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                        Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                    }
                                }
                            } elseif ($assessmentType == Config::ASSESSMENT_TYPES['TOTAL_LOSS']) {
                                $headAssessors = User::role(Config::$ROLES['HEAD-ASSESSOR'])->get(); // Returns only users with the role 'Head Assessor'
                                if (count($headAssessors) > 0) {
                                    foreach ($headAssessors as $headAssessor) {
                                        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
                                        $data = [
                                            'claim' => $assessment->claim->claimNo,
                                            'reg' => $assessment->claim->vehicleRegNo,
                                            'headAssessor' => $headAssessor->firstName,
                                            'email' => $headAssessor->email
                                        ];
                                        $email_add = $data['email'];

                                        $email = [
                                            'subject' => 'Assessment Report - ' . $data['reg'],
                                            'from_user_email' => 'noreply@jubileeinsurance.com',
                                            'message' => "
                            Hi, <br>
                            This is in reference to claim number <strong>" . $data['claim'] . " </strong><br>
                            " . $assessorName . " has completed their assessment report. <br>
                            Login to the <a href=" . url('/') . ">portal</a> to view it. <br>
                            <u><i><strong>Details are as below:</strong></i></u>
                            <strong>Status: </strong> " . Config::DISPLAY_ASSESSMENT_TYPES[$assessmentType] . "  <br>
                            <strong>Amount: </strong> " . $total . " <br>
                            <strong>Salvage: </strong>" . $salvage . " <br>
                            <strong>PAV: </strong>" . $pav . "
                            <br><br>

                            Regards, <br><br>
                            System Administrator, <br>
                            I.T Department <br>
                            Jubilee Insurance.
                        ",
                                        ];
                                        //                                        $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
                                        //                                        SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
                                        Notification::send($headAssessor, new NewAssessmentNotification($assessment));
                                    }
                                }
                            }
                        } else {
                            $response = array(
                                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                            );
                            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                                "An assessment was not created. An error occurred");
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to create an assessments. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function submitPriceChange(Request $request)
    {
        try {
            $totalImages = $request->totalImages;
            $assessmentID = $request->assessmentID;
            $partsData = json_decode($request->partsData, true);
            foreach ($partsData as $partDetail) {

                $partID = $partDetail['partID'];
                $current = $partDetail['current'];
                $difference= $partDetail['difference'];
                AssessmentItem::where(["id"=>$partID])->update([
                        "current"=>$current,
                        "difference"=>$difference,
                    ]
                );
            }

            Assessment::where("id",$assessmentID)->update([
                "changeTypeID"=>Config::$CHANGES["PRICE-CHANGE"]["id"],
            ]);

            $difference = AssessmentItem::where('assessmentID', $assessmentID)
                ->whereNotNull('current')
                ->sum('difference');

            $assessment = Assessment::where('id', $assessmentID)->first();


            if ($assessment->assessmentTypeID == Config::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE']) {
                $difference = ((Config::CURRENT_TOTAL_PERCENTAGE)/Config::INITIAL_PERCENTAGE * $difference);
                $price_change = $assessment->totalCost +  $difference;
            } else {
                $difference = (Config::MARK_UP * $difference);
                $price_change = $assessment->totalCost + $difference;
            }

            $update = Assessment::where('id', $assessmentID)
                ->update([
                    'priceChange' => $difference,
                    'totalChange' => $price_change
                ]);
            $pChange=PriceChange::where('assessmentID',$assessmentID)->first();
            if(isset($pChange->id))
            {
                $pChange->approvedBy=null;
                $pChange->approvedAt=null;
                $pChange->finalApproved=null;
                $pChange->finalApprover=null;
                $pChange->finalApprovedAt=null;
                $pChange->changed=null;
                $pChange->save();

            }else {

                $priceChange = PriceChange::firstOrNew(array('assessmentID' => $assessmentID));
                $priceChange->assessedBy = Auth::user()->id;
                $priceChange->previousTotal = $assessment->totalCost;
                $priceChange->currentTotal = $price_change;
                $priceChange->priceDifference = $difference;

                $priceChange->save();
            }

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
            $response = array(
                "STATUS_CODE" => Config::SUCCESS_CODE,
                "STATUS_MESSAGE" => "Congratulation!, You have successfully implemented price change"
            );
        } catch (\Exception $e) {
            $response = array(
                "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
            );
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to create an assessments. Error message " . $e->getMessage());
        }
        return json_encode($response);
    }

    public function priceChange(Request $request, $assessmentID)
    {
        $draftAssessment = Assessment::where(['id' => $assessmentID, 'assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['APPROVED']['id']])->with('claim')->first();
        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
        $carDetails = CarModel::where(["modelCode" => isset($assessment->claim->carModelCode) ? $assessment->claim->carModelCode : 0])->first();
//        $remarks = Remarks::all();
//        $parts = Part::all();
        $remarks = Cache::remember('remarks',Config::CACHE_EXPIRY_PERIOD,function (){
            return Remarks::select("id","name")->get();
        });
        $parts = Cache::remember('parts',Config::CACHE_EXPIRY_PERIOD,function (){
            return Part::select("id","name")->get();
        });
        $assessmentItems = AssessmentItem::where(["assessmentID" => isset($draftAssessment->id) ? $draftAssessment->id : 0])->with("part")->get();;
        $jobDetails = JobDetail::where(["assessmentID" => isset($draftAssessment->id) ? $draftAssessment->id : 0])->get();
        $jobDraftDetail = [];
        return view('assessor.price', ['assessment' => $assessment, 'remarks' => $remarks, 'parts' => $parts, 'assessmentItems' => $assessmentItems, "jobDraftDetail" => $jobDraftDetail, "draftAssessment" => $draftAssessment, "carDetails" => $carDetails]);
    }

    public function priceChangeReport(Request $request)
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
        return view("assessor.price-change-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor]);
    }
}
