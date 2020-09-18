<?php


namespace App\Helper;

define("MULTIPART_BOUNDARY", "----".md5(time()));
define("EOL", "\r\n");// PHP_EOL cannot be used for emails we need the CRFL '\r\n'

class InfobipEmailHelper
{

    public function __construct()
    {

    }


    public static function getBodyPart($FORM_FIELD, $value)
    {
        if ($FORM_FIELD === 'attachment') {
            $content = 'Content-Disposition: form-data; name="' . $FORM_FIELD . '"; filename="' . basename($value) . '"' . constant("EOL");
            $content .= 'Content-Type: ' . mime_content_type($value) . constant("EOL");
            $content .= 'Content-Transfer-Encoding: binary' . constant("EOL");
            $content .= constant("EOL") . file_get_contents($value) . constant("EOL");
        } else {
            $content = 'Content-Disposition: form-data; name="' . $FORM_FIELD . '"' . constant("EOL");
            $content .= constant("EOL") . $value . constant("EOL");
        }

        return $content;
    }

    /*
     * Method to convert an associative array of parameters into the HTML body string
    */
    public static function getBody($fields)
    {
        $content = '';
        foreach ($fields as $FORM_FIELD => $value) {
            $values = is_array($value) ? $value : array($value);
            foreach ($values as $v) {
                $content .= '--' . constant("MULTIPART_BOUNDARY") . constant("EOL") . self::getBodyPart($FORM_FIELD, $v);
            }
        }
        return $content . '--' . constant("MULTIPART_BOUNDARY") . '--'; // Email body should end with "--"
    }

    /*
     * Method to get the headers for a basic authentication with username and passowrd
    */
    public static function getHeader($username, $password)
    {
        // basic Authentication
        $auth = base64_encode("$username:$password");

        // Define the header
        return array('Authorization:Basic ' . $auth, 'Content-Type: multipart/form-data ; boundary=' . constant("MULTIPART_BOUNDARY"));
    }


    public static function sendEmail($email, $email_add)
    {
        // URL to the API that sends the email.
        $url = "https://rm3dm.api.infobip.com/email/1/send";

        //$doc_path = '/storage/app/public/documents/outputs/'.$policy_no.'.pdf';

        // Associate Array of the post parameters to be sent to the API
        $postData = array(
            'from' => $email['from_user_email'],
            'to' => $email_add,
            'replyTo' => $email['from_user_email'],
            'subject' => $email['subject'],
            'html' => $email['message'],
            // 'html' => view('emails.life.isfmail',['content'=>$email->message]),
            // 'intermediateReport'=> 'true',
            // 'notifyUrl' => env('NOTIFY_URL'),
            // 'notifyContentType' => 'application/json',
            // 'callbackData' => 'DLR callback data'
        );


        // Create the stream context.
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => self::getHeader('JubileeKenya', 'Jubilee4321'),
                'content' => self::getBody($postData),
            )
        ));

        // Read the response using the Stream Context.
        $response = file_get_contents($url, false, $context);

        return json_decode($response);
    }

    public static function check_status()
    {

        $url = "https://rm3dm.api.infobip.com/email/1/logs";
        $ch = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => ($url),
        ));

        $auth = base64_encode("JubileeKenya:Jubilee4321");

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept:application/json",
            "content-type: application/json",
            'Authorization:Basic ' . $auth
        ));
        //Prevents usage of a cached version of the URL
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        // Send the request & save response to $resp
        //$response = curl_exec($ch);
        $response = json_decode(curl_exec($ch));
        //Close the CURL initialization
        curl_close($ch);

        return $response;
    }


    public static function validate_email($email)
    {

        $auth = base64_encode("JubileeKenya:Jubilee4321");

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rm3dm.api.infobip.com/email/2/validation",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{ \"to\":\"$email\"}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic " . $auth,
                "content-type: application/json"
            ),
        ));

        $response = json_decode(curl_exec($curl));
        $err = json_decode(curl_error($curl));

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }
}
