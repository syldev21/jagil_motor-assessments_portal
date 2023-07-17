<?php


namespace App\Conf;


class Config
{
    /**
     * Log Variables
     */
    const MOTOR_ASSESSMENT_APP_NAME = "MOTOR_ASSESSMENT";
//   const MOTOR_ASSESSMENT_DEBUG = "C:\\xampp\\htdocs\\logs\\debug.log";
//  const MOTOR_ASSESSMENT_INFO = "C:\\xampp\\htdocs\\logs\\info.log";
//    const MOTOR_ASSESSMENT_ERROR = "C:\\xampp\\htdocs\\logs\\error.log";
//    const MOTOR_ASSESSMENT_FATAL = "C:\\xampp\\htdocs\\logs\\fatal.log";

	  const MOTOR_ASSESSMENT_DEBUG = "/var/www/assessment_jubileeallianz_com_v2/storage/logs/app-logs/debug.log";
	  const MOTOR_ASSESSMENT_INFO = "/var/www/assessment_jubileeallianz_com_v2/storage/logs/app-logs/info.log";
    const MOTOR_ASSESSMENT_ERROR = "/var/www/assessment_jubileeallianz_com_v2/storage/logs/app-logs/error.log";// "C:\\xampp\\htdocs\\logs\\error.log";
    const MOTOR_ASSESSMENT_FATAL = "/var/www/assessment_jubileeallianz_com_v2/storage/logs/app-logs/fatal.log"; // "C:\\xampp\\htdocs\\logs\\fatal.log";



    /**
     * Setting the default timezone to kenya
     */
    const TIME_ZONE = "Africa/Nairobi";

    const CURRENCY = "KES";

    const SUCCESS_CODE = 2000;
    const GENERIC_ERROR_CODE = 4000;
    const NO_RECORDS_FOUND = 3000;
    const RECORD_ALREADY_EXISTS = 5000;
    const INVALID_PAYLOAD = 6000;
    const GENERIC_ERROR_MESSAGE = "We are experiencing technical difficulty, Forward the issue to admin";
    const ACTIVE = 1;
    const INACTIVE = 0;
    static $CUSTOMER_TYPE = array(
        "INSURED_CUSTOMER" => 'I',
        "GARAGE_CUSTOMER" => 'G'
    );
    static $DISPLAY_STATUSES = array(
        "CLAIM" => array(
            1 => "Uploaded",
            2=> "Assigned",
            3 => "Re-assigned",
            4 => "Released"
        ),
        "ASSESSMENT" => array(
            1 => "Assigned",
            2 => "Drafted",
            3 => "Assessed",
            4 => "Provisional Approval",
            5=> "Approved",
            6=> "Changes Due"
        )
    );
    static $STATUSES = array(
        "CLAIM" => array(
            "UPLOADED" => array(
                "id" => 1,
                "text" => "Uploaded"
            ),
            "ASSIGNED" => array(
                "id" => 2,
                "text" => "Assigned"
            ),
            "RE-INSPECTED" => array(
                "id" => 3,
                "text" => "Re-inspected"
            ),
            "RELEASED"=> array(
                "id" => 4,
                "text" => "Released"
            )
        ),
        "ASSESSMENT" => array(
            "ASSIGNED" => array(
                "id" => 1,
                "text" => "Assigned"
            ),
            "IS-DRAFT" => array(
                "id" => 2,
                "text" => "Drafted"
            ),
            "ASSESSED" => array(
                "id" => 3,
                "text" => "Assessed"
            ),
            "PROVISIONAL-APPROVAL" => array(
                "id" => 4,
                "text" => "Provisional Approval"
            ),
            "APPROVED" => array(
                "id" => 5,
                "text" => "Approved"
            ),
            "CHANGES-DUE" => array(
                "id" => 6,
                "text" => "Changes Due"
            )
        ),
        "PRICE-CHANGE" => array(
            "HA-APPROVE" => array(
                "id" => 1,
                "text" => "price change approval"
            ),
            "AM-APPROVE" => array(
                "id" => 3,
                "text" => "price change approval"
            ),
            "APPROVED" => array(
                "id" => 4,
                "text" => "price change approval"
            )
        )
    );
    const HEAD_ASSESSOR = 'Head Assessor';
    const ASSESSOR = 'Assessor';
    const DEFAULT_STATUS = 0;
    static $STATUS_TYPES = array(
        "CLAIM" => 1,
        "ASSESSMENT" =>2
    );

