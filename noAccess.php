<?php
    require 'sessionize.php';
    if(isAdmin()){
        $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
        header("Location: $redirect");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT Talent Transformation</title>
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
        <img src="images/banner.png" class="banner">        
    </div>
    <div class="container-fluid">
        <?php include("header.php");?>
        <div class="container-fluid">
            <script>
                $(document).ready(function() {
                    $("html, body").animate({ scrollTop: $(document).height()-$(window).height() }, 2000);
                    $('#myModal').modal('show');                           
                });
            </script>
            <div align="center" class="row">
                    <img class="img-responsive" src="images/block.jpg">
            </div>
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">                
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Permission Denied</h4>
                        </div>
                        <div class="modal-body">
                            <p>Admin Privileges Required For This Section !</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <?php include("footer.php");?>
    </div>
</body>
</html>