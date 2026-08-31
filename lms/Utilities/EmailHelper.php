<?php
// lms/Utilities/EmailHelper.php
class EmailHelper {
    public static function send($to, $subject, $message, $headers = '') {
        if (empty($headers)) {
            $headers = "From: noreply@yourdomain.com\r\nContent-type: text/html; charset=UTF-8";
        }
        return mail($to, $subject, $message, $headers);
    }
} 