    const DATES_LIMIT =200;
    static $ROLES = array(
        "ASSESSOR" => "Assessor",
        "ADJUSTER" => "Adjuster",
        "ADMIN" => "Admin",
        "HEAD-ASSESSOR" => "Head Assessor",
        "ASSESSMENT-MANAGER" => "Assessment Manager",
        "MANAGER" => "Manager",
        "ASSISTANT-HEAD" => "Assistant Head",
        "RE-INSPECTION-OFFICER" => "Re-inspection Officer",
        "UNDERWRITER" => "Underwriter",
        "VALUER" => "Valuer",
        "CUSTOMER-SERVICE" => "Customer Service",
        "NHIF"=>"Nhif"
    );

    const START_YEAR = 1960;

    const CURRENT_VAT = 14;
    const CURRENT_VAT_PERCENTAGE = '14%';
    const INITIAL_PERCENTAGE = 100;
    const CURRENT_TOTAL_PERCENTAGE = self::INITIAL_PERCENTAGE + self::CURRENT_VAT;
    const VAT_REDUCTION_DATE = '2020-05-01 12:00:00';
    const VAT_END_DATE = '2020-12-31:00:00:00';

    const VAT = 16;
    const VAT_PERCENTAGE = '16%';
    const TOTAL_PERCENTAGE = self::INITIAL_PERCENTAGE + self::VAT;
    const MARK_UP = 0.9;

    static $JOB_TYPES = array(
        "LABOUR" =>array(
            "ID" => 1,
            "TITLE" => "Labour"
        ),
        "PAINTING" =>array(
            "ID" => 2,
            "TITLE" => "Painting"
        ),
        "MISCELLANEOUS" =>array(
            "ID" => 3,
            "TITLE" => "Miscellaneous"
        ),
        "PRIMER" =>array(
            "ID" => 4,
            "TITLE" => "2k Primer"
        ),
        "JIGGING" =>array(
            "ID" => 5,
            "TITLE" => "Jigging"
        ),
        "RECONSTRUCTION" =>array(
            "ID" => 6,
            "TITLE" => "Reconstruction"
        ),
        "AC_GAS" =>array(
            "ID" => 7,
            "TITLE" => "AC/Gas"
        ),
        "WELDING_GAS" =>array(
            "ID" => 8,
            "TITLE" => "Welding/Gas"
        ),
        "BUMPER_FIBRE" =>array(
            "ID" => 9,
            "TITLE" => "Bumper Fibre"
        ),
        "DAM_KIT" =>array(
            "ID" => 10,
            "TITLE" => "Dam Kit"
        ),
    );
    static $JOB_CATEGORIES = array(
        "REPAIR" => array(
            "ID" => 1,
            "TITLE" => "Repair"
        ),
        "REPLACE" => array(
            "ID" => 2,
            "TITLE" => "Replace"
        ),
        "CIL" => array(
            "ID" => 3,
            "TITLE" => "Cash In Lieu"
        ),
        "REUSE" => array(
            "ID" => 4,
            "TITLE" => "Re_used"
        )
    );
    static $ASSESSMENT_SEGMENTS = array(
        "ASSESSMENT" => array(
            "ID" => 1,
            "TITLE" => "Assessment"
        ),
        "RE_ASSESSMENT" => array(
            "ID" => 2,
            "TITLE" => "Re-assessment"
        ),
        "INSPECTION" => array(
            "ID" => 3,
            "TITLE" => "Inspection"
        ),
        "RE_INSPECTION" => array(
            "ID" => 4,
            "TITLE" => "Re-inspection"
        ),
        "SUPPLEMENTARY" => array(
            "ID" => 5,
            "TITLE" => "Supplementary"
        )
    );

