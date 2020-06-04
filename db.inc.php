<?php

function getConnection(){

    $db_host = "10.25.78.67";
    $db_user = "bharat";
    $db_password = "Programmer@#69";
    $db_dbname = "sci_cmis_final_27052019";


    $con=mysql_connect($db_host,$db_user,$db_password,$db_dbname);
    if(! $con ) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db( $db_dbname, $con );
    return $con;
}

function closeConnection($con){
    mysql_close($con);
}
?>