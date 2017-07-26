<?php
    require 'tryLogin.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Email Writing</title>
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
    <div class="container-fluid">
        <?php include("header.php");?>
        <div class="container-fluid">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                    <li data-target="#myCarousel" data-slide-to="3"></li>
                    <li data-target="#myCarousel" data-slide-to="4"></li>
                    <li data-target="#myCarousel" data-slide-to="5"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="item active"> <img src="images/1.jpg" class="img-rounded img-responsive"> </div>
                    <div class="item"> <img src="images/2.jpg" class="img-rounded img-responsive"> </div>
                    <div class="item"> <img src="images/3.jpg" class="img-rounded img-responsive"> </div>
                    <div class="item"> <img src="images/4.jpg" class="img-rounded img-responsive"> </div>
                    <div class="item"> <img src="images/5.jpg" class="img-rounded img-responsive"> </div>
                    <div class="item"> <img src="images/6.jpg" class="img-rounded img-responsive"> </div>
                </div>
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a>
            </div>
        </div>
        <?php include("footer.php");?>
    </div>
</body>
</html>
