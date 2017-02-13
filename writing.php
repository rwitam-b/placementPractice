<?php
    require 'tryLogin.php';
    require_once 'DB.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Email Writing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="includes/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="includes/jquery-ui.js"></script>
</head>

<body oncontenxtmenu="return false;" oncnopy="return false;" onpnaste="return false;" oncut="return false;" ondrag="return false;" ondrop="return false;">
    <br>
    <div class="jumbotron">
        <h1 align="center">AOT  Talent Transformation
        <br><small>Email Writing Practice</small></h1>
    </div>
    <div class="container-fluid">
        <?php
            error_reporting(0);
            include("header.php");
            if(isset($_POST["validity"]) and strcmp($_POST["validity"],$_SESSION["aotemail_username"])==0){
                $email=$questions=$display="";
                try{
                    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                    if(mysqli_connect_errno())
                        throw new Exception();
                    $sql="SELECT value FROM settings WHERE field='emailTime'";
                    $result=mysqli_query($con,$sql);
                    $row = mysqli_fetch_assoc($result);
                    $DBtime=$row["value"];
                    if(isset($_SESSION["aotemail_student"])){
                        $email=$_SESSION["aotemail_student"];
                        $sql="SELECT questions_done FROM students WHERE email='$email'";
                        $result=mysqli_query($con, $sql);
                        $row = mysqli_fetch_array($result);
                        $questions=$row['questions_done'];
                    }
                    if(isset($_SESSION["aotemail_admin"])){
                        $email=$_SESSION["aotemail_admin"];
                        $questions="";
                    }
                    if(!empty($questions))
                        $sql="SELECT * FROM email_questions WHERE id NOT IN($questions) ORDER BY RAND() LIMIT 1";
                    else
                        $sql="SELECT * FROM email_questions ORDER BY RAND() LIMIT 1";
                    $result=mysqli_query($con, $sql);
                    $row = mysqli_fetch_array($result);
                    $q_id=$row['id'];
                    if(!empty($row))
                        $display="<strong>".$row['question']."<br><br>"."Outline : </strong>".implode(" - ",explode(",",$row['phrases']));
                    else
                        $display="<strong>No Questions Found In The Database !<br>";
                    $stat="yes";
                }
                catch(Exception $e){
                    $display="Error Connecting To Database !<br><br>Please Try Again Later !";
                    $stat="no";
                }
                if(isset($_SESSION["aotemail_student"]) or isset($_SESSION["aotemail_admin"])){

        ?>
        <script>
        words=0;
        $(document).ready(function(){
            var runningFunction=setInterval(countdown,1000);
            if($("#status").val()=="no"){
                $("#email_data").prop("disabled", true);
                clearInterval(runningFunction);
            }
            $("#headLine").css("background-color","#222222");
            $("#wordCount").animate({color : "red"},"slow");
            $("#timer").css("color","hsl(100,100%,50%)");
            var time="<?php echo $DBtime;?>";
            var initTime=time;
            minutes=(Math.floor(time/60)).toString();
            seconds=(time%60).toString();
            if(minutes.length==1)
                minutes="0"+minutes;
            if(seconds.length==1)
                seconds="0"+seconds;
            $("#showTime").html(minutes+":"+seconds);
            function countdown(){
                $("input[name='wordColor']").val($("#wordCount").css("color"));
                minutes=(Math.floor(time/60)).toString();
                seconds=(time%60).toString();
                if(minutes.length==1)
                    minutes="0"+minutes;
                if(seconds.length==1)
                    seconds="0"+seconds;
                $("#showTime").html(minutes+":"+seconds);
                if(time<parseInt(0.3*initTime))
                    $("#timerSubText").html("<small>Hurry Up Now !</small>");
                if(time==parseInt(0.5*initTime))
                    $("#timerSubText").html("<small>You should have completed a major portion by now !</small>");
                if(time>parseInt(0.5*initTime))
                    $("#timerSubText").html("<small>Time Has Started !</small>");
                colorCode=(Math.floor(time*100/initTime)).toString();
                $("#timer").css("color","hsl("+colorCode+",100%,50%)");
                time-=1;
                if(time<0){
                    $("#email_data").prop("disabled", true);
                    clearInterval(runningFunction);
                    $("#timerSubText").html("<small>Time Up !</small>");
                }
            }
            $("#email_data").keyup(function(){
                words=$(this).val().trim().split(/\b\W+\b/g).length;
                $("#showWords").html( ($(this).val().length) ? words : "0");
                $("#words").val($("#showWords").html());
                if(words<30)
                    $("#wordCountSubText").html("Too Small !");
                else if(words<50)
                    $("#wordCountSubText").html("Keep Going A Bit More !");
                else if(words>=50 && words <80)
                    $("#wordCountSubText").html("Seems About Right !");
                else if(words>=80 && words<100)
                    $("#wordCountSubText").html("It's Becoming Large Now !");
                else if(words>=100 && words<110)
                    $("#wordCountSubText").html("You Should Try To Conclude Now !");
                else
                    $("#wordCountSubText").html("You Should Definitely Stop Now !");
            });
        });
        function setColor(){
            if(words<20){
                $("#wordCount").animate({
                    color : "red"
                },"slow");
            }
            else if(words<50){
                $("#wordCount").animate({
                    color : "#FFA500"
                },"slow");
            }
            else if(words>=50 && words <80){
                $("#wordCount").animate({
                    color : "hsl(100,100%,50%)"
                },"slow");
            }
            else if(words>=80 && words<100){
                $("#wordCount").animate({
                    color : "#FFA500"
                },"slow");
            }
            else{
                $("#wordCount").animate({
                    color : "red"
                },"slow");
            }
        }
        setInterval(setColor,5000);
    </script>
        <div class="container-fluid">
            <div id="headLine" class="row">
                <div class="text-center col-md-4 col-xs-12">
                    <h4 id="wordCount"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;<strong>Word Count : <span id="showWords">0</span></strong><br><small><span id="wordCountSubText">Too Small !</span></small></h4>
                </div>
                <div class="col-md-4"></div>
                <div class="text-center col-md-4 col-xs-12">
                    <h4 id="timer"><span class="glyphicon glyphicon-time"></span>&nbsp;<strong>Remaining - <span id="showTime"></span></strong><br><span id="timerSubText"><small>Time Has Started !</small></span></h4>
                </div>
            </div>
            <div class="row">
                <br>
                <span class="text-center"><?php echo $display;?></span>
                <input type="hidden" id="status" value="<?php echo $stat;?>">
                <br><br>
            </div>
            <form method="post" action="result.php">
                <input type="hidden" name="q_id" value="<?php echo $q_id;?>">
                <input type="hidden" name="wordColor" value="">
                <input type="hidden" id="words" name="words" value="">
                <input type="hidden" name="question" value="<?php echo $display;?>">
                <div class="row form-group">
                    <textarea required name="text" unselectable="on" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" id="email_data" style="resize:none;" rows=10 placeholder="Type here ->" class="form-control col-md-12"></textarea>
                </div>
                <div class="row form-group">
                    <div class="col-md-5"></div>
                    <div class="col-md-2 col-xs-12">
                        <input class="btn btn-primary btn-block" type="submit" value="SUBMIT">
                    </div>
                    <div class="col-md-5"></div>
                </div>
            </form>
            <?php
                }
                else{
                    $_SERVER['HTTP_REFERER']="test";
                    include("loginAccess.php");
                }
            }
            else{
            ?>
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
                            <h4 class="modal-title">Access Denied</h4>
                        </div>
                        <div class="modal-body">
                            <p>Please Visit The <strong>Email Writing</strong> Section To Attempt An Email !</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    <?php include("footer.php");?>
    </div>
</body>
</html>
