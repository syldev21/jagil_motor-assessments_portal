<?php


namespace App\Helper;


use App\Conf\Config;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CustomLogger
{
    /**
     * Motor assessment log variables
     */

    public $motorAssessmentDebugLogger;
    public $motorAssessmentInfoLogger;
    public $motorAssessmentErrorLogger;
    public $motorAssessmentFatalLogger;


    public function __construct()
    {
        /**
         * initialising the different log functions for Motor_assessment
         */
        $this->motorAssessmentDebugLogger = new Logger(Config::MOTOR_ASSESSMENT_APP_NAME);
        $this->motorAssessmentInfoLogger = new Logger(Config::MOTOR_ASSESSMENT_APP_NAME);
        $this->motorAssessmentFatalLogger = new Logger(Config::MOTOR_ASSESSMENT_APP_NAME);
        $this->motorAssessmentErrorLogger = new Logger(Config::MOTOR_ASSESSMENT_APP_NAME);

        $this->motorAssessmentDebugLogger->pushHandler(new StreamHandler(Config::MOTOR_ASSESSMENT_DEBUG, Logger::DEBUG));
        $this->motorAssessmentInfoLogger->pushHandler(new StreamHandler(Config::MOTOR_ASSESSMENT_INFO, Logger::INFO));
        $this->motorAssessmentFatalLogger->pushHandler(new StreamHandler(Config::MOTOR_ASSESSMENT_ERROR, Logger::ERROR));
        $this->motorAssessmentErrorLogger->pushHandler(new StreamHandler(Config::MOTOR_ASSESSMENT_FATAL, Logger::EMERGENCY));
    }
}
