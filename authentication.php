<?php    

    function isLoggedIn(){  
        $out=false;      
        if(isset($_SESSION['username']) and isset($_SESSION['email'])){
            if(isset($_SESSION['fingerprint'])){
                $check=sha1($_SESSION['email'].$_SESSION['admin'].$_SERVER['HTTP_USER_AGENT']);
                if(strcmp($check,$_SESSION['fingerprint'])==0){
                    $out=true;
                }
            }
        }
        return $out;
    }

    function isAdmin(){
        $out=false;
        if(isLoggedIn() and isset($_SESSION['admin']) and strcmp($_SESSION['admin'],"true")==0){
            $out=true;
        }
        return $out;
    }

    function logout(){
        if(isset($_COOKIE[session_name()]))
            setcookie(session_name(), "", 1, "/");
        session_unset();
        session_destroy();
    }

    function updateTimer(){
        $out=false;
        if(isset($_SESSION['lastActivity'])){
            $lastTime=$_SESSION['lastActivity'];
            $presentTime=time();
            if(($presentTime-$lastTime) < 1800){
                $_SESSION['lastActivity']=$presentTime;
                $out=true;
            }
        }
        return $out;
    }   
        
    function sessionTimeout(){
        logout();
        $redirect='http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/timeout.php';
        header("Location: $redirect");
        exit;
    }

?>