<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=UTF-8');

$thename='Login Loom Application'; 
$page = basename($_SERVER['SCRIPT_NAME']);
$website_auto_url =(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$ip_address=$_SERVER['REMOTE_ADDR']; //ip used
$sysname=gethostname();//computer used

// ////////////for local connect 
//$_HOST_NAME = "192.168.11.173";  
$_HOST_NAME = "localhost";  
$_DB_USERNAME="root";
$_DB_PASSWORD="";



///// for live connect
// $_HOST_NAME = 'localhost';  
// $_DB_USERNAME ='freefocu_alwaysonlineclasses';
// $_DB_PASSWORD ='ab@AfooTECH';

$conn = mysqli_connect($_HOST_NAME, $_DB_USERNAME, $_DB_PASSWORD)or die("Unable to connect to MySQL");
mysqli_select_db($conn,"login_loom_db");
/////////////////////////////////////////////////////
?>