    static $DOCUMENT_TYPES =array(
        "IMAGE"=>array(
            "ID" => 1,
            "TITLE" => "IMAGE"
        ),
        "PDF"=>array(
            "ID" => 2,
            "TITLE" => "pdf"
        )
    );
    const ASSESSMENT_TYPES = array(
        "AUTHORITY_TO_GARAGE" => 1,
        "CASH_IN_LIEU" => 2,
        "TOTAL_LOSS" => 3
    );
    static $ASSESSMENT_TYPES_ARRAY = array(self::ASSESSMENT_TYPES['AUTHORITY_TO_GARAGE'],self::ASSESSMENT_TYPES['CASH_IN_LIEU'],self::ASSESSMENT_TYPES['TOTAL_LOSS']);
    const DISPLAY_ASSESSMENT_TYPES = array(
        1 => "Authority to garage",
        2 => "Cash in Lieu",
        3 => "Total Loss"
    );

    const APPROVE = 1;
    const HALT =2;

    const ASSESSMENT_MANAGER = "Assessment Center Manager";

    const JUBILEE_NO_REPLY_EMAIL = "noreply@jubileeinsurance.com";
    const JUBILEE_ALLIANZ_TALK_TO_US_EMAIL = 'talk2us@allianz.com';
    const JUBILEE_ALLIANZ_TALK_TO_US_NAME = 'talk2us';
    const JUBILEE_REPLY_EMAIL = "jazrecover@allianz.com";
//    const JUBILEE_NO_REPLY_EMAIL_USERNAME ="JubileeKenya";
    const JUBILEE_NO_REPLY_EMAIL_USERNAME ="ADMIN_General";
//    const JUBILEE_NO_REPLY_EMAIL_PASSWORD = "@Jubilee981267#";
    const JUBILEE_NO_REPLY_EMAIL_PASSWORD = "Geeks@2022";

//    const JUBILEE_SMS_USERNAME ="JubileeKenya";
//    const JUBILEE_SMS_PASSWORD = "Jubilee4321";
    const JUBILEE_SMS_USERNAME ="ADMIN_General";
    const JUBILEE_SMS_PASSWORD = "Geeks@2022";


    static $USER_TYPES = array(
        "INTERNAL" => array(
            "ID" => 1,
            "NAME" => "Internal"
        ),
        "EXTERNAL" => array(
            "ID" => 2,
            "NAME" => "External"
        ),
        "HOME FIBER CUSTOMER" => array(
            "ID" => 3,
            "NAME" => "HF Customer"
        )

    );
    const HEAD_ASSESSOR_THRESHOLD = 300000;

    const DEFAULT_PASSWORD = "123456";

    static $CHANGES=array(

        "PRICE-CHANGE"=>array(
            "id"=>1,
            "text"=>"pending price change approval"
        ),
        "CHANGE-REQUEST"=>array(
            "id"=>2,
            "text"=>"pending change request approval"
        )

    );

    const CACHE_EXPIRY_PERIOD = 33600;

    const BASE_URL = 'http://127.0.0.1:8000';

