<?php
    require_once 'authentication.php';
    if(!isAdmin()){
        $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/noAccess.php';
        header("Location: $redirect");
        exit;
    }
?>