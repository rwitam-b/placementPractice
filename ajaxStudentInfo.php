<?php
    require 'tryLogin.php';
    require_once 'DB.php';
    if(isset($_SESSION["aotemail_username"]) and isset($_SESSION["aotemail_admin"]) and $_SERVER["REQUEST_METHOD"] == "POST"){
        try{
            $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if(mysqli_connect_errno())
                throw new Exception("Error Connecting To Database !");
            $condition="WHERE";
            $sql="";
            $year=$_POST["year"];
            if($year>=1 and $year<=4){
              $condition=$condition." year='$year'";
            }
            $stream=$_POST["stream"];
            if($stream=="CSE" or $stream=="EE" or $stream=="ECE" or $stream=="EIE" or $stream=="IT" or $stream=="ME"){
              $condition=$condition.(($condition!="WHERE")? " AND stream='$stream'":" stream='$stream'");
            }
            $test=$_POST["test"];
            if($test=="Email"){
              $sql="SELECT name,year,stream,roll,IFNULL(questions_email,0) AS 'email_attempts' FROM students";
            }
            else if($test=="Essay"){
              $sql="SELECT name,year,stream,roll,IFNULL(questions_essay,0) AS 'essay_attempts' FROM students";
            }
            else{
              $sql="SELECT name,year,stream,roll,IFNULL(questions_essay,0) AS 'essay_attempts',IFNULL(questions_email,0) AS 'email_attempts' FROM students";
            }
            if($condition!="WHERE"){
              $sql=$sql." ".$condition;
            }
            $result=mysqli_query($con,$sql);
            if(!$result)
                throw new Exception("Query Could Not Be Fetched At The Moment !");
            $arr = array();
            while($row=mysqli_fetch_assoc($result)){
                $arr[] = $row;
            }
            for($i=0;$i<count($arr);$i++){
              if(isset($arr[$i]["email_attempts"])){
                  $arr[$i]["email_attempts"]=$arr[$i]["email_attempts"]==0?0:count(explode(',',$arr[$i]["email_attempts"]));
              }
              if(isset($arr[$i]["essay_attempts"])){
                $arr[$i]["essay_attempts"]=$arr[$i]["essay_attempts"]==0?0:count(explode(',',$arr[$i]["essay_attempts"]));
              }
            }
            echo json_encode($arr);
            mysqli_close($con);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
        header('Refresh:0;url='.$redirect);
    }
?>
