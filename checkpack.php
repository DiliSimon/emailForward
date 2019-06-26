<?php

require_once './PHPMailer/src/PHPMailer.php';
require_once './PHPMailer/src/Exception.php';
require_once './PHPMailer/src/OAuth.php';
require_once './PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_mail($recipients, $subject, $body, $attachments) //needs an array of attachment paths.
{
    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "test.epidemiology@gmail.com";
    $mail->Password = "7890yuiop";
    $mail->SetFrom("test.epidemiology@gmail.com");
    foreach ($attachments as $filepath) {
        $mail->addAttachment($filepath);
    }
    $mail->Subject = $subject;
    $mail->Body = $body;
    foreach ($recipients as $recipient) {
        $mail->AddAddress($recipient);
    }
    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message has been sent";
    }
}