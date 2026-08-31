<?php
$secretKey = '6LeUqr8qAAAAAMM48oc2cSoyFIjPrtDAYsx_VmT_';
$token = $_POST['recaptcha-token'] ?? '';
$userIP = $_SERVER['REMOTE_ADDR'];

if (!$token) {
    die('reCAPTCHA token missing.');
}

// Verify token with Google
$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
$response = file_get_contents($verifyUrl . '?secret=' . $secretKey . '&response=' . $token . '&remoteip=' . $userIP);
$responseData = json_decode($response);

if ($responseData->success && $responseData->score >= 0.5) {

$configs = require_once("../../views/config.php");
require "../../Model/Registration.php";
require "../Utilities/Sanitization.php";
require "../Utilities/ultramsg.class.php";
include "../../Admin/DB Operations/enqueryOps.php";
include "../../Admin/DB Operations/notificationOps.php";
include "../../blogadmin/dblayer/smsOps.php";
include "../../blogadmin/dblayer/templateOps.php";
include "../../middleware/middleware.php";
include "../../middleware/csrf_middleware.php";
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $middleware = new Middleware();

    // Add CSRF middleware
    $middleware->add(new CsrfMiddleware());

    /*
     * ==========================================
     * SERVER-SIDE VALIDATION
     * ==========================================
     */

    $nameInput  = Sanitization::test_input($_POST["name2"] ?? '');
    $emailInput = Sanitization::test_input($_POST["email2"] ?? '');
    $phoneInput = Sanitization::test_input($_POST["phone2"] ?? '');

    // Validate Name - letters and spaces only
    if (
        $nameInput === '' ||
        !preg_match('/^[A-Za-z]+(?: [A-Za-z]+)*$/', $nameInput)
    ) {
        header("location:../../");
        exit;
    }

    // Validate Email
    if (
        $emailInput === '' ||
        !filter_var($emailInput, FILTER_VALIDATE_EMAIL)
    ) {
        header("location:../../");
        exit;
    }

    // Validate Phone - exactly 10 digits and starts with 6-9
    if (
        $phoneInput === '' ||
        !preg_match('/^[6-9][0-9]{9}$/', $phoneInput)
    ) {
        header("location:../../");
        exit;
    }


    /*
     * ==========================================
     * FRONT FORM CAPTCHA VALIDATION
     * ==========================================
     */

    if (Sanitization::test_input($_POST["front"] ?? '') == "front") {

        $user_input = $_POST['captcha_input'] ?? '';

        if ($user_input === ($_SESSION['captcha'] ?? '')) {
            error_log($user_input);
            error_log("CAPTCHA Verified!");
        } else {
            header("location:../../");
            exit;
        }
    }


    // Handle the request
    $request = $_SERVER;
    $middleware->handle($request);


    /*
     * ==========================================
     * UPDATE EXISTING ENQUIRY
     * ==========================================
     */

    if (isset($_POST['id'])) {

        $reg = new Registration();

        $reg->set_id(
            Sanitization::test_input($_POST["id"])
        );

        $reg->set_name(
            Sanitization::test_input($_POST["name2"])
        );

        $reg->set_email(
            Sanitization::test_input($_POST["email2"])
        );

        $reg->set_phone(
            Sanitization::test_input($_POST["phone2"])
        );

        $reg->set_Source(
            Sanitization::test_input($_POST["source"])
        );

        $reg->set_trainings(
            Sanitization::test_input($_POST["trainings2"])
        );

        $reg->set_demo(
            Sanitization::test_input($_POST["democlass"])
        );

        $reg->set_internship(
            Sanitization::test_input($_POST["internship2"])
        );

        $reg->set_services(
            Sanitization::test_input($_POST["services"])
        );

        $reg->setBranch(
            Sanitization::test_input($_POST["branch"])
        );

        DBenquery::update($reg);

    } else {


        /*
         * ==========================================
         * CREATE NEW ENQUIRY
         * ==========================================
         */

        $reg = new Registration();

        if ($_POST["name2"] != "") {
            $reg->set_name(
                Sanitization::test_input($_POST["name2"])
            );
        }

        if ($_POST["email2"] != "") {
            $reg->set_email(
                Sanitization::test_input($_POST["email2"])
            );
        }

        if ($_POST["phone2"] != "") {
            $reg->set_phone(
                Sanitization::test_input($_POST["phone2"])
            );
        }

        if ($_POST["source"] != "") {
            $reg->set_Source(
                Sanitization::test_input($_POST["source"])
            );
        }

        $reg->set_trainings(
            Sanitization::test_input($_POST["trainings2"])
        );

        $reg->set_demo(
            Sanitization::test_input($_POST["democlass"])
        );

        $reg->set_internship(
            Sanitization::test_input($_POST["internship2"])
        );

        $reg->set_services(
            Sanitization::test_input($_POST["services"])
        );

        $reg->setBranch(
            Sanitization::test_input($_POST["branch"])
        );


        /*
         * ==========================================
         * SMS DETAILS
         * ==========================================
         */

        $smsDetails = DBsms::getAllsmsDetails();
        $SenderMessage = DBtemplate::getSenderandMessage();

        $message = new sms();

        $message->setNumbers($reg->get_phone());

        $msg = str_replace(
            "{name}",
            $reg->get_name(),
            $SenderMessage->getmessage()
        );

        error_log($msg);
        error_log($reg->get_phone());

        $message->setMessage($msg);
        $message->setSender($SenderMessage->getsender());
        $message->setusername($smsDetails->getusername());
        $message->setAPIkey($smsDetails->getAPIkey());
        $message->setKey($smsDetails->getKey());


        /*
         * ==========================================
         * INSERT ENQUIRY
         * ==========================================
         */

        if (preg_match('/^[6-9][0-9]{9}$/', $reg->get_phone())) {
            DBenquery::insert($reg);
        }


        //$message->sendSMS();


        /*
         * ==========================================
         * WHATSAPP NOTIFICATION
         * ==========================================
         */

        $notification = new Notification();
        $notification->setStatus('1');

        $params = array(
            'token' => 'r7oz3zkl9emdvli9',
            'to' => $reg->get_phone(),
            'body' =>
                'Thank you ' .
                $reg->get_name() .
                ' for enquiring with DharwadHubballiTutor.For any further assistance please contact us on +91-9741237334 / +91-8007961759 visit our website for more details www.dharwadhubballitutor.com'
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/instance63433/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        //$response = curl_exec($curl);
        //$err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }


        /*
         * ==========================================
         * ENQUIRY NOTIFICATION
         * ==========================================
         */

        if (!empty($_POST['trainings2'])) {

            $notification->setMessage(
                'There Has Been an enquiry for Training'
            );

            $notification->setCategory(
                'trainings-tab-content'
            );

        } else if (!empty($_POST['internship2'])) {

            $notification->setMessage(
                'There Has Been an enquiry for Internship'
            );

            $notification->setCategory(
                'Internship-tab-content'
            );

        } else if (!empty($_POST['services'])) {

            $notification->setMessage(
                'There Has Been an enquiry for Services'
            );

            $notification->setCategory(
                'services-tab-content'
            );

        } else if (!empty($_POST['democlass'])) {

            $notification->setMessage(
                'There Has Been an enquiry for democlass'
            );

            $notification->setCategory(
                'democlass-tab-content'
            );
        }

        DBnotification::insert($notification);
    }
}
if(Sanitization::test_input($_POST["front"])=="front"){
    header("location:../../");
}else{
  
  header("location:../View/enquiries.php");
}
} else {
    // CAPTCHA failed
    echo "CAPTCHA verification failed. Please try again.";
    header("location:../../");
}


?>
