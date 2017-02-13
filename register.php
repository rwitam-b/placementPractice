<?php
    require 'tryLogin.php';
    require_once 'DB.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Student Registration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(window).load(function(){
            $('#passMatchError').hide();
        });
        function passMatch(){
            var pass=$("[name='pass']").val();
            var rePass=$("[name='rePass']").val();
            if(pass!="" && rePass!=""){
                $('#passMatchError').show();
                if(pass.localeCompare(rePass)!=0){
                    $('#passMatchError').addClass('alert-danger');
                    $('#passMatchError').removeClass('alert-success');
                    $('#passMatchError').html("Passwords Don't Match !");
                    $('#submitButton').prop("disabled",true);
                }
                else{
                    $('#passMatchError').html("Passwords Match !");;
                    $('#passMatchError').removeClass('alert-danger');
                    $('#passMatchError').addClass('alert-success');
                    $('#submitButton').prop("disabled",false);
                }
                $("html, body").animate({ scrollTop: $(document).height()-$(window).height() }, 1000);
            }
            else{
                $('#passMatchError').hide();
                $('#submitButton').prop("disabled",true);
                $('#passMatchError').removeClass('alert-success');
                $('#passMatchError').removeClass('alert-danger');
                $('#passMatchError').html("");
            }
        }
    </script>
</head>

