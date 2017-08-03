<?php
    require 'sessionize.php';
    require_once 'DB.php';
    require 'adminPrivilege.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Picture Addition</title>
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
//         error_reporting(0);
        $caption="";
        function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
        }
        function test_blank($caption){
            if(empty($caption))
                return true;
            else
                return false;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            try{
                $filename=$filetype="";
                $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                if(mysqli_connect_errno())
                    throw new Exception("Could Not Connect To Database !");   
                $caption=test_input($_POST["caption"]);
                if(test_blank($caption)){
                    throw new Exception("Please provide a valid caption!");
                }
                $sql="SELECT * FROM picture_questions WHERE caption='$caption'";
                if(mysqli_num_rows(mysqli_query($con,$sql))>0){
                    throw new Exception("A picture with the provided caption already exists in the database!");
                }                    
                $saveDir="picQuestions/";
                if(isset($_POST["submit"])) {                        
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if($check !== false) {
                        $filename=md5_file($_FILES["image"]["tmp_name"]);
                        $filetype=".".pathinfo($_FILES["image"]["name"],PATHINFO_EXTENSION);
                        $target=$filename.$filetype;
                        if (file_exists($saveDir.$target)) {
                            throw new Exception("This picture already exists in the database!");
                        }
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $saveDir.$target)) {                                
                            $sql="INSERT INTO picture_questions VALUES('$caption','$target')";
                            if(mysqli_query($con,$sql)){                                    
                                $success="Picture Successfully Uploaded!";
                                $caption="";
                            }else{
                                throw new Exception("Picture upload failed because of database failure!");
                            }
                        } else {
                            throw new Exception("Picture upload failed!");
                        }
                    } else {
                        throw new Exception("File uploaded is not a valid image!");
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
        <h2 align="center">Add New Picture</h2><br>
        <form enctype="multipart/form-data" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <br>
            <div class="row form-group">
                <div class="col-md-2"></div>
                <div class="col-md-8 col-xs-12">
                    <input type="text" class="form-control" name="caption" value="<?php echo $caption;?>" required placeholder="Enter Picture Caption">
                </div>
                <div class="col-md-2"></div>
            </div>      
            <div class="row form-group">
                <div class="col-md-2"></div>
                <div class="col-md-8 col-xs-12">
                    <input type="file" class="form-control" name="image" required>
                </div>
                <div class="col-md-2"></div>
            </div><br>  
            <div class="row form-group">
                <div class="col-md-5"></div>
                <div class="col-md-2 col-xs-12">
                    <input id="submitButton" class="btn btn-primary btn-block" name="submit" type="submit" value="INSERT">
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
