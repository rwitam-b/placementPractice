<?php
    require 'tryLogin.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Result</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            date_default_timezone_set("Asia/Kolkata");
            $referer="essayWriting.php";
            if (isset($_SESSION['aotemail_username'])){
                if(isset($_SERVER['HTTP_REFERER']) and strpos($_SERVER['HTTP_REFERER'], $referer)!== false and $_SERVER["REQUEST_METHOD"]=="POST"){
                    $text=htmlspecialchars(trim($_POST["text"]));
                    $question=$_POST["question"];                    
                    $words=$_POST["words"];
                    $wordColor=(string)$_POST["wordColor"];
                    if(intval($words)<50)
                        $wordSubtext="The Essay Is Too Short !";
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
                <span class="text-center"><strong><?php echo $question;?></strong><br></span>
                <br><br>
            </div>
            <div class="row">
                <div class="well">
                    <h5 align="left"><img src="images/atdbuttontr.gif"><a href="javascript:check()" id="checkLink">Check Grammar and Spelling</a></h5>
                    <h4 align="center"><strong>Essay You Wrote</strong></h4>
                    <textarea id="writtenText" style="resize:none;" rows=10 class="form-control"><?php echo $text;?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4 col-xs-12">
                    <button class="btn btn-primary btn-block" onclick="save()">Download</button>
                </div>
                <div class="col-sm-4"></div>
            </div>
            <div id="check" class="container">
            </div>
            <script>
                function check(){
                    AtD.checkTextAreaCrossAJAX("writtenText", "checkLink", "Edit Text");
                }
                function save(){
                    var dd = {
                        pageSize: 'A4',
                        header: {
                            text: "<?php echo date("l jS F Y h:i:s A");?>",
                            style:
                            {
                                alignment: 'right'
                            }
                        },
                        content: [
                            '\n\n',    
                            { 
                                text: 'AOT Talent Transformation', 
                                style: 'header' 
                            },                        
                            '\n\n',
                            {
                                text: 'Essay Writing Practice',
                                style: 'subheader'
                            },
                            '\n',
                            {
                                table: {
                                headerRows: 0,
                                widths: [ '*', '*'],
                        
                                body: [
                                    [ { text: 'Name', bold: true }, '<?php echo $_SESSION['aotemail_username'];?>', ],
                                    [ { text: 'Word Count', bold: true }, '<?php echo $words;?>', ],
                                    [ { text: 'Remark', bold: true }, '<?php echo $wordSubtext;?>', ]
                                ]
                            }
                            },
                            '\n\n\n\n',
                            {
                                text: '<?php echo $question;?>',
                                style: 'subheader'
                            },
                            '\n',
                            { 
                                text: '<?php echo $text;?>', 
                                style: 'text'
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
                    var name="essay_"+"<?php echo $_SESSION['aotemail_username'];?>".toLowerCase().replace(/\s+/g,"_")+"@"+new Date().toLocaleDateString().replace(/[/]/g,"_");
                    pdfMake.createPdf(dd).download(name);                    
                }
                $(document).ready(function(){
                    $("#headLine").css("background-color","#222222");
                    $("#timer").css("color","red");
                    $("#wordCount").css("color","<?php echo $wordColor;?>");
                    $("html, body").animate({ scrollTop: 300 }, 2000);                    
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