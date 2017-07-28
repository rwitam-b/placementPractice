<?php
    require 'tryLogin.php';
    require_once 'DB.php';
    if(isset($_SESSION["aotemail_username"]) and isset($_SESSION["aotemail_admin"]) and $_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["id"])){
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $id=$_POST["id"];
        if(isset($_POST["type"])){
            if($_POST["type"]=="1"){
                $sql="SELECT * FROM email_questions WHERE id='$id'";                
            }
            elseif($_POST["type"]=="2"){
                $sql="SELECT * FROM essay_questions WHERE id='$id'";                
            }            
            $result=mysqli_query($con,$sql);
            $row=mysqli_fetch_array($result, MYSQLI_ASSOC);
            echo json_encode($row);
            mysqli_free_result($result);
            mysqli_close($con);
        }
    }
    else{
        $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
        header('Refresh:0;url='.$redirect);
    }
?>