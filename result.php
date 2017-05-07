<?php
    require 'tryLogin.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Result</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
    .myWell{
        border: 1px dashed #000000;
    }
    pre{
        border: 1px solid #888888;
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
        <?php
            error_reporting(0);
            include("header.php");
            $referer="writing.php";
            if (isset($_SESSION['aotemail_username'])){
                if(isset($_SERVER['HTTP_REFERER']) and strpos($_SERVER['HTTP_REFERER'], $referer)!== false and $_SERVER["REQUEST_METHOD"]=="POST"){
                    $text=htmlspecialchars(trim($_POST["text"]));
                    $toProcess=str_replace(PHP_EOL,"<--rb-->",$text);
                    $toProcess=str_replace(" ","<sp>",$toProcess);
                    $question=$_POST["question"];
                    $processQuestion=str_replace(PHP_EOL,"<--rb-->",$question);
                    $processQuestion=str_replace(" ","<sp>",$question);
                    $words=$_POST["words"];
                    $wordColor=(string)$_POST["wordColor"];
                    if(intval($words)<50)
                        $wordSubtext="The Email Is Too Short !";
                    elseif(intval($words)>=50 and intval($words)<80)
                        $wordSubtext="Looks Good !";
                    else
                        $wordSubtext="You Seem To Have Crossed The Average Word Limit !";
                    $q_id=$_POST["q_id"];
        ?>
        <div class="container-fluid">
            <div id="headLine" class="row">
                <div class="text-center col-md-4 col-xs-12">
                    <h4 id="wordCount"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;<strong>Word Count : <?php echo $words;?></strong><br><small><?php echo $wordSubtext;?></small></h4>
                </div>
                <div class="col-md-4"></div>
                <div class="text-center col-md-4 col-xs-12">
                    <h4 id="timer"><span class="glyphicon glyphicon-time"></span>&nbsp;<strong>Remaining - 00:00</strong><br><small>Time Up !</small></h4>
                </div>
            </div>
            <div class="row">
                <br>
                <span class="text-center"><?php echo $question;?></span>
                <br><br>
            </div>
            <div class="row">
                <div class="well">
                    <h4 align="center"><strong>Email You Wrote</strong></h4>
                    <textarea disnabled style="resize:none;" rows=10 class="form-control"><?php echo $text;?></textarea>
                </div>
            </div>
            <div id="check" class="container">
            </div>
            <script>
                $(document).ready(function(){
                    $("#headLine").css("background-color","#222222");
                    $("#timer").css("color","red");
                    $("#wordCount").css("color","<?php echo $wordColor;?>");
                    $("html, body").animate({ scrollTop: 300 }, 2000);
                    $.post("ajaxResults.php",{"q_id": "<?php echo $q_id;?>", "text": "<?php echo $toProcess;?>"},
                    function (response){
                        try{
                            $("#check").html(response);
                            $.post("ajaxSaveFile.php",{"text": "<?php echo $toProcess;?>","question": "<?php echo $processQuestion;?>"},
                            function (response){
                                $("#check").html($("#check").html()+response);
                            });
                        }
                        catch(err){
                            $("#check").html("<div class=\"row text-center alert alert-danger\"><strong>A Technical Glitch Occured !<br>Please Try Again Later !</strong></div>");
                        }
                    });
                });
        </script>
            <?php
                }
                else{
                    $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                    header('Refresh:0;url='.$redirect);
                }
            }
            else{
                $_SERVER['HTTP_REFERER']="test";
                include("loginAccess.php");
            }
            ?>
        </div>
    <?php include("footer.php");?>
    </div>
</body>
</html>
