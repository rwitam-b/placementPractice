<?php
    require 'tryLogin.php';
    require_once 'DB.php';
    if(isset($_SESSION["aotemail_username"]) and isset($_SESSION["aotemail_admin"]) and $_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["id"])){
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $id=$_POST["id"];
        if(isset($_POST["type"])){
            if($_POST["type"]=="1"){
                $sql="DELETE FROM email_questions WHERE id='$id'";
            }
            elseif($_POST["type"]=="2"){
                $sql="DELETE FROM essay_questions WHERE id='$id'";
            }
            if(mysqli_query($con,$sql))
                echo "Question Successfully Removed From Database !";
            else
                echo "Could Not Remove Question Due To Some Technical Issue !<br>Please Try Again Later !";
            mysqli_close($con);
        }
    }
    else{
        $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
        header('Refresh:0;url='.$redirect);
    }
?>