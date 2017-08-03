<?php
    require 'sessionize.php';
    require 'loginPrivilege.php';
    require_once 'DB.php';
    $referer="emailWriting.php";
    if(isset($_SERVER['HTTP_REFERER']) and strpos($_SERVER['HTTP_REFERER'], $referer)!== false and $_SERVER["REQUEST_METHOD"]=="POST"){
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Result</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="includes/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="includes/jquery.min.js"></script>
    <script src="includes/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- ATD Stuff    -->
    <script src="includes/atd/scripts/jquery.atd.textarea.js"></script>
    <script src="includes/atd/scripts/csshttprequest.js"></script>
    <link rel="stylesheet" href="includes/atd/css/atd.css" />
    <!-- PDF Make Stuff -->
    <script src="includes/pdfMake/pdfmake.min.js"></script>
 	<script src="includes/pdfMake/vfs_fonts.js"></script>
    <style>
        .myWell{
            border: 1px dashed #000000;
        }
        pre{
            border: 1px solid #888888;
        }
        .banner{
            max-width: 70vw;
        } 
    </style>
</head>
<body>
    <br>
    <div class="jumbotron">          
        <img src="images/banner.png" class="banner">
        <h1 align="center"><small>Email Writing Practice</small></h1>
    </div><br>
    <div class="container-fluid">
        <?php
        try{
            error_reporting(0);
            include("header.php");
            date_default_timezone_set("Asia/Kolkata");
            $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if(mysqli_connect_errno())
                throw new Exception();
            $q_id=$_POST["q_id"];
            $sql="SELECT * FROM email_questions WHERE id='$q_id'";
            $result=mysqli_query($con,$sql);
            $row = mysqli_fetch_assoc($result);
            $question=$row["question"];
            $text=htmlspecialchars(trim($_POST["text"]));                    
            $toProcess=str_replace(PHP_EOL,"<--nl-->",$text);            
            $words=$_POST["words"];
            $wordColor=(string)$_POST["wordColor"];
            if(intval($words)<50)
                $wordSubtext="The Email Is Too Short !";
            elseif(intval($words)>=50 and intval($words)<80)
                $wordSubtext="Looks Good !";
            else
                $wordSubtext="You Seem To Have Crossed The Average Word Limit !";
            
        }   
        catch(Exception $e){
            $display="Error Connecting To Database !<br><br>Please Try Again Later !";
            $stat="no";
        }        
        ?>
        <div id="report" class="container-fluid">
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
                <span id="questionHTML" class="text-center"><?php echo $question;?></span>
                <br><br>
            </div>
            <div class="row">
                <div class="well">
                    <h5 align="left"><img src="images/atdbuttontr.gif"><a href="javascript:check()" id="checkLink">Check Spelling</a></h5>
                    <h4 align="center"><strong>Email You Wrote</strong></h4>
                    <textarea id="writtenText" style="resize:none;" rows=10 class="form-control"><?php echo $text;?></textarea>
                </div>
            </div>
            <div id="check" class="container">
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4 col-xs-12">
                    <button class="btn btn-primary btn-block" onclick="save()">Download Result PDF</button>
                </div>
                <div class="col-sm-4"></div>
            </div>
            <script>
                (function($){
                    $.fn.innerText = function(msg) {
                        if (msg) {
                            if (document.body.innerText) {
                            for (var i in this) {
                                this[i].innerText = msg;
                            }
                            } else {
                            for (var i in this) {
                                this[i].innerHTML.replace(/&amp;lt;br&amp;gt;/gi,"n").replace(/(&amp;lt;([^&amp;gt;]+)&amp;gt;)/gi, "");
                            }
                            }
                            return this;
                        } else {
                            if (document.body.innerText) {
                            return this[0].innerText;
                            } else {
                            return this[0].innerHTML.replace(/&amp;lt;br&amp;gt;/gi,"n").replace(/(&amp;lt;([^&amp;gt;]+)&amp;gt;)/gi, "");
                            }
                        }
                    };
                })(jQuery);

                $.fn.ignore = function(sel){
                    return this.clone().find(sel||">*").remove().end();
                };

                function check(){
                    AtD.checkTextAreaCrossAJAX("writtenText", "checkLink", "Edit Text");
                }

                function save(){
                    var extractedQuestion=$("#questionHTML").text().substring(0,$("#questionHTML").text().indexOf("Outline"));
                    extractedQuestion+="\n\n"+$("#questionHTML").text().substring($("#questionHTML").text().indexOf("Outline"));                    
                    var dd = {
                        pageSize: "A4",
                        header: {
                            text: "<?php echo date("l jS F Y h:i:s A");?>",
                            style:
                            {
                                alignment: 'right'
                            }
                        },
                        content: [
                            "\n\n",    
                            { 
                                text: "AOT Talent Transformation", 
                                style: "header" 
                            },                        
                            "\n\n",
                            {
                                text: "Email Writing Practice",
                                style: "subheader"
                            },
                            "\n",
                            {
                                table: {                                    
                                    headerRows: 0,
                                    widths: [ "*", "*"],
                            
                                    body: [
                                        [ { text: "Name", bold: true }, "<?php echo $_SESSION['username'];?>", ],
                                        [ { text: "Word Count", bold: true }, "<?php echo $words;?>", ],
                                        [ { text: "Remark", bold: true }, "<?php echo $wordSubtext;?>", ]
                                    ]
                                }                            
                            },
                            "\n\n\n\n",
                            {
                                text: <?php echo json_encode($question);?>,
                                style: "subheader"
                            },
                            "\n\n",
                            { 
                                text: <?php echo json_encode($text);?>, 
                                style: "text"
                            },
                            "\n\n",
                            {
                                table: {
                                widths: [ '*'],
                                body: [
                                    [ { text: "SUBJECT", style: "subheader" } ],
                                    [ { text: $("#subject").ignore("h4").innerText().trim(), style: 'text' }]
                                ]
                                }
                            },
                            '\n\n',
                            {
                                table: {
                                widths: [ '*'],
                                body: [
                                    [ { text: 'RECEIVER', style: 'subheader' } ],
                                    [ { text: $("#receiver").ignore("h4").innerText().trim(), style: 'text' }]
                                ]
                                }
                            },
                            '\n\n',
                            {
                                table: {
                                widths: [ '*'],
                                body: [
                                    [ { text: 'SENDER', style: 'subheader' } ],
                                    [ { text: $("#sender").ignore("h4").innerText().trim(), style: 'text' }]
                                ]
                                }
                            },
                            '\n\n',
                            {
                                table: {
                                widths: [ '*'],
                                body: [
                                    [ { text: 'USAGE OF OUTLINE PHRASES', style: 'subheader' } ],
                                    [ { text: $("#usage_of_outline_phrases").ignore("h4").innerText().trim(), style: 'text' }]
                                ]
                                }
                            },
                            '\n\n',
                            {
                                table: {
                                widths: [ '*'],
                                body: [
                                    [ { text: 'ORDER OF OUTLINES PHRASES', style: 'subheader' } ],
                                    [ { text: $("#order_of_outline_phrases").ignore("h4").innerText().trim(), style: 'text' }]
                                ]
                                }
                            }
                        ],
                        styles: {
                            header: {
                                fontSize: 25,
                                bold: true,
                                alignment: 'center'
                            },
                            subheader: {
                                fontSize: 18,
                                bold: true,
                                alignment: 'center'
                            },
                            text: {
                                italics: true,
                                fontSize: 15
                            }
                        }
                    }
                    var name="essay_"+"<?php echo $_SESSION['username'];?>".toLowerCase().replace(/\s+/g,"_")+"@"+new Date().toLocaleDateString().replace(/[/]/g,"_");
                    pdfMake.createPdf(dd).download(name);                    
                }

                $(document).ready(function(){
                    $("#headLine").css("background-color","#222222");
                    $("#timer").css("color","red");
                    $("#wordCount").css("color","<?php echo $wordColor;?>");
                    $("html, body").animate({ scrollTop: 300 }, 2000);
                    $.post("ajaxResults.php",{"q_id": "<?php echo $q_id;?>", "text": "<?php echo $toProcess;?>"},
                        function (response){
                            try{
                                $("#check").html(response);
                            }
                            catch(err){
                                $("#check").html("<div class=\"row text-center alert alert-danger\"><strong>A Technical Glitch Occured !<br>Please Try Again Later !</strong></div>");
                            }
                        }
                    );                    
                });                
            </script>
        </div>        
    </div>
    <?php
        include("footer.php");
        }
        else{
            $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
            header('Refresh:0;url='.$redirect);
        }
    ?>
</body>
</html>
        
