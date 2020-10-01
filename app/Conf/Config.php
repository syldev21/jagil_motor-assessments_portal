<?php


namespace App\Conf;


class Config
{
    /**
     * Log Variables
     */
    const MOTOR_ASSESSMENT_APP_NAME = "MOTOR_ASSESSMENT";
    const MOTOR_ASSESSMENT_DEBUG = "C:\\xampp\\htdocs\\logs\\debug.log";
    const MOTOR_ASSESSMENT_INFO = "C:\\xampp\\htdocs\\logs\\info.log";
    const MOTOR_ASSESSMENT_ERROR = "C:\\xampp\\htdocs\\logs\\error.log";
    const MOTOR_ASSESSMENT_FATAL = "C:\\xampp\\htdocs\\logs\\fatal.log";

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
                "text" => "Re-Assigned"
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
                "text" => "Is Draft"
            ),
            "ASSESSED" => array(
                "id" => 3,
                "text" => "Assessed"
            ),
            "APPROVED" => array(
                "id" => 4,
                "text" => "Approved"
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

    const DATES_LIMIT =5;
    static $ROLES = array(
        "ASSESSOR" => "Assessor",
        "ADJUSTER" => "Adjuster",
        "ADMIN" => "Admin",
        "HEAD-ASSESSOR" => "Head Assessor"
    );

    const START_YEAR = 1960;

    const CURRENT_VAT = 14;
    const CURRENT_VAT_PERCENTAGE = '14%';
    const INITIAL_PERCENTAGE = 100;
    const CURRENT_TOTAL_PERCENTAGE = self::INITIAL_PERCENTAGE + self::CURRENT_VAT;
    const VAT_REDUCTION_DATE = '2020-05-01 12:00:00';

    const VAT = 16;
    const VAT_PERCENTAGE = '16%';
    const TOTAL_PERCENTAGE = self::INITIAL_PERCENTAGE + self::VAT;

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
        "N/A" => array(
            "ID" => 3,
            "TITLE" => "Not Applicable"
        ),
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
}
