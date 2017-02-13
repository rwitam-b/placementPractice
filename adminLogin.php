<?php
    require 'tryLogin.php';
    require_once 'DB.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Admin Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <?php
        $error=$success=$admin_id=$pass="";
        function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
        }
        if (!isset($_SESSION['aotemail_username']) and !isset($_SESSION['aotemail_admin'])){
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                try{
                    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                    if(mysqli_connect_errno())
                        throw new Exception();
                    $admin_id = mysqli_real_escape_string($con,test_input($_POST["inputEmail"]));
                    $pass = mysqli_real_escape_string($con,test_input($_POST["inputPassword"]));
                    $admin_id=strtolower($admin_id);
                    $pass=strtoupper(md5($admin_id.$pass));
                    if (!empty($admin_id) and !empty($pass)){
                        $sql="SELECT name,password FROM admins WHERE email = '$admin_id'";
                        $data = mysqli_query($con, $sql);
                        if (mysqli_num_rows($data) == 1){
                            $row = mysqli_fetch_array($data);
                            if(strcmp($row['password'],$pass)==0){
                                session_unset();
                                session_destroy();
                                setcookie("aotemail_username","",  time() - 3600, "/");
                                setcookie("aotemail_student", "", time() - 3600, "/");
                                setcookie("aotemail_admin", "", time() - 3600, "/");
                                session_start();
                                $_SESSION['aotemail_username']=$row['name'];
                                $_SESSION['aotemail_admin']=$admin_id;
                                setcookie("aotemail_username", $_SESSION['aotemail_username'], time() + (86400 * 30), "/");
                                setcookie("aotemail_admin", $_SESSION['aotemail_admin'], time() + (86400 * 30), "/");
                                $success='Login Succesful !<br>Redirecting to home page in <span id="counter">5</span> secs.... !';
                            }
                            else{
                                $error="Admin Found In The System !<br>Please Provide The Correct Password To Get In !";
                            }
                        }
                        else{
                            $error="Admin Not Registered In The System !";
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
        }

    ?>
    <div class="jumbotron">
        <h1 align="center">AOT Talent Transformation
        <br><small>Email Writing Practice</small></h1>
    </div>
    <div class="container-fluid">
        <?php include("header.php");?>
        <?php
            if (isset($_SESSION['aotemail_username']) and isset($_SESSION['aotemail_student'])){
                $_SERVER['HTTP_REFERER']="test";
                include("noAccess.php");
            }
            elseif(isset($_SESSION['aotemail_admin']) and isset($_SESSION['aotemail_username'])){
                $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
        ?>
        <script>
            $(document).ready(function() {
                window.location = '<?php echo $redirect; ?>';
            });
        </script>
        <?php
            }
            else{
        ?>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <h2 class="text-center">Admin Login</h2>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <input type="email" name="inputEmail" class="form-control" placeholder="User ID" value="<?php echo $admin_id;?>" required autofocus><br>
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
                if(!empty($success)){
            ?>
            <div class="col-md-6 alert alert-success text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><?php echo $success?></strong>
            </div>
            <script>
            function countdown() {
                var i = document.getElementById('counter');
                if (parseInt(i.innerHTML)<=2) {
                    location.href = '<?php echo $redirect;?>';
                }
                i.innerHTML = parseInt(i.innerHTML)-1;
            }
            setInterval(function(){ countdown(); },1000);
            </script>
            <?php
                }
            ?>
            <div class="col-md-3"></div>
        </div>
        <?php
            }
        ?>
    </div>
    <?php include("footer.php");?>
</body>
</html>
