<?php
require('db.inc.php');
function getAORdetails(){
    $con = getConnection();
    $sql = "select bar_id, title, name,  email as emailid from bar where email is not null && email !='' && if_aor='Y'  && if_sen='N' && isdead='N' order by bar_id ASC";
    $res =  mysql_query($sql, $con);
    closeConnection($con);
    return $res;
}

function insertAttachedFiles($name,$uploadedFileName,$type,$size){
    $con = getConnection();
    $sql = "insert into mailer_attachments(file_display_name, file_path, file_name, file_type,file_size, upload_time, upload_by)
    values ('".$name."','".ATTACHMENT_SAVED_DIR."','".$uploadedFileName."','".$type."','".$size."','','".USERCODE."')";
    $res = mysql_query($sql, $con);
    if(! $res ) {
        die('Could not enter data: ' . mysql_error());
    }
    $insertid = mysql_insert_id();
    closeConnection($con);
	return $insertid;
}

function insertMailContent($subject, $content){
	 $con = getConnection();
	$sql = "insert into mailer_contents(subject,content) values ('".$subject."','".$content."')";
	$res = mysql_query($sql, $con);
    if(! $res ) {
        die('Could not enter data: ' . mysql_error());
    }
    $insertid = mysql_insert_id();
    closeConnection($con);
    return $insertid;
}

function insertSentMail($toEmail,$sentMailResult,$insert_content_id, $attach_ids_str){
    $con = getConnection();
    $date = date();
    $sql = "insert into mailer_sent(email_id,status,sent_by,mailer_contents_id,mailer_attachments_id,sent_time) 
    values ('".$toEmail."','".$sentMailResult."','".USERCODE."','".$insert_content_id."','".$attach_ids_str."','".$date."')";
    $res = mysql_query($sql, $con);
    if(! $res ) {
        die('Could not enter data: ' . mysql_error());
    }
    $insertid = mysql_insert_id();
    closeConnection($con);
    return $insertid;
}
?>