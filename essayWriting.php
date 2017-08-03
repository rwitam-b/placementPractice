<?php
    require 'sessionize.php';
    require 'loginPrivilege.php';
    require_once 'DB.php';
    if(isset($_POST["validity"]) and strcmp($_POST["validity"],sha1($_SESSION["username"]))==0){
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Essay Writing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="includes/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="includes/jquery-ui.css">
    <script src="includes/jquery.min.js"></script>
    <script src="includes/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="includes/jquery-ui.js"></script>
</head>

<body oncontextmenu="return false;" oncopy="return false;" onphaste="return false;" oncut="return false;" ondrag="return false;" ondrop="return false;">
    <br>
    <div class="jumbotron">          
        <img src="images/banner.png" class="banner banner-small">
        <h1 align="center"><small>Essay Writing Practice</small></h1>
    </div><br>
    <div class="container-fluid">
        <?php
            error_reporting(0);
            include("header.php");

            function csvToString($str){                
                if(empty($str)){      
                    return "''";
                }else{
                    $arr=explode(",",$str);
                    for($a=0;$a<count($arr);$a++){
                        $arr[$a]="'".$arr[$a]."'";
                    }
                    return implode(",",$arr);
                }
            }

            function add($existing,$addition){  
                if(empty($existing)){      
                    return $addition;
                }else{
                    $arr=explode(",",$existing);
                    array_push($arr,$addition);
                    return implode(",",$arr);
                }
            }
            
            $email=$questions=$display="";
            try{
                $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                if(mysqli_connect_errno())
                    throw new Exception();
                $sql="SELECT value FROM settings WHERE field='essayTime'";
                $result=mysqli_query($con,$sql);
                $row = mysqli_fetch_assoc($result);
                $DBtime=$row["value"];
                $email=$_SESSION["email"];
                if(!isAdmin()){                    
                    $sql="SELECT questions_essay FROM students WHERE email='$email'";
                    $result=mysqli_query($con, $sql);
                    $row = mysqli_fetch_array($result);
                    $ques=$row['questions_essay'];                        
                    $questions=csvToString($ques);
                    $sql="SELECT * FROM essay_questions WHERE id NOT IN($questions) ORDER BY RAND() LIMIT 1";
                    $result=mysqli_query($con, $sql);
                    $row = mysqli_fetch_array($result);
                    if(!empty($row)){                           
                        $ques=add($ques,$row['id']);
                        $sql="UPDATE students SET questions_essay='$ques' WHERE email='$email'";
                        mysqli_query($con, $sql);
                    }
                    else{
                        $sql="SELECT * FROM essay_questions ORDER BY RAND() LIMIT 1";
                        $result=mysqli_query($con, $sql);
                        $row = mysqli_fetch_array($result);                            
                    }                    
                }
                else{
                    $sql="SELECT * FROM essay_questions ORDER BY RAND() LIMIT 1";
                    $result=mysqli_query($con, $sql);
                    $row = mysqli_fetch_array($result);     
                }                                   
                if(!empty($row)){
                    $q_id=$row['id'];
                    $display=$row['question'];                        
                }
                else
                    $display="No Questions Found In The Database !";
                $stat="yes";
            }
            catch(Exception $e){
                $display="Error Connecting To Database !<br><br>Please Try Again Later !";
                $stat="no";
            }
        ?>
        <?php
            if(!empty($q_id)){
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
                    if(words<=100)
                        $("#wordCountSubText").html("Too Small !");
                    else if(words<150)
                        $("#wordCountSubText").html("Keep Going A Bit More !");
                    else if(words>=150 && words <200)
                        $("#wordCountSubText").html("Almost There !");
                    else if(words>=200 && words<230)
                        $("#wordCountSubText").html("Seems Perfect !");
                    else if(words>=230 && words<250)
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
                <span class="text-center"><strong><?php echo $display;?></strong><br></span>
                <input type="hidden" id="status" value="<?php echo $stat;?>">
                <br><br>
            </div>
            <form method="post" action="essayResult.php">
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
        </div>
        <?php                        
            }else{
        ?>
        <h1 class="text-center"><?php echo $display;?></h1>
        <?php
            }                           
        }else{
            $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
            header("Location: $redirect");
        }     
        include("footer.php");
        ?>
    </div>
</body>
</html>
