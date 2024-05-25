<?php 
include 'db_conn.php';
session_start();

session_unset();

session_destroy();
if(isset($_COOKIE['remember_uname'])){
    setcookie('remember_uname','',time()-3600,'/');
}

header("Location: index.php");