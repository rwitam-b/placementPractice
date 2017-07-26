<?php
    require 'tryLogin.php';
    require_once 'DB.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>AOT TT - Essay Deletion</title>
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
    <?php
        error_reporting(0);
        include("header.php");
        if(isset($_SESSION["aotemail_username"]) and isset($_SESSION["aotemail_admin"])){
    ?>
    <script>
        $(window).ready(function(){
            $(".alert").hide();
        });
        function refreshFields(val){
            if(val){
                $.post("ajaxFormFields.php",{id: val, type: "2"},
                    function (response){
                        obj=JSON.parse(response);                        
                        $("textarea[name='question']").html(obj.question);                        
                    }
                );
            }
            else{                
                $("textarea[name='question']").html("");
            }
        }
        function again(){
            window.location="deleteEssay.php";
        }
        function deleteQuestion(){
            var id=$("#select_email").val();
            if(id){
                $.post("ajaxDelete.php",{id:id,type: "2"},
                    function(response){
                        $("#text").html(response);
                        $(".alert").show();
                        $("html, body").animate({ scrollTop: $(document).height()-$(window).height() }, 1000);
                        setTimeout(again,1500);
                    }
                );
            }
        }
    </script>
    <div class="container-fluid">
        <h2 align="center">Delete Email</h2><br>
        <div class="row form-group">
            <div class="col-md-1"></div>
            <div class="col-md-10 col-xs-12">
                <select class="form-control" id="select_email" onchange="refreshFields(this.value)">
                    <?php
                        echo "<option value=\"\">Select A Question</option>";
                        try{
                            $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                            if(mysqli_connect_errno())
                                throw new Exception("Could Not Connect To Database !");
                            $sql="SELECT * FROM essay_questions";
                            $result=mysqli_query($con,$sql);
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {                                
                                $ques=str_replace(PHP_EOL," ",$row["question"]);
                                if(strlen($ques)>150)
                                    $ques=substr($ques,0,150)."....";
                                echo "<option value=\"".$row["id"]."\">".$ques."</option>";
                            }
                            mysqli_free_result($result);
                            mysqli_close($con);
                        }
                        catch(Exception $e){
                            echo "<option value=''>".$e->getMessage()."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-1"></div>
        </div><br><br>        
        <div class="row form-group">
            <div class="col-md-2"></div>
            <div class="col-md-8 col-xs-12">
                <textarea disabled name="question" style="resize:none;" rows=10 placeholder="Question" class="form-control"></textarea>
            </div>
            <div class="col-md-2"></div>
        </div>        
        <div class="row form-group">
            <div class="col-md-5"></div>
            <div class="col-md-2 col-xs-12">
                <input class="btn btn-primary btn-block" type="submit" value="DELETE" onclick="deleteQuestion()">
            </div>
            <div class="col-md-5"></div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div align="center" class="col-md-8 col-xs-12 alert alert-success text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span id="text"></span></strong>
            </div>
            <div class="col-md-2"></div>
        </div>

    </div>
    <?php
        }else{
            $_SERVER['HTTP_REFERER']="test";
            include("noAccess.php");
        }
        include("footer.php");
    ?>
</body>
</html>
