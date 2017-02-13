<?php
    require 'tryLogin.php';
    if (isset($_SESSION['aotemail_username']) and $_SERVER["REQUEST_METHOD"]=="POST" and isset($_POST["text"])){
        $file=getcwd().'/saveEmail.py';
        $command = escapeshellcmd('python '.$file.' '.$_POST["text"].' '.$_POST["question"]);
        $display = shell_exec($command);        
        echo $display;
    }
    else{
        echo "Not Available";
    }
?>