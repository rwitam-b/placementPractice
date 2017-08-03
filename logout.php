<?php
    require 'sessionize.php';
    require 'loginPrivilege.php';  
    logout();  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Logout</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="includes/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="includes/animate.css">
    <script src="includes/jquery.min.js"></script>
    <script src="includes/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        #picture{
            margin-left: auto;
            margin-right: auto;
            display: block;
            height:60vh;
        }
        .flex{
            height:60vh;
            display: flex;            
            align-items: center; 
        }
    </style>
</head>

<body style="overflow-x:hidden;">
    <br>
    <div class="jumbotron">          
        <img src="images/banner.png" class="banner banner-small">          
    </div><br>
    <div class="container-fluid">
        <?php
            include("header.php");
        ?>              
        <div align="center" class="row">
            <div class="col-md-4 col-xs-12">                
                <marquee class="flex animated swing infinite" behavior="alternate" scrollamount="30"><h1>Good</h1></marquee>            
            </div>
            <div class="col-md-4 col-xs-12">
                <img id="picture" src="images/out.gif" class="img-responsive animated pulse infinite" alt="">
            </div>
            <div class="col-md-4 col-xs-12">                
                <marquee class="flex animated swing infinite" behavior="alternate" scrollamount="30"><h1>Bye !</h1></marquee>            
            </div>            
        </div>               
        <?php include("footer.php");?>
    </div>
</body>
</html>
