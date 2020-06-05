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
                        <!--  <option value="diarynoselect">Search by DiaryNo.</option>-->
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
                                    href="javascript:addFileInput();">Attach File</a></div>
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
        }

        if (id == 'inputtypeselect') {
            $("#inputmailrow").show();
            $("#bar_member_row").hide();

        } else if (id == 'searchbartypeselect') {
            $("#bar_member_row").show();
            $("#inputmailrow").hide();
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
        }
    }


    var upload_number = 1;

    function addFileInput() {
        var d = document.createElement("div");
        var file = document.createElement("input");
        file.setAttribute("type", "file");
        file.setAttribute("name", "attachment" + upload_number);
        file.setAttribute("id", "attachment" + upload_number);
        file.setAttribute("onChange", '$("#moreUploadsLink").css({ display: "block" });');
        d.appendChild(file);

        var remove = document.createElement("button");
        remove.setAttribute("type", "button");
        remove.setAttribute("name", "remove" + upload_number);
        remove.setAttribute("id", "remove" + upload_number);
        remove.setAttribute("class", "btn btn-raised btn-lg btn-danger");
        remove.setAttribute("onclick", "removeFileInput(" + upload_number + ")");
        d.appendChild(remove);

        document.getElementById("moreUploads").appendChild(d);
        $("#moreUploadsLink").css({display: "none"});
        upload_number++;
    }

    function removeFileInput(upload_number) {
        if ($("#attachment" + upload_number).get(0).files.length == 0) {
            $("#moreUploadsLink").css({display: "block"});
        }

        $("#attachment" + upload_number).remove();
        $("#remove" + upload_number).remove();
    }


</script>

</body>
</html>
