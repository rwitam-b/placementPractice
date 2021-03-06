<?php
    require 'sessionize.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Picture Interpretation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="includes/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="includes/jquery.min.js"></script>
    <script src="includes/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .btn{
            white-space:normal !important;
            word-wrap:break-word;
        }
        .img-center{
            margin: auto;
        }
    </style>
</head>

<body>
    <br>
    <div class="jumbotron">          
        <img src="images/banner.png" class="banner banner-small">
        <h1 align="center"><small>Picture Interpretation Practice</small></h1>
    </div><br>
    <div class="container-fluid">
        <?php include("header.php");?>                
        <div class="row">
            <h2 class="text-center text-danger"><strong>You have all been writing essays since you were a kid</strong></h2>
            <h3 class="text-center text-info"><strong>This is just the same, only with a PICTURE instead of the essay topic!</strong></h3>
        </div><br><br>
        <div class="row cont">
            <img class="img-rounded img-responsive img-center" src="images/essayTips.jpg">
        </div><br><br>
        <div class="row">
            <form id="goToWrite" method="post" action="pictureWriting.php">
                <div class="col-md-5"></div>
                <div class="col-md-2">
                    <input type="hidden" name="validity" value="<?php echo sha1($_SESSION['username']);?>">
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
        <?php include("footer.php");?>
    </div>
</body>
</html>
