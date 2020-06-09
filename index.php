<?php
require('db_functions.inc.php');
$res = getAORdetails();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Mail Notifying System</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/fontawesome.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/jquery.multiselect.css">
    <link rel="stylesheet" href="css/app.css">

</head>
<body>
<div class="container" style="width: 100%">
    <div class="row">

        <!--Mail Ids Selection-->

        <div class="col-lg-6">
            <div class="row" id="mailtypeselectionrow" align="center">
                <div class="col-sm-6">
                    <h4>Please Select Action</h4>
                </div>
                <div class="col-sm-6">
                    <select name="actiontype" id="actiontype" onchange="showHideRowDiv(this.value)">
                        <option selected value="searchbartypeselect">Search AOR</option>
                        <option value="inputtypeselect">Enter Email</option>
                        <option value="diarynoselect">Search by DiaryNo.</option>
                    </select>
                </div>
            </div>
            <hr/>
            <div class="row" id="inputmailrow">
                <div class="col">
                    <h3>Please Enter Email Ids!</h3>
                    <div align="center">
                        <input style="width: 100%" type="email" name="inputmail" id="inputmail"
                               placeholder="Enter Emails Separated with Comma[,]"/>

                        <button style="margin-top: 10px" onclick="showHideRowDiv('showmailformfrominput')">Click to
                            Select
                        </button>

                    </div>
                </div>
            </div>
            <div class="row" id="bar_member_row" align="center">
                <div class="col">
                    <h3>Select Advocates On Record!</h3>

                    <select name="users_list" id="users_list" multiple="multiple" size="15">

                        <?php
                        while ($row = mysql_fetch_array($res)) {
                            ?>
                            <option value="<?php echo $row['bar_id'] . '##' . $row['emailid'] ?>"><?php echo $row['name'] . '[' . $row['emailid'] . ']' ?> </option>
                        <?php }
                        ?>
                    </select>

                    <button onclick="showHideRowDiv('showmailformfromsearch')">Click to Select</button>
                </div>
            </div>
            <div class="row" id="case_no_row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5>Search by Diary/Case no-</h5>
                        </div>
                        <div class="col-sm-6">
                            <label for="casenoradio">CaseNo.</label>
                            <input type="radio" name=diarycaseradio id="casenoradio" value="casenoradio"
                                   checked="checked" onchange="showhidecasediarydiv(this.value)"/>
                            <label for="diarynoradio">DiaryNo.</label>
                            <input type="radio" name=diarycaseradio id="diarynoradio" value="diarynoradio"
                                   onchange="showhidecasediarydiv(this.value)"/>
                        </div>
                    </div>
                        <div class="row" id="casenoformdiv">
                            <div class="col-sm-12">
                                Case Type:
                                <select id="selct">
                                    <option value="-1">Select</option>
                                    <?php
                                    $ct_rs = getCaseTypeDetails();
                                    while ($ct_rw = mysql_fetch_array($ct_rs)) {
                                        ?>
                                        <option value="<?php echo $ct_rw['casecode'] ?>"><?php echo $ct_rw['short_description']; ?></option>
                                    <?php } ?>
                                </select>&nbsp;
                            </div>
                            <div class="col-sm-6">
                                Case No.: <input type="text" size="6" maxlength="6" id="case_no"/>&nbsp;
                            </div>
                            <div class="col-sm-6">
                                Year:
                                <?php $currently_selected = date('Y');
                                $earliest_year = 1950;
                                $latest_year = date('Y');
                                print '<select id="case_yr">';
                                foreach (range($latest_year, $earliest_year) as $i) {
                                    print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                }
                                print '</select>'; ?>
                            </div>
                        </div>
                        <div class="row" id="diarynoformdiv" style="display: none">
                            <div class="col-sm-9">
                                Diary No.:<input type="text" id="dno" size="4" placeholder="Enter Diary No" "/>&nbsp;
                            </div>
                            <div class="col-sm-3">
                                Year:
                                <?php $currently_selected = date('Y');
                                $earliest_year = 1950;
                                $latest_year = date('Y');
                                print '<select id="dyr">';
                                foreach (range($latest_year, $earliest_year) as $i) {
                                    print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                                }
                                print '</select>'; ?>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-12">
                        <input type="button" name="btnGetEmails" onclick="getAORPartyDetails()" value="GET Emails"/>
                        </div>
                        <div class="col-12" id="results" style="display: none">

                        </div>
                        <button onclick="showHideRowDiv('showmailformfromsdiarycaseno')">Click to Select</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mail Form-->

        <div class="col-lg-6">
            <div class="row" id="mailformrow">
                <div class="col">
                    <form id="mail_form" enctype="multipart/form-data" method="POST" action="sendmail.php">
                        <div class="form-group">
                            <h3><label>Send Email To :</label></h3>
                            <textarea rows="3" style="width: 100%" name="selectedemails" id="selectedemails"
                                      readonly required></textarea>
                        </div>

                        <div class="form-group">
                            <h3><label>Subject</label></h3>
                            <input style="width: 100%" type="text" name="subject"
                                   placeholder="Enter Mail Subject Here!" maxlength="50" required/>
                        </div>

                        <div class="form-group">
                            <h3><label>Message</label></h3>
                            <textarea rows="3" name="message" class="form-control"
                                      placeholder="Type Your Message" maxlength="1000" required></textarea>
                        </div>

                        <div class="form-group">
                            <h4><label>Select Attachments if Any</label></h4>
                        </div>
                        <div class="form-group" id="moreUploads"></div>
                        <div class="form-group" id="moreUploadsLink" style="display:block;"><a
                                    href="javascript:addFileInput();"><span class="fa fa-paperclip"></span> Attach File</a>
                            <h6>(Max Size=4mb)</h6></div>
                        <div class="form-group">
                            <input name="submit" type="submit" value="Send" class="btn btn-raised btn-lg btn-warning"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery.multiselect.js"></script>
