<?php
//error_reporting(E_ALL, E_STRICT);
//ini_set('display_errors','on');
require('functions.inc.php');

//echo "ok2";exit;
//var_dump($_POST);exit;
//var_dump($_FILES);exit;


if (isset($_POST['selectedemails']) && isset($_POST['message']) && $_POST['selectedemails'] != "" && $_POST['message'] != "") {

    $recipient_email = $_POST['selectedemails']; //recipient email addrress
    
    $sender_name = 'Supreme Court of India'; //sender name
    $sender_email = '<sci@nic.in>'; //from mail, sender email addrress


    $subject = $_POST["subject"]; //subject for the email
    $message = $_POST["message"]; //body of the email

    $allMailResultArr = multi_attach_mail($recipient_email, $subject, $message, $sender_email, $sender_name,  $_FILES);


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Mail Notifying System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/jquery.multiselect.css">
    <link rel="stylesheet" href="css/app.css">

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col">
            <h2>Status of the Emails Sent!</h2>
            <table class="table table-bordered table-responsive-lg">
                <thead>
                <tr>
                    <th>Email</th>
                    <th>Status</th>
                </tr>
                </thead>
                <?php foreach ($allMailResultArr as $email => $status) { ?>
                    <tr>
                        <td><?= $email ?></td>
                        <td><?php if ($status == 1) {
                                echo 'SUCCESS';
                            } else {
                                echo 'FAILED';
                            } ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

</div>
</body>
