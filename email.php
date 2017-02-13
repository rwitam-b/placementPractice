<?php
    require 'tryLogin.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Email Writing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .btn{
            white-space:normal !important;
            word-wrap:break-word;
        }
    </style>
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
            if (isset($_SESSION['aotemail_username'])){
        ?>
        <div class="row">
            <h2 class="text-center text-danger"><strong>Go through the guide provided before you attempt the test</strong></h2>
        </div><br><br>
        <div class="row">
            <form id="goToWrite" method="post" action="writing.php">
                <div class="col-md-5"></div>
                <div class="col-md-2">
                    <input type="hidden" name="validity" value="<?php echo $_SESSION['aotemail_username'];?>">
                    <input id="start" class="btn btn-primary btn-block btn-lg" value="START">
                </div>
            </form>
            <script>
                $(document).ready(function() {
                    $('#start').click(function(){
                        $('#myModal').modal({backdrop: 'static', keyboard: false});
                    });
                });
            </script>
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">WARNING !</h4>
                        </div>
                        <div class="modal-body">
                            <p>Please take heed of the following points -></p>
                            <p>
                                <ul>
                                    <li>Do not change tabs</li>
                                    <li>Do not minimize the browser</li>
                                    <li>Do not try to open any other application</li>
                                    <li>Do not click anywhere else</li>
                                </ul>
                            </p>
                        </div>
                        <div class="modal-footer">
                        <button type="submit" class="btn btn-default" form="goToWrite">Agree And Start Test</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Go Back</button>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5"></div>
        </div><br>
        <div class="row">
              <h4 class="text-center text-danger"><small>(You cannot come back once you've started the test)</small></h4><br>
              <div class="alert alert-danger text-center"><strong>WARNING : ANY NAVIGATION ATTEMPT WILL AUTOMATICALLY END YOUR TEST !</strong></div>
        </div>
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
