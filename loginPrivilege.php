<?php
    require_once 'authentication.php';
    if(!isLoggedIn()){        
        $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/loginAccess.php';
        header("Location: $redirect");
        exit;
    }
?>