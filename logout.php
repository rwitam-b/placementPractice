<?php
    require 'tryLogin.php';
    $flag=false;
    if (isset($_SESSION['aotemail_username'])){
        setcookie("aotemail_username","",  time() - 3600, "/");
        setcookie("aotemail_student", "", time() - 3600, "/");
        setcookie("aotemail_admin", "", time() - 3600, "/");
        if(isset($_COOKIE[session_name()]))
            setcookie(session_name(), "", time()-42000, "/");
        session_unset();
        session_destroy();
        $flag=true;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Logout</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <br>
    <div class="jumbotron">
        <h1 align="center">AOT Talent Transformation
        <br><small>Email Writing Practice</small></h1>
    </div>
    <div class="container-fluid">
        <?php include("header.php");?>
        <?php
            $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
            if ($flag){
        ?>
        <script>
            $(document).ready(function() {
                $('#myModal').modal({backdrop: 'static', keyboard: false});
            });
        </script>
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Thank You For Using Our Services</h4>
                    </div>
                    <div class="modal-body">
                        <p>Logging Out In <span id="counter">5</span> Second(s).</p>
                    </div>
                </div>
            </div>
        </div>
        <div align="center" class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 col-xs-12">
                <img class="img-rounded" src="images/out.gif">
                <marquee behavior="alternate" scrollamount="15"><h1 class="text-muted">BYE BYE !</h1></marquee>
            </div>
            <div class="col-md-3"></div>
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
            else{
                $_SERVER['HTTP_REFERER']="test";
                include("loginAccess.php");
            }
        ?>
        <?php include("footer.php");?>
    </div>
</body>
</html>
