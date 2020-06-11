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
    $sql = "insert into mailer_attachments(file_display_name, file_path, file_name, file_type,file_size,upload_by)
    values ('".$name."','".ATTACHMENT_SAVED_DIR."','".$uploadedFileName."','".$type."','".$size."','".USERCODE."')";
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
    $sql = "insert into mailer_sent(email_id,status,sent_by,mailer_contents_id,mailer_attachments_id) 
    values ('".$toEmail."','".$sentMailResult."','".USERCODE."','".$insert_content_id."','".$attach_ids_str."')";
    $res = mysql_query($sql, $con);
    if(! $res ) {
        die('Could not enter data: ' . mysql_error());
    }
    $insertid = mysql_insert_id();
    closeConnection($con);
    return $insertid;
}

function getCaseTypeDetails(){
    $con = getConnection();
    $sql = "SELECT casecode, skey, casename,short_description FROM casetype WHERE display = 'Y' AND casecode!=9999 ORDER BY short_description";
    $res = mysql_query($sql, $con);
    if(! $res ) {
        die('Could not get data: ' . mysql_error());
    }
    closeConnection($con);
    return $res;
}

function getAORDetailsbyDiaryNo($diaryNumber,$diaryYear){
    $diaryNumYear = $diaryNumber.$diaryYear;
    $con = getConnection();
    $sql = "SELECT bar.bar_id,bar.email,bar.name from bar inner join advocate on advocate.advocate_id=bar.bar_id where advocate.diary_no=".$diaryNumYear;
    $res = mysql_query($sql, $con);

    closeConnection($con);
    return $res;
}

function getPartyDetailsbyDiaryNo($diaryNumber,$diaryYear){
   // $diaryNumYear = $diaryNumber.$diaryYear;
    $con = getConnection();
    $sql = "SELECT partyname,email from party where diary_no=".$diaryNumber;
    $res = mysql_query($sql, $con);

    closeConnection($con);
    return $res;
}

?>