    const DEFAULT_TIMEZONE ='Africa/Nairobi';
    //Date when markup was removed from the
    // sum of total parts so as to be included on part unit price
    const MARK_UP_CUT_OFF_DATE = '2021-02-22 12:00:00';
    const NEW_MARKUP = 1;
    const DATE_RANGE = 5;
    const PDF_TYPES = array(
        "CLAIM_FORM" => array(
            "ID"=>1,
            "TITLE" => "Claim Form"
        ),
        "INVOICE" => array(
            "ID"=>2,
            "TITLE" => "Invoice"
        )
    );
    const NOTIFICATION_TYPES = array(
        "EMAIL" => "Email",
        "SMS" => "SMS"
    );
    const ACTIVITIES = array(
        "CLAIM_UPLOAD" => "Claim Upload",
        "ASSIGN_ASSESSOR" => "Assign Assessor",
        "SUBMITTING_ASSESSMENT_REPORT" => "Submission of Assessment Report",
        "PROVISIONAL_APPROVAL" => "Provisional Approval",
        "REQUEST_CHANGES" => "Request Assessment Changes",
        "FINAL_APPROVAL" => "Final Approval",
        "GENERIC_NOTIFICATION" => "Generic Notification"
    );
    const FLAG_THRESHOLD = 30;
//    const DMS_BASE_URL = "http://127.0.0.1:8000/dms/fetchDMSDocuments";
    const DMS_BASE_URL =   self::BASE_URL."/dms/fetchDMSDocuments";
    const CLAIM_TYPES = array(
        "ASSESSMENT" => "Assessement",
        "THEFT" => "Theft",
        "WINDSCREEN" => "Windscreen"
    );

    static $PERIOD = array(
        "TODAY" => array(
            "TITLE" => "Today",
            "ID" => 1,
            "DAYS"=> 0
        ),
        "TOMORROW" => array(
            "TITLE" => "Tomorrow",
            "ID" => 2,
            "DAYS"=> 1
        ),
        "ONE_WEEK" => array(
            "TITLE" => "One Week",
            "ID" => 3,
            "DAYS"=> 7
        ),
        "ONE_MONTH" => array(
            "TITLE" => "One Month",
            "ID" => 4,
            "DAYS"=> 30
        ),
        "TWO_MONTHS" => array(
            "TITLE" => "Two Months",
            "ID" => 5,
            "DAYS" => 60
        ),
        "THREE_MONTHS" => array(
            "TITLE" => "Three Months",
            "ID" => 6,
            "DAYS" => 90
        ),
    );
    //Non-Motor renewal configs
    const COVER_TYPES = array(
        "OCCUPATION"=> 1,
        "BASIC_COVER"=>2,
        "ADD_ON_COVERS"=>3,
        "OTHER_OPTIONS"=>4
    );
    const COVERS = array(
        40 => array(
            "DESCRIPTION"=>"Earthquake Loading",
            "RATE"=> 0.00025
        ),
        41 => array(
            "DESCRIPTION"=>"DOS Power Failure",
            "RATE"=> 0.25
        ),
        42 => array(
            "DESCRIPTION"=>"Forest Fire",
            "RATE"=> 0.0005
        ),
        43 => array(
            "DESCRIPTION"=>"Spontaneous Combustion",
            "RATE"=> 0.0007
        ),
        44 => array(
            "DESCRIPTION"=>"Temporary Removal",
            "RATE"=> 0.1
        ),
        45 => array(
            "DESCRIPTION"=>"Asset All Risks Loading",
            "RATE"=> 0.25
        ),
        46 => array(
            "DESCRIPTION"=>"LTA (Maximum 3 Years)",
            "RATE"=> 0.15
        ),
        47 => array(
            "DESCRIPTION" =>"Levis",
            "RATE"=> 0.0045
        )
    );
    const COVER_CODES = array(
        "EARTHQUAKE_LOADING"=> 40,
        "DOS_POWER_FAILURE"=> 41,
        "FOREST_FIRE"=> 42,
        "SPONTANEOUS_COMBUSTION"=> 43,
        "TEMPORARY_REMOVAL"=> 44,
        "ASSET_ALL_RISKS_LOADING"=> 45,
        "LTA"=> 46,
        "LEVIS"=>47
    );
    const YES_OR_NO = array(
        "NO" => array(
            "ID"=>0,
            "TEXT"=>"NO"
        ),
        "YES"=> array(
            "ID"=>1,
            "TEXT"=>"YES"
        )
    );
    const GARAGE_TYPES = array(
        "Assessement" => array(
            "ID"=> 1,
            "TEXT"=>"Assessement"
        ),
        "Windscreen" => array(
            "ID"=> 2,
            "TEXT"=>"Windscreen"
        ),
        "Theft" => array(
            "ID"=> 3,
            "TEXT"=>"Theft"
        )
    );
    const PERMISSIONS = array(
        "PROCESS_SALVAGE"=>"Process Salvage",
        "SALE_SALVAGE"=>"Sale Salvage",
        "ADD_VENDOR" => "Add Vendor",
        "SUBMIT_NHIF_CLAIM" => "Submit NHIF Claim",
        "HOME_FIBER_VIEW_ONLY" => "Home Fiber View Only"
    );
    const SUB_CLASSES = array(
        1001=>"Motor Commercial",
        1002=>"Motor Private",
        1005 => "Motor Cycle"
    );
    const DISPLAY_VENDOR_TYPES = array(
        "SALVAGE" =>array(
            "ID" => 1,
            "TITLE" => "Salvage"
        ),
        "COURTESY_CAR" =>array(
            "ID" => 2,
            "TITLE" => "Courtesy Car"
        ),
        "INVESTIGATION" =>array(
            "ID" => 3,
            "TITLE" => "Investigation"
        )
    );
    const VENDOR_TYPES = array(
        1 => array(
            "ID" => 1,
            "TITLE" => "Salvage"
        ),
        2 => array(
            "ID" => 2,
            "TITLE" => "Courtesy Car"
        ),
        3 => array(
            "ID" => 3,
            "TITLE" => "Investigation"
        )
    );
    const TRAVEL_STATUSES = array(
        0 => array(
            "ID" => 0,
            "TITLE" => "Pending"
        ),
        1 => array(
            "ID" => 1,
            "TITLE" => "Processed"
        ),
        2 => array(
            "ID" => 2,
            "TITLE" => "Failed"
        )
    );
    const TRAVEL_DISPLAY_STATUSES = array(
        "PENDING" => array(
            "ID" => 0,
            "TITLE" => "Pending"
        ),
        "PROCESSED" => array(
            "ID" => 1,
            "TITLE" => "Processed"
        ),
        "FAILED" => array(
            "ID" => 2,
            "TITLE" => "Failed"
        )
    );

