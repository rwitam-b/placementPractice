<?php
    require 'tryLogin.php';
    if (isset($_SESSION['aotemail_username']) and $_SERVER["REQUEST_METHOD"]=="POST" and isset($_POST["q_id"]) and isset($_POST["text"])){
        $file=getcwd().'/testEmail.py';
        $command = escapeshellcmd('python '.$file.' '.$_POST["text"].' '.$_POST["q_id"]);
        $display = shell_exec($command);        
        echo $display;
    }
    else{
        echo "Not Available";
    }
?>