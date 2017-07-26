<?php
    require 'tryLogin.php';
    require_once 'DB.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Email Addition</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <h1 align="center">AOT Talent Transformation
        <br><small>Email Writing Practice</small></h1>
    </div>
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
        if(isset($_SESSION["aotemail_username"]) and isset($_SESSION["aotemail_admin"])){
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
                    $id=md5($sender.$receiver.$question.$phrases.$r_type.$s_type);
                    $sql="SELECT * FROM email_questions WHERE id='$id'";
                    if(test_blank($sender,$receiver,$question,$phrases))
                        throw new Exception("Please Fill In All The Fields !");
                    else{
                        if(mysqli_num_rows(mysqli_query($con,$sql))>0){
                            throw new Exception("This Question Already Exists In The Database !");
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
        $(document).ready(function(){
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
                if($(this).is(':checked')){
                    $("input[name='receiver']").tagsinput("removeAll");
                    $("input[name='receiver']").tagsinput("destroy");
                    $("input[name='receiver_subtype']").bootstrapToggle("enable");
                    $("input[name='receiver']").attr("placeholder","Any Will Be Accepted");
                    $("input[name='receiver']").attr("disabled", "disabled");
                    $("input[name='r_type']").val("NS");
                }
                else{
                    $("input[name='receiver']").tagsinput("removeAll");
                    $("input[name='receiver']").tagsinput("destroy");
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
        <h2 align="center">Add New Email</h2><br>
        <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
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
                    <input id="submitButton" class="btn btn-primary btn-block" type="submit" value="INSERT">
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
    <?php
        }else{
            $_SERVER['HTTP_REFERER']="test";
            include("noAccess.php");
        }
    ?>
    <?php include("footer.php");?>
</body>
</html>
