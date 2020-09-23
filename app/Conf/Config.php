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
    const GENERIC_ERROR_MESSAGE = "We are experiencing technical difficult, Forward the issue to admin";
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
                "id" => 2,
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
}
