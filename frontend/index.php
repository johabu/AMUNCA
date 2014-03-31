<?php
error_reporting(E_ALL);
include("mysql.php");
include("functions.php");
// start session
session_start();
include("autologout.php");

printheader("AMUNCA main page");
echo "<h1>Welcome to AMUNCA multi user network calendar application</h1>";

printfooter();
?>
