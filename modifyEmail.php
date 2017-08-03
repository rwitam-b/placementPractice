<?php
    require 'sessionize.php';
    require_once 'DB.php';
    require 'adminPrivilege.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Email Modification</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="includes/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="includes/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="includes/bootstrap-tagsinput.css">
    <script src="includes/jquery.min.js"></script>
    <script src="includes/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="includes/bootstrap-tagsinput.js"></script>
    <script src="includes/bootstrap-toggle.min.js"></script>
    <style>
        .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
        .toggle.ios .toggle-handle { border-radius: 20px; }
    </style>
</head>
<body>
    <br>
    <div class="jumbotron">          
        <img src="images/banner.png" class="banner banner-small">
        <h1 align="center"><small>Admin Panel</small></h1>
    </div><br>
    <?php
        include("header.php");
        error_reporting(0);
        $id=$sender=$receiver=$question=$phrases=$error=$success="";
        function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
        }
        function test_blank($sender,$receiver,$question,$phrases){
            if(empty($sender) or empty($receiver) or empty($question) or empty($phrases))
                return true;
            else
                return false;
        }        
        $idFlag="false";
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            try{
                $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                if(mysqli_connect_errno())
                    throw new Exception("Could Not Connect To Database !");
                $sender = mysqli_real_escape_string($con,test_input($_POST["sender"]));
                $receiver = mysqli_real_escape_string($con,test_input($_POST["receiver"]));
                $question = mysqli_real_escape_string($con,test_input($_POST["question"]));
                $phrases = mysqli_real_escape_string($con,test_input($_POST["phrases"]));
                $s_type = $_POST["s_type"];
                $r_type= $_POST["r_type"];
                if(strcmp($s_type,"N")==0)
                    $sender="--ANY--";
                if(strcmp($r_type,"NS")==0)
                    $receiver="--ANY--";
                if(isset($_POST["id"]) and !empty($_POST["id"]))
                    $idFlag=$_POST["id"];
                $id=md5($sender.$receiver.$question.$phrases.$r_type.$s_type);
                $sql="SELECT * FROM email_questions WHERE id='$id'";
                if(test_blank($sender,$receiver,$question,$phrases))
                    throw new Exception("Please Fill In All The Fields !");
                else{
                    if(mysqli_num_rows(mysqli_query($con,$sql))>0){
                        throw new Exception("This Question Has Not Been Modified !");
                    }
                    $sql="INSERT INTO email_questions VALUES('$id','$sender','$receiver','$question','$phrases','$r_type','$s_type')";
                    if(mysqli_query($con,$sql)){
                        $sql="SELECT COUNT(*) FROM email_questions";
                        $result=mysqli_query($con,$sql);
                        $row=mysqli_fetch_array($result,MYSQLI_NUM);
                        $success="Email Successfully Added Into The Database !<br>Total Question Count : ".$row[0];
                        $id=$sender=$receiver=$question=$phrases=$error="";
                    }
                    else{
                        throw new Exception("Some Technical Glitch Occured<br>Please Try Again Later !");
                    }
                }
            }
            catch(Exception $e){
                $error=$e->getMessage();
            }
        }
    ?>
    <script>
        $(document).on("keypress", ":input:not(textarea)", function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
            }
        });
        function refreshFields(val){
            if(val){
                $.post("ajaxFormFields.php",{id: val,type: "1"},
                    function (response){
                        obj=JSON.parse(response);
                        if(obj.sender_type.localeCompare("S")==0){
                            $("input[name='sender_type']").bootstrapToggle("off");
                            $("input[name='sender']").val(obj.sender);
                        }
                        else{
                            $("input[name='sender_type']").bootstrapToggle("on");
                        }
                        if(obj.receiver_type.localeCompare("S")==0){
                            $("input[name='receiver_type']").bootstrapToggle("off");
                            $("input[name='receiver']").val(obj.receiver);
                        }
                        else if(obj.receiver_type.localeCompare("NS")==0){
                            $("input[name='receiver_type']").bootstrapToggle("on");
                        }
                        else{
                            $("input[name='receiver_type']").bootstrapToggle("on");
                            $("input[name='receiver_subtype']").bootstrapToggle("on");
                            $("input[name='receiver']").tagsinput('add',obj.receiver);
                        }
                        $("textarea[name='question']").html(obj.question);
                        $("input[name='phrases']").tagsinput('removeAll');
                        $("input[name='phrases']").tagsinput('add', obj.phrases);
                    }
                );
            }
            else{
                $("input[name='sender']").val("");
                $("input[name='receiver']").val("");
                $("textarea[name='question']").html("");
                $("input[name='phrases']").tagsinput('removeAll');
                $("input[name='receiver_type']").bootstrapToggle("off");
                $("input[name='sender_type']").bootstrapToggle("off");
            }
        }
        $(document).ready(function(){
            var test=<?php echo json_encode($idFlag);?>;
            if(test.localeCompare("false")){
                $("#select_email").val(test);
                refreshFields(test);
            }
            $("input[name='receiver']").tagsinput("destroy");
            $("input[name='sender_type']").change(function(){
                if($(this).is(':checked')){
                    $("input[name='s_type']").val("N");
                    $("input[name='sender']").attr("placeholder","Any Name Will Be Accepted");
                    $("input[name='sender']").attr("disabled", "disabled");
                    $("input[name='sender']").val("");
                }
                else{
                    $("input[name='s_type']").val("S");
                    $("input[name='sender']").attr("placeholder","Specific Sender Name");
                    $("input[name='sender']").removeAttr("disabled");
                    $("input[name='sender']").val("");
                }
            });
            $("input[name='receiver_type']").change(function(){
                $("input[name='receiver']").tagsinput("removeAll");
                $("input[name='receiver']").val("");
                $("input[name='receiver']").tagsinput("destroy");
                if($(this).is(':checked')){
                    $("input[name='receiver_subtype']").bootstrapToggle("enable");
                    $("input[name='receiver']").attr("placeholder","Any Will Be Accepted");
                    $("input[name='receiver']").attr("disabled", "disabled");
                    $("input[name='r_type']").val("NS");
                }
                else{
                    $("input[name='receiver_subtype']").bootstrapToggle("off");
                    $("input[name='receiver_subtype']").bootstrapToggle("disable");
                    $("input[name='receiver']").attr("placeholder","Specific Receiver Name");
                    $("input[name='receiver']").removeAttr("disabled");
                    $("input[name='r_type']").val("S");
                }
            });
            $("input[name='receiver_subtype']").change(function(){
                $("input[name='receiver']").tagsinput("removeAll");
                $("input[name='receiver']").tagsinput("destroy");
                if($(this).is(':checked')){
                    $("input[name='r_type']").val("NP");
                    $("input[name='receiver']").removeAttr("disabled");
                    $("input[name='receiver']").attr("placeholder","Generic Receivers");
                    $("input[name='receiver']").tagsinput("refresh");
                }
                else{
                    $("input[name='r_type']").val("NS");
                    $("input[name='receiver']").attr("disabled", "disabled");
                    $("input[name='receiver']").attr("placeholder","Any Will Be Accepted");
                }
            });
        });
    </script>
    <div class="container-fluid">
        <h2 align="center">Modify Existing Email</h2><br>
        <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="row form-group">
                <div class="col-md-1"></div>
                <div class="col-md-10 col-xs-12">
                    <select name="id" class="form-control" id="select_email" onchange="refreshFields(this.value)">
                        <?php
                            echo "<option value=\"\">Select A Question</option>";
                            try{
                                $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                                if(mysqli_connect_errno())
                                    throw new Exception("Could Not Connect To Database !");
                                $sql="SELECT * FROM email_questions";
                                $result=mysqli_query($con,$sql);
                                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                    $ques=$row["question"];
                                    $ques=$row["sender"]." -> ".$row["receiver"]." : ".str_replace(PHP_EOL," ",$ques);
                                    if(strlen($ques)>150)
                                        $ques=substr($ques,0,150)."....";
                                    echo "<option value=\"".$row["id"]."\">".$ques."</option>";
                                }
                                mysqli_free_result($result);
                                mysqli_close($con);
                            }
                            catch(Exception $e){
                                echo "<option value=''>".$e->getMessage()."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-1"></div>
            </div><br><br>
            <input type="hidden" name="s_type" value="S">
            <input type="hidden" name="r_type" value="S">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-3 col-xs-12">
                    <input type="checkbox" data-style="ios" name="sender_type" data-size="normal" data-onstyle="primary" data-offstyle="primary" data-toggle="toggle" data-on="Not Specified" data-off="Specified">
                </div>
                <div class="col-md-4 col-xs-12">
                    <input type="text" class="form-control" name="sender" required placeholder="Specific Sender Name" value="<?php echo $sender;?>">
                </div>
                <div class="col-md-2"></div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-2 col-xs-12">
                    <input type="checkbox" data-style="ios" name="receiver_type" data-size="normal" data-onstyle="primary" data-offstyle="primary" data-toggle="toggle" data-on="Not Specified" data-off="Specified">
                </div>
                <div class="col-md-2 col-xs-12">
                    <input type="checkbox" data-style="ios" disabled name="receiver_subtype" data-size="normal" data-onstyle="primary" data-offstyle="primary" data-toggle="toggle" data-on="Generic" data-off="Singular">
                </div>
                <div class="col-md-4 col-xs-12">
                    <input type="text" data-role="tagsinput" class="form-control" name="receiver" required placeholder="Specific Receiver Name" value="<?php echo $receiver;?>">
                </div>
                <div class="col-md-2"></div>
            </div>
            <br>
            <div class="row form-group">
                <div class="col-md-2"></div>
                <div class="col-md-8 col-xs-12">
                    <textarea required name="question" style="resize:none;" rows=10 placeholder="Type question here ->" class="form-control"><?php echo str_replace("\\r\\n","\n",$question);?></textarea>
                </div>
                <div class="col-md-2"></div>
            </div>
            <div class="row form-group">
                <div class="col-md-2"></div>
                <div class="col-md-8 col-xs-12">
                    <input type="text" data-role="tagsinput" class="form-control" name="phrases" placeholder="Enter Phrases In Order" value="<?php echo $phrases;?>" required>
                </div>
                <div class="col-md-2"></div>
            </div>
            <div class="row form-group">
                <div class="col-md-5"></div>
                <div class="col-md-2 col-xs-12">
                    <input id="submitButton" class="btn btn-primary btn-block" type="submit" value="MODIFY">
                </div>
                <div class="col-md-5"></div>
            </div>
        </form>
        <?php
            if(!empty($success)){
        ?>
        <script>
            $(document).ready(function() {
                $("html, body").animate({ scrollTop: $(document).height()-$(window).height() }, 1000);
            });
        </script>
        <div class="row">
            <div class="col-md-2"></div>
            <div align="center" class="col-md-8 col-xs-12 alert alert-success text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><?php echo $success?></strong>
            </div>
            <div class="col-md-2"></div>
        </div>
        <?php
            }
        ?>
        <?php
            if(!empty($error)){
        ?>
        <script>
            $(document).ready(function() {
                $("html, body").animate({ scrollTop: $(document).height()-$(window).height() }, 1000);
            });
        </script>
        <div class="row">
            <div class="col-md-2"></div>
            <div align="center" class="col-md-8 col-xs-12 alert alert-danger text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><?php echo $error?></strong>
            </div>
            <div class="col-md-2"></div>
        </div>
        <?php
            }
        ?>
    </div>    
    <?php include("footer.php");?>
</body>
</html>
