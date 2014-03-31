<?php
error_reporting(E_ALL);
include("../mysql.php");
include("../functions.php");
session_start();
include("../autologout.php");

printheader("AMUNCA Administration");
echo "<h2>AMUNCA Administration panel</h2>\n";
// check if user has right to enter administration area
if(!isset($_SESSION['rights']) OR !in_array('admin', $_SESSION['rights']))
	die("You are not allowed to enter this page!<br />Please return to the <a href=\"index.php\">Main page</a>\n");

// create array
$page = array();
$page['user'] = "user/index.php";

// check if the requested site $_GET['page'] exists
if(isset($_GET['page']) AND isset($page[$_GET['page']]))
	include $page[$_GET['page']];
// Otherwise show menu
else
	echo "<a href=\"admin.php?page=user\">Manage AMUNCA users</a>\n";

echo "<p>Back to the <a href=\"index.php\">Main page</a></p>";
printfooter();
?>
