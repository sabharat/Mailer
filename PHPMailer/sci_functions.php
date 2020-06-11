<?php
use PHPMailer\PHPMailer\PHPMailer;


function sendEmailviaGmailSMTP($emailto, $subject, $body){

    $name = "eCopying - Supreme court of India";
    $email = "kbalkasaiya@gmail.com";

    require_once "PHPMailer.php";
    require_once "SMTP.php";
    require_once "Exception.php";

    $mail = new PHPMailer();

    //smtp settings
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "kbalkasaiya@gmail.com";
    $mail->Password = 'PASSWORD';
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    //$mail->addAttachment("../deleted_40878_2017_Order_08-Jan-2018_deleted_on_140519.pdf");
    // $mail->addAttachment("./njrsData/njrs_registeredmatters.txt");
    // $mail->addAttachment("./njrsData/njrs_unregistered matters.txt");

    //email settings
    $mail->isHTML(true);
    $mail->setFrom($email, $name);
    $mail->addAddress($emailto);
    $mail->Subject = ("$email ($subject)");
    $mail->Body = $body;

    if($mail->send()){
        $status = "success";
        $response = "Email is sent!";
    }
    else
    {
        $status = "failed";
        $response = "Something is wrong: <br>" . $mail->ErrorInfo;
    }

    return json_encode(array("status" => $status, "response" => $response));
}

function sendAttachedEmailviaGmailSMTP($emailto, $subject, $body, $files){

    $name = "Bharat";
    $email = "bharatsaini56@gmail.com";

    require_once "PHPMailer.php";
    require_once "SMTP.php";
    require_once "Exception.php";

    $mail = new PHPMailer();

    //smtp settings
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "bharatsaini56@gmail.com";
    $mail->Password = 'password';
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";

    if(count($files) > 0){
        for($i=0;$i<count($files);$i++){
            if(is_file($files[$i])){
                $mail->addAttachment($files[$i]);
            }
        }
    }
    //$mail->addAttachment("../deleted_40878_2017_Order_08-Jan-2018_deleted_on_140519.pdf");
    // $mail->addAttachment("./njrsData/njrs_registeredmatters.txt");
    // $mail->addAttachment("./njrsData/njrs_unregistered matters.txt");

    //email settings
    $mail->isHTML(true);
    $mail->setFrom($email, $name);
    $mail->addAddress($emailto);
    $mail->Subject = ("$email ($subject)");
    $mail->Body = $body;

    if($mail->send()){
        $status = "success";
        $response = "Email is sent!";
    }
    else
    {
        $status = "failed";
        $response = "Something is wrong: <br>" . $mail->ErrorInfo;
    }

    return json_encode(array("status" => $status, "response" => $response));
}
?>
