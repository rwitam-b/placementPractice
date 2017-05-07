<?php
//     require 'tryLogin.php';
//     if (isset($_SESSION['aotemail_username']) and $_SERVER["REQUEST_METHOD"]=="POST" and isset($_POST["q_id"]) and isset($_POST["text"])){
        $file=getcwd().'/testEmail.py';
	echo $file;
        $command = escapeshellcmd('python -V 2>&1');
        $display = shell_exec($command);        
        echo $display;
//     }
//     else{
//         echo "Not Available";
//     }
?>
