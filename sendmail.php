<?php
require('functions.inc.php');
require('db_functions.inc.php');echo "ok2";exit;
//var_dump($_POST);exit;
//var_dump($_FILES);exit;

//error_reporting(E_ALL, E_STRICT);
//ini_set('display_errors','on');
if(isset($_POST['selectedemails']) && isset($_POST['message']) && $_POST['selectedemails'] !="" && $_POST['message']!="")
{

    $from_email         = 'sender@sci.nic.in'; //from mail, sender email addrress
    $recipient_email    = $_POST['selectedemails']; //recipient email addrress

    $sender_name    = 'Sender Name'; //sender name
    $reply_to_email = 'senderemail'; //sender email, it will be used in "reply-to" header
    $subject        = $_POST["subject"]; //subject for the email
    $message        = $_POST["message"]; //body of the email




    $boundary = md5("random"); // define boundary with a md5 hashed value

    //header
    $headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
    $headers .= "From:".$from_email."\r\n"; // Sender Email
    $headers .= "Reply-To: ".$reply_to_email."\r\n"; // Email addrress to reach back
    $headers .= "Content-Type: multipart/mixed;\r\n"; // Defining Content-Type
    $headers .= "boundary = $boundary\r\n"; //Defining the Boundary

    //plain text
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split(base64_encode($message));

	$attachids = array();
    foreach ($_FILES as $attachment => $attachment_value){
        //Get uploaded file data using $_FILES array
        $tmp_name    = $_FILES[$attachment]['tmp_name']; // get the temporary file name of the file on the server
        $name        = $_FILES[$attachment]['name'];  // get the name of the file
        $size        = $_FILES[$attachment]['size'];  // get size of the file for size validation
        $type        = $_FILES[$attachment]['type'];  // get type of the file
        $error       = $_FILES[$attachment]['error']; // get the error (if any)

        //validate form field for attaching the file
        if($error > 0 && $name=='' && $type=='' && $size==0)
        {
            continue;
        }
        if ($error > 0){
            die('Upload error or File Upload Issue');
        }
        //read from the uploaded file & base64_encode content
        $handle = fopen($tmp_name, "r");  // set the file handle only for reading the file
        $content = fread($handle, $size); // reading the file
        fclose($handle);                  // close upon completion

        $encoded_content = chunk_split(base64_encode($content));

        //attachment
        $body .= "--$boundary\r\n";
        $body .="Content-Type: $type; name=".$name."\r\n";
        $body .="Content-Disposition: attachment; filename=".$name."\r\n";
        $body .="Content-Transfer-Encoding: base64\r\n";
        $body .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";
        $body .= $encoded_content; // Attaching the encoded file with email
        echo "ok";exit;
        $insertid = insertUploadedFile($tmp_name,$name,$size,$type);
		array_push($attachids,$insertid);
		//   unlink($name); // delete the file after attachment sent.
    }
	$attach_ids_str = implode(",",$attachids);
	$insert_content_id = insertMailContent($subject, $message);
	$toEmailsArr = explode(",",$recipient_email);
	$allMailResultArr = array();
	foreach($toEmailsArr as $key=>$toEmail){
		 //$sentMailResult = mail($toEmail, $subject, $body, $headers);
        $sentMailResult = true;
        insertSentMail($toEmail,$sentMailResult,$insert_content_id, $attach_ids_str);
        array_push($allMailResultArr, "$toEmail => $sentMailResult");

	}

	print_r($allMailResultArr);



}
?>