<?php
require('constants.php');
require('db_functions.inc.php');
require('PHPMailer/sci_functions.php');

function insertUploadedFile($tmp_name,$name,$size,$type)
{
                $fileExtension = pathinfo($name, PATHINFO_EXTENSION);

                $uploadedFileName = USERCODE . "_" . time() . "_" . rand(1000, 99999) . "." . $fileExtension;

                if (is_dir(ATTACHMENT_SAVED_DIR) == false) {
                    mkdir(ATTACHMENT_SAVED_DIR, 0755, true);        // Create directory if it does not exist
                }
                if (is_dir(ATTACHMENT_SAVED_DIR."/" . $uploadedFileName) == false) {
					 move_uploaded_file($tmp_name, ATTACHMENT_SAVED_DIR."/" . $uploadedFileName);
                    $insert_id = insertAttachedFiles($name,$uploadedFileName,$type,$size);

                    return $insert_id;                
                }
}


function multi_attach_mail($recipient_email, $subject, $message, $sender_email, $sender_name,  $filesold){
    $from = $sender_name."<".$sender_email.">";

    $boundary = md5("random"); // define boundary with a md5 hashed value

    //header
    $headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
    $headers .= "From:" . $from . "\r\n"; // Sender
 //   $headers .= "Reply-To: " . $reply_to_email . "\r\n"; // Email addrress to reach back
    $headers .= "Content-Type: multipart/mixed;\r\n"; // Defining Content-Type
    $headers .= "boundary = $boundary\r\n"; //Defining the Boundary

    //plain text
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split(base64_encode($message));

    $attachids = array();
    foreach ($filesold as $attachment => $attachment_value) {
        //Get uploaded file data using $_FILES array
        $tmp_name = $filesold[$attachment]['tmp_name']; // get the temporary file name of the file on the server
        $name = $filesold[$attachment]['name'];  // get the name of the file
        $size = $filesold[$attachment]['size'];  // get size of the file for size validation
        $type = $filesold[$attachment]['type'];  // get type of the file
        $error = $filesold[$attachment]['error']; // get the error (if any)

        //validate form field for attaching the file
        if ($error > 0 && $name == '' && $type == '' && $size == 0) {
            continue;
        }
        if ($error > 0) {
            die('Upload error or File Upload Issue');
        }

        //read from the uploaded file & base64_encode content
        $handle = fopen($tmp_name, "r");  // set the file handle only for reading the file
        $content = fread($handle, $size); // reading the file
        fclose($handle);                  // close upon completion

        $encoded_content = chunk_split(base64_encode($content));

        //attachment
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $type; name=" . $name . "\r\n";
        $body .= "Content-Disposition: attachment; filename=" . $name . "\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "X-Attachment-Id: " . rand(1000, 99999) . "\r\n\r\n";
        $body .= $encoded_content; // Attaching the encoded file with email

        $insertid = insertUploadedFile($tmp_name, $name, $size, $type);
        array_push($attachids, $insertid);

        //   unlink($name); // delete the file after attachment sent.
    }
    $attach_ids_str = implode(",", $attachids);

    $insert_content_id = insertMailContent($subject, $message);

    $toEmailsArr = explode(",", $recipient_email);
    $allMailResultArr = array();

    foreach ($toEmailsArr as $key => $toEmail) {
    //    $sentMailResult = mail($toEmail, $subject, $body, $headers);

        /////////////////FOR GMAIL SMTP ONLY///////////////////
         $files = getAttachedFileswithInsertIds($attach_ids_str);

        $response = sendAttachedEmailviaGmailSMTP($toEmail, $subject, $message, $files);
        $sentMailResult = false;
        if (strpos($response, 'success') !== false) {
            $sentMailResult = true;
        }
        /////////////////FOR GMAIL SMTP ONLY///////////////////
        insertSentMail($toEmail, $sentMailResult, $insert_content_id, $attach_ids_str);
        $allMailResultArr[$toEmail] = $sentMailResult;

    }

    return $allMailResultArr;
}

?>