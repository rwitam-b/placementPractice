<?php
    require 'sessionize.php';
    require_once 'DB.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="includes/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="includes/jquery.min.js"></script>
    <script src="includes/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body><br>
    <?php
        error_reporting(0);
        $error=$success=$u_id=$pass="";
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        if (!isLoggedIn()){
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                try{
                    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                    if(mysqli_connect_errno())
                        throw new Exception();
                    $u_id = mysqli_real_escape_string($con,test_input($_POST["inputEmail"]));
                    $pass = mysqli_real_escape_string($con,test_input($_POST["inputPassword"]));
                    $u_id=strtolower($u_id);                    
                    if (!empty($u_id) and !empty($pass)){
                        $sql="SELECT name,password FROM students WHERE email = '$u_id'";
                        $data = mysqli_query($con, $sql);
                        if (mysqli_num_rows($data) == 1){
                            $row = mysqli_fetch_array($data);                            
                            if(password_verify($u_id.$pass,$row['password'])){
                                session_unset();
                                session_destroy();                                
                                session_start();
                                session_regenerate_id(true);
                                $_SESSION['username']=$row['name'];
                                $_SESSION['email']=$u_id;
                                $_SESSION['admin']="false";
                                $_SESSION['fingerprint']=sha1($_SESSION['email'].$_SESSION['admin'].$_SERVER['HTTP_USER_AGENT']);
                                $_SESSION['lastActivity']=time();
                                $success='Login Succesful !<br>Redirecting To Home Page...';
                                $u_id="";
                            }
                            else{
                                $error="User Found In The System !<br>Please Provide The Correct Password To Gain Access !";
                            }
                        }
                        else{
                            $error="User Not Registered In The System !";
                        }
                    }
                    else{
                            $error="Invalid Username/Password !";
                    }
                }
                catch(Exception $e){
                    $error="Error Connecting To Database !";
                }
            }
    ?>
    <div class="jumbotron">          
        <img src="images/banner.png" class="banner">        
    </div><br>
    <div class="container-fluid">
        <?php include("header.php");?>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <h2 class="text-center">Student Login</h2>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <input type="email" name="inputEmail" class="form-control" placeholder="User ID" value="<?php echo $u_id;?>" required autofocus><br>
                    <input type="password" name="inputPassword" class="form-control" placeholder="Password" required>
                    <br>
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Sign In">
                </form>
            </div>
            <div class="col-md-4"></div>
        </div><br><br>
        <div class="row">
            <div class="col-md-3"></div>
            <?php
                $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                if(!empty($error)){
            ?>
                <div class="col-md-6 alert alert-danger text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong><?php echo $error?></strong>
                </div>
            <?php
                }
            ?>
            <?php
                if(!empty($success)){
            ?>
                <div class="col-md-6 alert alert-success text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong><?php echo $success?></strong>
                </div>
            <script>            
                setTimeout(function(){ location.href = '<?php echo $redirect;?>'; },2000);
            </script>
            <?php
                }
            ?>
            <div class="col-md-3"></div>
        </div>
        <?php include("footer.php");?>
    </div>
    <?php
        }
        else{
            $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
            header('Refresh:0;url='.$redirect);
        }
    ?>
</body>
</html>
