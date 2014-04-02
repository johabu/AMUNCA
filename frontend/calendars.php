<?php
error_reporting(E_ALL);
include("mysql.php");
include("functions.php");
session_start();
include("autologout.php");

printheader("AMUNCA - userlist");
printmenu();

echo "<h2>List of AMUNCA calendars</h2>";
if(!isset($_SESSION['user_id'])) {
        //die("You are not logged in.<br />\nPlease log in first.\n");
        header("Location: index.php");
}
else {
	echo "[Here will be the list of calendars in this database shown]<br />";
}
printfooter();
?>