<script src="js/app.js"></script>

<script>
    window.load = showHideRowDiv(0);

    function showHideRowDiv(id) {
        //alert("Inside 0");
        if (id == '0') {
            $("#inputmailrow").hide();
            $("#case_no_row").hide();
        }

        if (id == 'inputtypeselect') {
            $("#inputmailrow").show();
            $("#bar_member_row").hide();
            $("#case_no_row").hide();

        } else if (id == 'searchbartypeselect') {
            $("#bar_member_row").show();
            $("#inputmailrow").hide();
            $("#case_no_row").hide();
        } else if (id == 'diarynoselect') {
            $("#bar_member_row").hide();
            $("#inputmailrow").hide();
            $("#case_no_row").show();
        } else if (id == 'showmailformfrominput') {
            var inputemails = $("#inputmail").val();
            $("#selectedemails").val(inputemails);

        } else if (id == 'showmailformfromsearch') {
            var arr = [];
            var fields = $('input[type=checkbox]:checked').serializeArray();
            jQuery.each(fields, function (i, field) {
                arr.push(field.value.split('##')[1]);
            });
            $("#selectedemails").val(arr);
        } else if (id == 'showmailformfromsdiarycaseno') {
            var arr = [];
            var fields = $('input[type=checkbox]:checked').serializeArray();
            jQuery.each(fields, function (i, field) {
                alert(field.value.t);exit;
                arr.push(field.value.split('##')[1]);
            });
            $("#selectedemails").val(arr);
        }
    }

    function showhidecasediarydiv(id) {
        if (id == 'casenoradio') {
            $("#casenoformdiv").show();
            $("#diarynoformdiv").hide();
        } else if (id == 'diarynoradio') {
            $("#casenoformdiv").hide();
            $("#diarynoformdiv").show();
        }
        $("#results").css({display: "none"});
    }


    var upload_number = 1;

    function addFileInput() {
        var d = document.createElement("div");
        var file = document.createElement("input");
        file.setAttribute("type", "file");
        file.setAttribute("accept", ".jpg,.jpeg,.png,.zip,.pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        file.setAttribute("name", "attachment" + upload_number);
        file.setAttribute("id", "attachment" + upload_number);
        file.setAttribute("onChange", "validateFileInput(" + upload_number + ")");
        d.appendChild(file);

        var remove = document.createElement("button");
        remove.setAttribute("type", "button");
        remove.setAttribute("name", "remove" + upload_number);
        remove.setAttribute("id", "remove" + upload_number);
        remove.setAttribute("class", "fas fa-times");
        remove.setAttribute("onclick", "removeFileInput(" + upload_number + ")");
        d.appendChild(remove);

        document.getElementById("moreUploads").appendChild(d);
        $("#moreUploadsLink").css({display: "none"});
        upload_number++;
    }

    function validateFileInput(upload_number) {
        const fi = document.getElementById('attachment' + upload_number);
        // Check if any file is selected.
        if (fi.files.length > 0) {
            for (i = 0; i <= fi.files.length - 1; i++) {
                const fsize = fi.files.item(i).size;
                const file = Math.round((fsize / 1024));
                // The size of the file.
                if (file >= 4096) {
                    alert("File too Big, please select a file less than 4mb");
                    fi.value = null;
                    return;
                }
            }
        }
        $("#moreUploadsLink").css({display: "block"});
    }

    function removeFileInput(upload_number) {
        if ($("#attachment" + upload_number).get(0).files.length == 0) {
            $("#moreUploadsLink").css({display: "block"});
        }

        $("#attachment" + upload_number).remove();
        $("#remove" + upload_number).remove();
    }

    function getAORPartyDetails() {
        var diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');
        if($("#casenoradio").is(':checked')){
            cstype = $("#selct").val();
            csno = $("#case_no").val();
            csyr = $("#case_yr").val();

            if(!regNum.test(cstype)){
                alert("Please Select Casetype");
                $("#selct").focus();
                return false;
            }
            if(!regNum.test(csno)){
                alert("Please Fill Case No in Numeric");
                $("#case_no").focus();
                return false;
            }
            if(!regNum.test(csyr)){
                alert("Please Fill Case Year in Numeric");
                $("#case_yr").focus();
                return false;
            }
            if(csno == 0){
                alert("Case No Can't be Zero");
                $("#case_no").focus();
                return false;
            }
            if(csyr == 0){
                alert("Case Year Can't be Zero");
                $("#case_yr").focus();
                return false;
            }

        }
        else if($("#diarynoradio").is(':checked')){
            diaryno = $("#dno").val();
            diaryyear = $("#dyr").val();
            if(!regNum.test(diaryno)){
                alert("Please Enter Diary No in Numeric");
                $("#dno").focus();
                return false;
            }
            if(!regNum.test(diaryyear)){
                alert("Please Enter Diary Year in Numeric");
                $("#dyr").focus();
                return false;
            }
            if(diaryno == 0){
                alert("Diary No Can't be Zero");
                $("#dno").focus();
                return false;
            }
            if(diaryyear == 0){
                alert("Diary Year Can't be Zero");
                $("#dyr").focus();
                return false;
            }
        }
        else{
            alert('Please Select Any Option');
            return false;
        }

        $.ajax({
            type: 'POST',
            url:"./getAORPartyDetails.php",
            beforeSend: function (xhr) {
                $("#results").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='img/load.gif'></div>");
            },
            data:{d_no:diaryno,d_yr:diaryyear,ct:cstype,cn:csno,cy:csyr}
        })
            .done(function(msg){
                $("#results").html(msg);
               if(msg!='')
                $("#results").css({display: "block"});
            })
            .fail(function(){
                alert("ERROR, Please Contact Server Room");
            });

    }


</script>

</body>
</html>