<body>
    <?php
        error_reporting(0);
        $fname=$mname=$lname=$year=$stream=$roll=$sex=$email=$error="";
        function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
        }
        function test_blank($fname,$lname,$year,$stream,$roll,$sex,$email,$pass){
            if(empty($fname) or empty($lname) or empty($year) or empty($stream) or empty($roll) or empty($sex) or empty($email) or empty($pass)){
                return true;
            }
            else
                return false;
        }
        if (!isset($_SESSION['aotemail_username'])){
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                try{
                    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                    if(mysqli_connect_errno())
                        throw new Exception("Error Connecting To Database !");
                    $fname = mysqli_real_escape_string($con,test_input($_POST["fname"]));
                    $mname = mysqli_real_escape_string($con,test_input($_POST["mname"]));
                    $lname = mysqli_real_escape_string($con,test_input($_POST["lname"]));
                    $year = mysqli_real_escape_string($con,test_input($_POST["year"]));
                    $stream = mysqli_real_escape_string($con,test_input($_POST["stream"]));
                    $roll = mysqli_real_escape_string($con,test_input($_POST["roll"]));
                    $sex = mysqli_real_escape_string($con,test_input($_POST["sex"]));
                    $email = mysqli_real_escape_string($con,test_input($_POST["email"]));
                    $pass = mysqli_real_escape_string($con,test_input($_POST["pass"]));
                    $rePass = mysqli_real_escape_string($con,test_input($_POST["rePass"]));
                    $email=strtolower($email);
                    $sql="SELECT name FROM students WHERE email = '$email'";
                    $data = mysqli_query($con, $sql);
                    if(test_blank($fname,$lname,$year,$stream,$roll,$sex,$email,$pass))
                        $error="Please fill in all the fields !";
                    elseif (mysqli_num_rows($data) == 1){
                        $error="This email is already registered in the system !";
                    }
                    elseif(strcmp($pass,$rePass)!=0){
                        $error="The provided passwords do not match !";
                    }
                    else{
                        $matches="";
                        $message="";
                        if(preg_match_all("/[\d_\W]/i", $fname,$matches)){
                            $str="First Name Contains Bad Characters !<br>";
                            foreach($matches as $values){
                                foreach($values as $append){
                                    $str=$str.$append." ";
                                }
                            }
                            $message=$message.$str."<br><br>";
                        }
                        if(preg_match_all("/[\d_\W]/i", $mname,$matches)){
                            $str="Middle Name Contains Bad Characters !<br>";
                            foreach($matches as $values){
                                foreach($values as $append){
                                    $str=$str.$append." ";
                                }
                            }
                            $message=$message.$str."<br><br>";
                        }
                        if(preg_match_all("/[\d_\W]/i", $lname,$matches)){
                            $str="Last Name Contains Bad Characters !<br>";
                            foreach($matches as $values){
                                foreach($values as $append){
                                    $str=$str.$append." ";
                                }
                            }
                            $message=$message.$str."<br><br>";
                        }
                        if(strlen($roll)>5){
                            $str="Looks Like A Very Long Roll Number !<br>Try Your Class Roll Number Maybe ?";
                            $message=$message.$str."<br><br>";
                        }
                        if(preg_match_all("/[\W_]/i", $roll,$matches)){
                            $str="Roll Number Contains Bad Characters !<br>";
                            foreach($matches as $values){
                                foreach($values as $append){
                                    $str=$str.$append." ";
                                }
                            }
                            $message=$message.$str."<br><br>";
                        }
                        if(!preg_match_all("/^[tlr]?[\d]+$/i", $roll,$matches)){
                            $str="Bad Roll Number Format !";
                            $message=$message.$str."<br><br>";
                        }
                        if(!filter_var($email, FILTER_VALIDATE_EMAIL) === false){
                            if(!preg_match("/@aot.edu.in$/i",$email,$matches)){
                                $str="Please Use Your AOT Email ID !";
                                $message=$message.$str."<br><br>";
                            }
                        }
                        else{
                            $str="Please Use A Valid Email ID !";
                            $message=$message.$str."<br><br>";
                        }
                        if(!empty($message)){
                            throw new Exception($message);
                        }
                        $name=$fname." ".$mname." ".$lname;
                        $name=strtolower($name);
                        $arr=preg_split("/\s+/",$name);
                        $name=" ";
                        foreach($arr as $val){
                            $name=$name.strtoupper(substr($val,0,1)).substr($val,1)." ";
                        }
                        $name=trim($name);
                        $roll=strtoupper($roll);
                        $pass=strtoupper(md5($email.$pass));
                        $sql="INSERT INTO students(name,sex,year,stream,roll,email,password) VALUES('$name','$sex','$year','$stream','$roll','$email','$pass')";
                        if(mysqli_query($con,$sql)){
                            $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/afterReg.php?type=success';
                        }
                        else{
                            $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/afterReg.php?type=failure';
                        }
                        header('Location: '.$redirect);
                    }
                }
                catch(Exception $e){
                    $error=$e->getMessage();
                }
    ?>
            <script>
                $(document).ready(function(){
                    var stream="<?php echo $stream;?>";
                    var year="<?php echo $year;?>";
                    var sex="<?php echo $sex;?>";
                    $('select[name="stream"]').val(stream);
                    $('select[name="year"]').val(year);
                    $('input:radio[name="sex"]').val([sex]);
                });
            </script>
    <?php
            }
    ?>
    <script>
        $(document).ready(function(){
            $('.changeOnMouse').mouseenter(function(){
                $(this).children('option[value=""]').hide();
                $(this).removeClass("changeOnMouse");
            });
        });
    </script>
    <br>
    <div class="jumbotron">
        <h1 align="center">AOT Talent Transformation
        <br><small>Email Writing Practice</small></h1>
    </div>
    <div class="container-fluid">
        <?php include("header.php");?>
        <div class="container-fluid">
            <div class="row">
                <h2 class="text-center">Registration Form</h2>
            </div><br>
            <div class="container">
                <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="row form-group">
                        <div class="col-md-4 col-xs-12">
                            <input class="form-control" type="text" name="fname" placeholder="First Name" value="<?php echo $fname;?>" required>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <input class="form-control" type="text" name="mname" placeholder="Middle Name" value="<?php echo $mname;?>">
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <input class="form-control" type="text" name="lname" placeholder="Last Name" value="<?php echo $lname;?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-4 col-xs-12">
                            <select name="year" class="changeOnMouse form-control" required>
                                <option value="">Current Year</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <select name="stream" class="changeOnMouse form-control" required>
                                <option value="">Stream</option>
                                <option value="CSE">Computer Science &amp; Engineering</option>
                                <option value="EE">Electrical Engineering</option>
                                <option value="ECE">Electronics &amp; Communications Engineering</option>
                                <option value="EIE">Electronics &amp; Instrumentation Engineering</option>
                                <option value="IT">Information Technology</option>
                                <option value="ME">Mechanical Engineering</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <input name="roll" class="form-control" type="text" placeholder="Roll Number" required value="<?php echo $roll;?>">
                        </div>
                    </div>
                    <div align="center" class="row form-group">
                        <div class="col-md-4"></div>
                        <div class="col-md-2">
                            <label class="radio-inline"><input value="M" name="sex" id="sex" type="radio" required>Male</label>
                        </div>
                        <div class="col-md-2">
                            <label class="radio-inline"><input value="F" name="sex" id="sex" type="radio">Female</label>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-4"></div>
                        <div class="col-md-4 col-xs-12">
                            <input name="email" class="form-control" type="email" placeholder="Email ID" required value="<?php echo $email;?>">
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3"></div>
                        <div class="col-md-3 col-xs-12">
                            <input name="pass" class="form-control" type="password" placeholder="Enter Password" required onkeyup="passMatch()">
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <input name="rePass" class="form-control" type="password" placeholder="Retype Password" required onkeyup="passMatch()">
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-4"></div>
                        <div class="col-md-4 col-xs-12">
                            <div id="passMatchError" class="alert text-center"></div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-5"></div>
                        <div class="col-md-2 col-xs-12">
                            <input id="submitButton" class="btn btn-primary btn-block" type="submit" value="SIGN UP">
                        </div>
                        <div class="col-md-5"></div>
                    </div>
                    <?php
                        if(!empty($error)){
                    ?>
                    <script>
                        $(window).load(function() {
                            $("html, body").animate({ scrollTop: $(document).height()-$(window).height() }, 1000);
                        });
                    </script>
                    <div class="row">
                        <div class="col-xs-2"></div>
                        <div id="showMeTheError" align="center" class="col-xs-8 alert alert-danger text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong><?php echo $error?></strong>
                        </div>
                        <div class="col-xs-2"></div>
                    </div>
                    <?php
                        }
                    ?>
                </form>
            </div>
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
