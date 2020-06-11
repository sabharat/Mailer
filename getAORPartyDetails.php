<?php
require('db_functions.inc.php');
$con = getConnection();
if ($_REQUEST['ct'] != '') {
    $get_dno = "SELECT substr( diary_no, 1, length( diary_no ) -4 ) as dn, substr( diary_no , -4 ) as dy
    FROM main 
    WHERE (SUBSTRING_INDEX(fil_no, '-', 1) = " . $_REQUEST['ct'] . " AND CAST(" . $_REQUEST['cn'] . " AS UNSIGNED) 
    BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2),'-',-1)) 
    AND (SUBSTRING_INDEX(fil_no, '-', -1)) AND  if((reg_year_mh=0 OR DATE(fil_dt)>DATE('2017-05-10')), YEAR(fil_dt)=" . $_REQUEST['cy'] . ", reg_year_mh=" . $_REQUEST['cy'] . ") ) 
    # or (SUBSTRING_INDEX(fil_no_fh, '-', 1) = " . $_REQUEST['ct'] . " 
    # AND CAST(" . $_REQUEST['cn'] . " AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2),'-',-1)) 
    # AND (SUBSTRING_INDEX(fil_no_fh, '-', -1)) AND if(reg_year_fh=0, YEAR(fil_dt_fh)=" . $_REQUEST['cy'] . ", reg_year_fh=" . $_REQUEST['cy'] . "))
    ";

//$get_dno = "SELECT substr( diary_no, 1, length( diary_no ) -4 ) as dn, substr( diary_no , -4 ) as dy FROM registered_cases WHERE casetype_id=$_REQUEST[ct] AND case_no=$_REQUEST[cn] AND case_year=$_REQUEST[cy] AND display='Y'";

    $get_dno = mysql_query($get_dno) or die(__LINE__ . '->' . mysql_error());
    if (mysql_affected_rows() > 0) {
        $get_dno = mysql_fetch_array($get_dno);
        $_REQUEST['d_no'] = $get_dno['dn'];
        $_REQUEST['d_yr'] = $get_dno['dy'];
    } else {
        $get_dno = "SELECT 
SUBSTR( h.diary_no, 1, LENGTH( h.diary_no ) -4 ) AS dn, 
SUBSTR( h.diary_no , -4 ) AS dy,
if(h.new_registration_number!='',SUBSTRING_INDEX(h.new_registration_number, '-', 1),'') as ct1, 
            if(h.new_registration_number!='',SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2), '-', -1 ),'') as crf1, 
            if(h.new_registration_number!='',SUBSTRING_INDEX(h.new_registration_number, '-', -1),'') as crl1 FROM
 main_casetype_history h 
WHERE 
((SUBSTRING_INDEX(h.new_registration_number, '-', 1) = " . $_REQUEST['ct'] . " AND 
CAST(" . $_REQUEST['cn'] . " AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(h.new_registration_number, '-', 2),'-',-1)) AND (SUBSTRING_INDEX(h.new_registration_number, '-', -1)) AND h.new_registration_year=" . $_REQUEST['cy'] . ") OR
  (
    SUBSTRING_INDEX(h.old_registration_number, '-', 1) = " . $_REQUEST['ct'] . "  
    AND CAST(" . $_REQUEST['cn'] . " AS UNSIGNED) BETWEEN (
      SUBSTRING_INDEX(
        SUBSTRING_INDEX(h.old_registration_number, '-', 2),
        '-',
        - 1
      )
    ) 
    AND (
      SUBSTRING_INDEX(
        h.old_registration_number,
        '-',
        - 1
      )
    ) 
    AND h.old_registration_year = " . $_REQUEST['cy'] . " 
   AND h.is_deleted='t'
)) AND h.is_deleted='f'";

