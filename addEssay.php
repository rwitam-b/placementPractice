<?php
    require 'sessionize.php';
    require_once 'DB.php';
    require 'adminPrivilege.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Essay Addition</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="includes/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="includes/jquery.min.js"></script>
    <script src="includes/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            try{
                $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                if(mysqli_connect_errno())
                    throw new Exception("Could Not Connect To Database !");                    
                $question = mysqli_real_escape_string($con,test_input($_POST["question"]));                    
                $id=md5($question);
                $sql="SELECT * FROM essay_questions WHERE id='$id'";
                if(test_blank($question))
                    throw new Exception("Please Fill In The Question !");
                else{
                    if(mysqli_num_rows(mysqli_query($con,$sql))>0){
                        throw new Exception("This Essay Question Already Exists In The Database !");
                    }
                    $sql="INSERT INTO essay_questions VALUES('$id','$question')";
                    if(mysqli_query($con,$sql)){
                        $sql="SELECT COUNT(*) FROM essay_questions";
                        $result=mysqli_query($con,$sql);
                        $row=mysqli_fetch_array($result,MYSQLI_NUM);
                        $success="Essay Question Successfully Added Into The Database !<br>Total Question Count : ".$row[0];
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
    </script>
    <div class="container-fluid">
        <h2 align="center">Add New Essay</h2><br>
        <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <br>
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
    <?php include("footer.php");?>
</body>
</html>
