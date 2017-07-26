<?php
    require 'tryLogin.php';
    require_once 'DB.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Essay Modification</title>
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
        $id=$question="";
        function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
        }
        function test_blank($question){
            if(empty($question))
                return true;
            else
                return false;
        }
        if(isset($_SESSION["aotemail_username"]) and isset($_SESSION["aotemail_admin"])){
            $idFlag="false";
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                try{
                    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                    if(mysqli_connect_errno())
                        throw new Exception("Could Not Connect To Database !");                    
                    $question = mysqli_real_escape_string($con,test_input($_POST["question"]));                    
                    if(isset($_POST["id"]) and !empty($_POST["id"]))
                        $idFlag=$_POST["id"];
                    $id=md5($question);
                    $sql="SELECT * FROM essay_questions WHERE id='$id'";
                    if(test_blank($question))
                        throw new Exception("Please Fill In The Question !");
                    else{
                        if(mysqli_num_rows(mysqli_query($con,$sql))>0){
                            throw new Exception("This Question Has Not Been Modified !");
                        }
                        $sql="INSERT INTO essay_questions VALUES('$id','$question')";
                        if(mysqli_query($con,$sql)){
                            $sql="SELECT COUNT(*) FROM essay_questions";
                            $result=mysqli_query($con,$sql);
                            $row=mysqli_fetch_array($result,MYSQLI_NUM);
                            $success="Essay Successfully Added Into The Database !<br>Total Question Count : ".$row[0];
                            $id=$question="";
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
                $.post("ajaxFormFields.php",{id: val,type: "2"},
                    function (response){
                        obj=JSON.parse(response);                        
                        $("textarea[name='question']").html(obj.question);                        
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
        });
    </script>
    <div class="container-fluid">
        <h2 align="center">Modify Existing Essay</h2><br>
        <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="row form-group">
                <div class="col-md-1"></div>
                <div class="col-md-10 col-xs-12">
                    <select name="id" class="form-control" id="select_email" onchange="refreshFields(this.value)">
                        <?php
                            echo "<option value=\"\">Select An Essay</option>";
                            try{
                                $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                                if(mysqli_connect_errno())
                                    throw new Exception("Could Not Connect To Database !");
                                $sql="SELECT * FROM essay_questions";
                                $result=mysqli_query($con,$sql);
                                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {                                    
                                    $ques=str_replace(PHP_EOL," ",$row["question"]);
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
            <div class="row form-group">
                <div class="col-md-2"></div>
                <div class="col-md-8 col-xs-12">
                    <textarea required name="question" style="resize:none;" rows=10 placeholder="Type question here ->" class="form-control"><?php echo str_replace("\\r\\n","\n",$question);?></textarea>
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
    <?php
        }else{
            $_SERVER['HTTP_REFERER']="test";
            include("noAccess.php");
        }
    ?>
    <?php include("footer.php");?>
</body>
</html>
