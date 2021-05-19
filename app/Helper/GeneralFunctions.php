<?php


namespace App\Helper;


use App\ActivityLog;
use App\Assessment;
use App\AssessmentItem;
use App\Claim;
use App\Conf\Config;
use App\Garage;
use App\PriceChange;
use App\Role;
use App\UserHasRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralFunctions
{
    private $log;

    function __construct()
    {
        $this->log = new CustomLogger();
    }

    public function curlDate()
    {
        date_default_timezone_set(Config::TIME_ZONE);
        return date('Y-m-d H:i:s');
    }

    public function search($userID, $fromDate = NULL, $toDate = NULL, $vehicleRegNo = NULL)
    {
        if (isset($vehicleRegNo) && isset($userID)) {
            $assessors = DB::table('users')->where('userID', $userID)->get();
            $claims = Claim::where("vehicleRegNo", $vehicleRegNo)->get();
            if (count($claims) > 0) {
                $data = array(
                    "assessors" => $assessors,
                    "claims" => $claims
                );
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Claims data fetched successfully",
                    "DATA" => $data
                );
            } else {
                $response = array(
                    "STATUS_CODE" => Config::NO_RECORDS_FOUND,
                    "STATUS_MESSAGE" => "No vehicle found with Reg No." . $vehicleRegNo,
                    "DATA" => []
                );
            }
        } elseif (isset($fromDate) && isset($toDate) && isset($userID)) {
            try {
                $assessors = DB::table('users')->where('userID', $userID)->get();
                $garages = Garage::all();
                $claims = Claim::where("dateCreated", '>=', $fromDate)->where("dateCreated", "<=", $toDate)->get();
                $data = array(
                    "assessors" => $assessors,
                    "garages" => $garages,
                    "claims" => $claims
                );
                $response = array(
                    "STATUS_CODE" => Config::SUCCESS_CODE,
                    "STATUS_MESSAGE" => "Claims data fetched successfully",
                    "DATA" => $data
                );
            } catch (\Exception $e) {
                $response = array(
                    "STATUS_CODE" => Config::GENERIC_ERROR_CODE,
                    "STATUS_MESSAGE" => Config::GENERIC_ERROR_MESSAGE
                );
                $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                    "An exception occurred when trying to search for claims. Error message " . $e->getMessage());
            }
        }
        return json_encode($response);
    }

    public function formatDate($date)
    {
        return date('Y-m-d',strtotime($date));
    }

    public function humanTiming($time)
    {

        $time = time() - $time; // to get the time since that moment
        $time = ($time < 1) ? 1 : $time;
        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
        }
    }

    public static function getSumOfTotalItems($assessmentID)
    {
        $sumTotal =AssessmentItem::where('assessmentID', $assessmentID)->sum('total');
        $priceChange = PriceChange::where('assessmentID', $assessmentID)->first();
        $difference = 0;
        if(isset($priceChange->finalApprovedAt)) {
            $difference = AssessmentItem::where('assessmentID', $assessmentID)
                ->whereNotNull('current')
                ->sum('totalDifference');
        }
        $total = $sumTotal+$difference;
        return $total;
    }
    public function logActivity($data)
    {
        try {
            ActivityLog::create([
                "vehicleRegNo" => isset($data['vehicleRegNo']) ? $data['vehicleRegNo'] : '',
                "claimNo" => isset($data['claimNo']) ? $data['claimNo'] : '',
                "policyNo" => isset($data['policyNo']) ? $data['policyNo'] : '',
                "userID" => isset($data['userID']) ? $data['userID'] : '',
                "role" => isset($data['role']) ? $data['role'] : '',
                "activity" => isset($data['activity']) ? $data['activity'] : '',
                "notification" => isset($data['notification']) ? $data['notification'] : '',
                "notificationTo" => isset($data['notificationTo']) ? $data['notificationTo'] : '',
                "cc" => isset($data['cc']) ? $data['cc'] : '',
                "notificationType" => isset($data['notificationType']) ? $data['notificationType'] : '',
                "createdBy" => Auth::user()->id,
                "dateCreated" => $this->curlDate()
            ]);
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "Information logged successfully for activity ".$data['activity']);
        }catch (\Exception $e)
        {
            $this->log->motorAssessmentInfoLogger->info("FUNCTION " . __METHOD__ . " " . " LINE " . __LINE__ .
                "An exception occurred when trying to log an event. Error message " . $e->getMessage());
        }
    }
    public function generateCurlget($url, $params = null, $headers)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (Exception $ex) {


        }
    }
}
