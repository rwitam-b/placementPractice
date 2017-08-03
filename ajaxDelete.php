<?php
    require 'sessionize.php';
    require_once 'DB.php';
    require 'adminPrivilege.php';
    
    if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["id"])){
        try{            
            $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $id=$_POST["id"];
            if(isset($_POST["type"])){
                if($_POST["type"]=="1"){
                    $sql="DELETE FROM email_questions WHERE id='$id'";
                }
                elseif($_POST["type"]=="2"){
                    $sql="DELETE FROM essay_questions WHERE id='$id'";
                }
                elseif($_POST["type"]=="3"){
                    $sql="DELETE FROM picture_questions WHERE filename='$id'";
                    $file="picQuestions/".$id;
                    unlink($file);
                }
                if(mysqli_query($con,$sql))
                    echo "Question Successfully Removed From Database !";
                else
                    echo "Could Not Remove Question Due To Some Technical Issue !<br>Please Try Again Later !";
                mysqli_close($con);
            }
        }
        catch(Exception $e){
            echo "Could Not Remove Question Due To Some Technical Issue !<br>Please Try Again Later !";
        }
    }
    else{
        echo "Not Available";
    }
?>