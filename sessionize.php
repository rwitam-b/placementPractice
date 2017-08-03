<?php
    require_once 'authentication.php';

    if(session_status()==PHP_SESSION_NONE){
        session_start();
    }   

    if(!isLoggedIn()){
        logout();
    }else{
        if(!updateTimer()){
            sessionTimeout();
        }
    }
?>