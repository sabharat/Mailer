<?php
require('constants.php');
require('db_functions.inc.php');

function insertUploadedFile($tmp_name,$name,$size,$type)
{
              //  $fileExtension = pathinfo($name, PATHINFO_EXTENSION);

                $uploadedFileName = USERCODE . "_" . time() . "_" . rand(1000, 99999) . "." . $type;

                if (is_dir(ATTACHMENT_SAVED_DIR) == false) {
                    mkdir(ATTACHMENT_SAVED_DIR, 0755, true);        // Create directory if it does not exist
                }
                if (is_dir(ATTACHMENT_SAVED_DIR."/" . $uploadedFileName) == false) {
					 move_uploaded_file($tmp_name, ATTACHMENT_SAVED_DIR."/" . $uploadedFileName);
                    $insert_id = insertAttachedFiles($name,$uploadedFileName,$type,$size);
                    return $insert_id;                
                }
}

?>