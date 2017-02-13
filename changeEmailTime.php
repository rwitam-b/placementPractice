<?php
    require 'tryLogin.php';
    require_once 'DB.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Email Time Limit</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <br>
    <div class="jumbotron">
        <h1 align="center">AOT Talent Transformation
            <br><small>Email Writing Practice</small></h1>
    </div>
    <?php
        error_reporting(0);
        include("header.php");
        function test_blank($minutes,$seconds){            
            if(!empty($minutes)){                
                $count=1;
            }
            elseif(!empty($seconds)){
                $count=1;
            }
            else{
                $count=0;
            }
            return ($count==0)?true:false;
        }        
        if(isset($_SESSION["aotemail_username"]) and isset($_SESSION["aotemail_admin"])){                            
            try{
                $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 
                $time="";               
                if(mysqli_connect_errno())
                    throw new Exception("Could Not Connect To Database !");
                $sql="SELECT value FROM settings WHERE field='emailTime'";
                $result=mysqli_query($con,$sql);
                $row = mysqli_fetch_assoc($result);                
                $time="".intval($row["value"]/60)." Minutes, ".($row["value"]%60)." Seconds";
                if ($_SERVER["REQUEST_METHOD"] == "POST"){
                    $minutes=$_POST["minutes"];
                    $seconds=$_POST["seconds"];                                                      
                    if(test_blank($minutes,$seconds)){
                        throw new Exception("Please Fill In All The Fields !");    
                    }               
                    else{                        
                        if($row["value"]==($minutes*60)+$seconds){
                            throw new Exception("The Time Has Not Been Changed !");
                        }
                        $newTime=($minutes*60)+$seconds;
                        $sql="UPDATE settings SET value='$newTime' WHERE field='emailTime'";                                                
                        if(mysqli_query($con,$sql)){                            
                            $success="Time Limit Succesfully Changed In Database !";
                            $sql="SELECT value FROM settings WHERE field='emailTime'";
                            $result=mysqli_query($con,$sql);
                            $row = mysqli_fetch_assoc($result);                
                            $time="".intval($row["value"]/60)." Minutes, ".($row["value"]%60)." Seconds";
                            $minutes=$seconds="";
                        }
                        else{
                            throw new Exception("Some Technical Glitch Occured<br>Please Try Again Later !");
                        }
                    }
                }
                mysqli_close($con);
            }
            catch(Exception $e){
                $error=$e->getMessage();
            }
    ?>
    <div class="container-fluid">
        <h2 align="center">Change Email Time Limit</h2><br>
        <div class="row">
            <div class="col-lg-2"></div>
            <div align="center" class="col-lg-8 col-md-12 alert alert-info text-center">
                <strong>Current Time Limit : <?php echo $time;?></strong>
            </div>
            <div class="col-lg-2"></div>
        </div><br><br><br>
        <form class="form-inline" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-3 col-md-12 form-group">
                    <label class="control-label" for="minutes">Minutes:</label>
                    <input requird type="number" name="minutes" class="form-control" min="1" placeholder="Set Minutes">
                </div>
                <div class="col-lg-3 col-md-12 form-group">
                    <label class="control-label" for="seconds">Seconds:</label>
                    <input requred type="number" name="seconds" class="form-control" min="0" placeholder="Set Seconds">
                </div>
            </div><br><br>
            <div class="row">
                <div class="col-lg-5"></div>
                <div class="col-lg-2 col-md-12">
                    <input type="submit" class="btn btn-primary btn-block">
                </div>
                <div class="col-lg-5"></div>
            </div><br><br><br>
        </form>
        <?php
            if(!empty($error)){
        ?>
        <script>
            $(document).ready(function () {
                $("html, body").animate({
                    scrollTop: $(document).height() - $(window).height()
                }, 1000);
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
            if(!empty($success)){
        ?>
        <script>
            $(document).ready(function () {
                $("html, body").animate({
                    scrollTop: $(document).height() - $(window).height()
                }, 1000);
            });
        </script>
        <div class="row">
            <div class="col-md-2"></div>
            <div lign="center" class="col-md-8 col-xs-12 alert alert-success text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><?php echo $success?></strong>
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
        include("footer.php");
    ?>
</body>

</html>