    const NHIF_DISPLAY_STATUSES = array(
        "SUBMITTED" => array(
            "ID" => 0,
            "TITLE" => "Submitted"
        ),
        "IN_PROGRESS" => array(
            "ID" => 1,
            "TITLE" => "In Progress"
        ),
        "PAID" => array(
            "ID" => 2,
            "TITLE" => "Paid"
        ),
        "CLOSED" => array(
            "ID" => 3,
            "TITLE" => "Closed"
        ),
        "REJECTED" => array(
            "ID" => 4,
            "TITLE" => "Rejected"
        ),
        "DOCUMENTS_PENDING" => array(
            "ID" => 5,
            "TITLE" => "Documents Pending"
        )
    );
    const NHIF_STATUSES = array(
        0 => array(
            "ID" => 0,
            "TITLE" => "Submitted"
        ),
        1 => array(
            "ID" => 1,
            "TITLE" => "In Progress"
        ),
        2 => array(
            "ID" => 2,
            "TITLE" => "Paid"
        ),
        3 => array(
            "ID" => 3,
            "TITLE" => "Closed"
        ),
        4 => array(
            "ID" => 4,
            "TITLE" => "Rejected"
        ),
        5 => array(
            "ID" => 5,
            "TITLE" => "Submitted Documents Pending"
        ),
    );
    const VALIDATOR=array(
        "REQUIRED_FIELDS"=>array(
            "ID"=>1,
            "TITLE"=>"Make sure all the mandatory fields are filled"
        ),
        "ALLOWED_DATE_RANGE"=>array(
            "ID"=>2,
            "TITLE"=>"Date of Loss is not within the policy period"
        ),
        "EARLY_DATE"=>array(
            "ID"=>3,
            "TITLE"=>"Date received cannot be earlier than date of injury"
        ),
        "LATER_DATE"=>array(
            "ID"=>4,
            "TITLE"=>"Date received cannot be later than "
        ),
    );
    const POLICY_TYPES=array(
        "CIVIL_SERVANTS_AND_NYS"=>array(
            "ID"=>"P/101/5020/2021/000020",
            "TITLE"=>"CIVIL SERVANTS AND NATIONAL YOUTH SERVICE",
            "WEF"=>"April 15, 2021 00:00:00",
            "WET"=>"April 14, 2022 23:59:00"
        ),
        "KENYA_POLICE_AND_KENYA_PRISONS"=>array(
            "ID"=>"P/101/6001/2021/000028",
            "TITLE"=>"ALL EMPLOYEES OF THE KENYA POLICE & KENYA PRISONS",
            "WEF"=>"Jan 01, 2022 00:00:00",
            "WET"=>"Mar 31, 2022 23:59:00"
        ),
    );
    const INJURY_TYPES=array(
        "INJURY"=>array(
            "ID"=>0,
            "TITLE"=>"Injury"
        ),
        "DEATH"=>array(
            "ID"=>1,
            "TITLE"=>"Death"
        )
    );
    const CLAIM_NOTIFICATION_CONTACTS=array(

        "EMAIL_TO"=>array(
            "ID"=>0,
            "NAME"=>"Jazk Claims Payments",
            "EMAIL"=>"jazkclaimspayments@allianz.com"
        ),
        "CC_EMAIL"=>array(
            "ID"=>1,
            "NAME"=>"Harriet Wanjiku",
            "EMAIL"=>"harriet.kariuki@allianz.com"
        )
    );
    const CAUSE_OF_INJURY=array(
        "ACCIDENT"=>array(
            "ID"=>0,
            "TITLE"=>"Accident"
        ),
        "FIRE"=>array(
            "ID"=>1,
            "TITLE"=>"Fire"
        )
    );
    const SUBROGATION_CC_EMAILS=array(
        "MIRIAM"=>array(
            "ID"=>0,
            "EMAIL"=>"Miriam.maina@allianz.com"
        ),
        "NANCY"=>array(
            "ID"=>1,
            "EMAIL"=>"Nancy.kasyoka@allianz.com"
        )
    );

