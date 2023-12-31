<?php


namespace App\Helper;


use App\Conf\Config;
use infobip\api\client\SendMultipleTextualSmsAdvanced;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\Destination;
use infobip\api\model\sms\mt\send\Message;
use infobip\api\model\sms\mt\send\textual\SMSAdvancedTextualRequest;

class SMSHelper

{

    public static function sendSMS($text, $recipient) {

        $client = new SendMultipleTextualSmsAdvanced(new BasicAuthConfiguration(Config::JUBILEE_SMS_USERNAME, Config::JUBILEE_SMS_PASSWORD));



        $destination = new  Destination();

        $destination->setTo($recipient);



        $message = new Message();

//        $message->setFrom('JUBINSURE');

        $message->setFrom('JALLIANZ');

        $message->setDestinations([$destination]);

        $message->setText($text);



        $requestBody = new SMSAdvancedTextualRequest();

        $requestBody->setMessages([$message]);



        $response = $client->execute($requestBody);



        return $response;

    }

}
