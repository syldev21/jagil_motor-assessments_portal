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
use App\ReInspection;
use App\Remarks;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $assessments = Assessment::where(['assessmentStatusID' => $assessmentStatusID, "assessedBy" => $id])->with('claim')->with('user')->with('approver')->with('assessor')->orderBy('dateCreated', 'DESC')->get();
            return view('assessor.assessments', ['assessments' => $assessments, 'assessmentStatusID' => $assessmentStatusID]);
        } catch (\Exception $e) {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to fetch assessments. Error message " . $e->getMessage());
        }
    }

    public function fillAssessmentReport(Request $request, $assessmentID)
    {
        $draftAssessment = Assessment::where(['id' => $assessmentID, 'assessmentStatusID' => Config::$STATUSES['ASSESSMENT']['IS-DRAFT']['id']])->with('claim')->first();
        $assessment = Assessment::where(['id' => $assessmentID])->with('claim')->first();
        $carDetails = CarModel::where(["modelCode" => isset($assessment->claim->carModelCode) ? $assessment->claim->carModelCode : 0])->first();
        $remarks = Remarks::all();
        $parts = Part::all();
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

        return view('assessor.assessment-report', ['assessment' => $assessment, 'remarks' => $remarks, 'parts' => $parts, 'assessmentItems' => $assessmentItems, "jobDraftDetail" => $jobDraftDetail, "draftAssessment" => $draftAssessment, "carDetails" => $carDetails]);
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
            $response = array();
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
                    $documents = Document::create([
                        "claimID" => $claimID,
                        "name" => $picture,
                        "mime" => $extension,
                        "size" => $size,
                        "documentType" => Config::$DOCUMENT_TYPES["IMAGE"]["ID"],
                        "url" => $path,
                        "segment" => Config::$ASSESSMENT_SEGMENTS["ASSESSMENT"]["ID"]
                    ]);
                    if ($documents->id > 0) {
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
                        "STATUS_CODE" => Config::INVALID_PAYLOAD,
                        "STATUS_MESSAGE" => "Invalid data, Confirm your data and try again later"
                    );
                }
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
            $totalImages = $request->totalImages;
            $assessmentID = $request->assessmentID;
            $partsData = json_decode($request->partsData, true);
            $jobsData = json_decode($request->jobsData, true);
            $isDraft = $request->isDraft;
            $assessmentType = $request->assessmentType;
            $curDate = $this->functions->curlDate();
            $drafted = $request->drafted;
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
//                                            $emailResult = InfobipEmailHelper::sendEmail($email, $email_add);
//                                            SMSHelper::sendSMS('Hello ' . $headAssessor->firstName . ', An Assessment for vehicle : ' . $data['reg'] . ' has been Completed. You are required review and action', $headAssessor->MSISDN);
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
        return view("assessor.view-assessment-report",['assessment' => $assessment,"assessmentItems" => $assessmentItems,"jobDetails" => $jobDetails,"insured"=>$insured,'documents'=> $documents,'adjuster'=>$adjuster,'assessor'=>$assessor]);
    }
    public function submitReInspection(Request $request)
    {
        try {
            $repaired = json_decode($request->repaired,true);
            $replaced = json_decode($request->replaced,true);
            $cil = json_decode($request->cil,true);
            $reused = json_decode($request->reused,true);
            $assessmentID = $request->assessmentID;
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
            if (isset($repaired) || isset($replaced) || isset($cil) || count($reused)) {
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

                $inspected = ReInspection::where('assessmentID', $assessmentID)->first();

                if (count($inspected) > 0) {
                    ReInspection::where('assessmentID',$assessmentID)
                        ->update([
                            'labor' => $labor,
                            'add_labor' => $addLabor,
                            'total' => $finalTotal,
                            'notes' => $request->notes,
                            'modifiedBy' => Auth::user()->id,
                            'dateModified' => date('Y-m-d H:i:s')
                        ]);
                }else
                {
                    ReInspection::create([
                        'assessmentID' => $assessmentID,
                        'labor' => $labor,
                        'add_labor' => $addLabor,
                        'total' => $finalTotal,
                        'createdBy' => Auth::user()->id,
                        'notes' => $request->notes,
                        'dateCreated' => date('Y-m-d H:i:s'),
                    ]);
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
}