    const SAF_EMAIL=array(
        "NAME"=>"Mary Ngure",
        "PHONE"=>"0709901206",
        "EMAIL"=>"mary.ngure@allianz.com"
    );

    /*** Metropol KYC Integration ***/
    const METROPOL_UAT_PUBLIC_KEY ="FXPiXhzkLAnYqoqNUcBKoTHYxilZBY";
    const METROPOL_UAT_PRIVATE_KEY ="PPCbItYLwZXjqYVyAHXKOSRcwVUtoZmFqqGAJglHfLsXJnYjwxTMpKOnilGY";
    const METROPOL_PROD_PUBLIC_KEY ="ivWSyAAYBfrBwKXmTbDsVEglWoDKMq";
    const METROPOL_PROD_PRIVATE_KEY ="isfNiRTscBEZnnKREDGLnKLiDlJGrccbviOfBHxDLDeYsqrWTAkJkxtpJExC";
    const METROPOL_BASE_URL = "https://api.metropol.co.ke";
    const METROPOL_PORT =22225;
    const METROPOL_API_VERSION ="v2_1";

    /*** IPRS KYC Integration ***/
    const IPRS_USERNAME ='JUBILEEINSURANCE';
    const IPRS_PASSWORD = 'Kitui123';
    const IPRS_URL = 'http://10.1.1.5:9004/IPRSServerwcf';
    const PROXY ='192.168.52.47';
    const PORT ='3128';
}
