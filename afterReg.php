<?php
    require 'tryLogin.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Registration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="includes/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="includes/jquery.min.js"></script>
    <script src="includes/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <br>
    <div class="jumbotron">
        <h1 align="center">AOT Talent Transformation
        <br><small>Email Writing Practice</small></h1>
    </div>
    <div class="container">
        <?php
            include("header.php");
            $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
            $referer='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/register.php';
            if (!isset($_SESSION['aotemail_username']) and isset($_SERVER['HTTP_REFERER']) and strcmp($_SERVER['HTTP_REFERER'],$referer)==0){
        ?>
        <script>
            $(document).ready(function() {
                $("html, body").animate({ scrollTop: $(document).height()-$(window).height() }, 1000);
                $('#myModal').modal({backdrop: 'static', keyboard: false});
            });
        </script>
        <?php
            if(isset($_GET["type"])){
                if(strcmp($_GET["type"],"success")==0){
                    $head="Registration Successful !";
                    $body='<p>Please Login Using Your Credentials To Access Our Features.</p><p>Redirecting You In <span id="counter">5</span> Second(s).</p>';
                    $image="images/registered.png";
                }
                else{
                    $head="Registration Failed !";
                    $body='<p>Sorry ! There Were Some Technical Glitches At The Moment.</p><p>Redirecting You In <span id="counter">5</span> Second(s).</p>';
                    $image="images/failed.png";
                }
            }
            else{
                    $head="You Shouldn't Be Here Like This !";
                    $body='<p>I Will Show You The Way Out.</p><p>Redirecting You In <span id="counter">5</span> Second(s).</p>';
                    $image="images/failed.png";
                }

        ?>
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo $head;?></h4>
                    </div>
                    <div class="modal-body">
                        <?php echo $body;?>
                    </div>
                </div>
            </div>
        </div>
        <div align="center" class="row">
            <div class="col-xs-3"></div>
            <div class="col-xs-6">
                <img class="img-rounded img-responsive" src="<?php echo $image;?>">
            </div>
            <div class="col-xs-3"></div>
        </div>
        <?php
                setcookie("aotemail_username", "", time() - 3600,'/');
                setcookie("aotemail_student", "", time() - 3600,'/');
                setcookie("aotemail_admin", "", time() - 3600,'/');
                session_unset();
                if(isset($_COOKIE[session_name()])) {
                    setcookie(session_name(), '', time()-42000, '/');
                }
                session_destroy();
        ?>
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
                header('Location: '.$redirect);
            }
        ?>
        <?php include("footer.php");?>
    </div>
</body>
</html>