//  $get_dno = "SELECT substr( diary_no, 1, length( diary_no ) -4 ) as dn, substr( diary_no , -4 ) as dy
//    FROM main
//    WHERE (SUBSTRING_INDEX(fil_no, '-', 1) = $_REQUEST[ct] AND CAST($_REQUEST[cn] AS UNSIGNED)
//    BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2),'-',-1))
//    AND (SUBSTRING_INDEX(fil_no, '-', -1)) AND  if(reg_year_mh=0, YEAR(fil_dt)=$_REQUEST[cy], reg_year_mh=$_REQUEST[cy]) ) or (SUBSTRING_INDEX(fil_no_fh, '-', 1) = $_REQUEST[ct]
//    AND CAST($_REQUEST[cn] AS UNSIGNED) BETWEEN (SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2),'-',-1))
//    AND (SUBSTRING_INDEX(fil_no_fh, '-', -1)) AND if(reg_year_fh=0, YEAR(fil_dt_fh)=$_REQUEST[cy], reg_year_fh=$_REQUEST[cy]))";
//$get_dno = "SELECT substr( diary_no, 1, length( diary_no ) -4 ) as dn, substr( diary_no , -4 ) as dy FROM registered_cases WHERE casetype_id=$_REQUEST[ct] AND case_no=$_REQUEST[cn] AND case_year=$_REQUEST[cy] AND display='Y'";
        $get_dno = mysql_query($get_dno) or die(__LINE__ . '->' . mysql_error());
        if (mysql_affected_rows() > 0) {
            $get_dno = mysql_fetch_array($get_dno);
            $_REQUEST['d_no'] = $get_dno['dn'];
            $_REQUEST['d_yr'] = $get_dno['dy'];
            $sql_ct_type = mysql_query("Select short_description from casetype where casecode='" . $_REQUEST['ct'] . "' and display='Y'") or die("Error" . __LINE__ . mysql_error());
            $res_ct_typ = mysql_result($sql_ct_type, 0);
            $t_slpcc = $res_ct_typ . " " . $get_dno['crf1'] . " - " . $get_dno['crl1'] . " / " . $_REQUEST['cy'];
        } else {
            ?>
            <p align=center><font color=red>Case Not Found</font></p>
            <?php
        }

    }

}
//echo "Select * from main where substr( diary_no, 1, length( diary_no ) -4 )='$_REQUEST[d_no]' and substr( diary_no , -4 )='$_REQUEST[d_yr]'";
//echo "Select diary_no,conn_key,diary_no,fil_dt, YEAR(fil_dt) as filyr, fil_no_fh, actcode, pet_adv_id, res_adv_id, lastorder, c_status, if(fil_no!='',SUBSTRING_INDEX(fil_no, '-', 1),'') as ct1,
//        if(fil_no!='',SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1 ),'') as crf1, if(fil_no!='',SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1),'') as crl1, if(fil_no_fh!='',SUBSTRING_INDEX(fil_no_fh, '-', 1),'') as ct2,
//        if(fil_no_fh!='',SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2), '-', -1 ),'') as crf2, if(fil_no_fh!='',SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2), '-', -1),'') as crl2 from main where substr( diary_no, 1, length( diary_no ) -4 )='$_REQUEST[d_no]' and substr( diary_no , -4 )='$_REQUEST[d_yr]'";
if ($_REQUEST['d_no'] != '' and $_REQUEST['d_yr'] != '') {
    $res1 = getAORDetailsbyDiaryNo($_REQUEST['d_no'], $_REQUEST['d_yr']);
    $res2 = getPartyDetailsbyDiaryNo($_REQUEST['d_no'], $_REQUEST['d_yr']);
    if (mysql_num_rows($res1) > 0 || mysql_num_rows($res2) > 0) {
        if (mysql_num_rows($res1) > 0) {
            ?>
            <h3 align="center">AOR details</h3>
            <table class="table table-bordered">
                <tr><th>Select</th><th>Name</th><th>Email</th></tr>
                <?php
                while ($row1 = mysql_fetch_array($res1)) {
                    ?>
                    <tr><td><input type="checkbox" id="<?php echo "aor##".$row1['email']?>" name="<?php echo "aor##".$row1['email']?>" value="<?php echo "aor##".$row1['email']?>"></td><td><?= $row1['name']?></td><td><?= $row1['email']?></td></tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
        if (mysql_num_rows($res2) > 0) {
            ?>
            <h3 align="center">Party details</h3>
            <table class="table table-bordered">
                <tr><th>Select</th><th>Name</th><th>Email</th></tr>
                <?php
                while ($row2 = mysql_fetch_array($res2)) {
                    ?>
                    <tr><td><input type="checkbox" id="<?php "party##".$row2['email']?>" name="<?= "party##".$row2['email']?>" value="<?= "party##".$row2['email']?>"></td><td><?php $row2['partyname']?></td><td><?= $row2['email']?></td></tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
    } else {
        echo "No data Found";
    }
}
closeConnection($con);
?>