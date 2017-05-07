<?php
    $url = getenv('JAWSDB_URL');
     $dbparts = parse_url($url);
     $hostname = $dbparts['host'];
     $username = $dbparts['user'];
     $password = $dbparts['pass'];
     $database = ltrim($dbparts['path'],'/');
     define('DB_HOST', $hostname);
     define('DB_USER', $username);
     define('DB_PASSWORD', $password);
     define('DB_NAME', $database);
?>
