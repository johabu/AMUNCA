<?php
error_reporting(E_ALL);
include("mysql.php");
include("functions.php");
// start session
session_start();
include("autologout.php");

printheader("AMUNCA main page");
printmenu();
echo "<h1>Welcome to AMUNCA multi user network calendar application</h1>";
echo "This is the main page of AMUNCA.<br /> AMUNCA (\"AMUNCA Multi User Network Calendar Application\") is an attempt ".
"to create an easy to use calendar administration application.<br /> By ".
"access through the web browser it is platform independent. Another ".
"feature of AMUNCA are multi-user calendars which allow users <br />to ".
"harmonize their arrangements easily.<br /><br />";
if(!isset($_SESSION['user_id'])) {
	
} else {
	echo "<br />Look at the <a href=\"userlist.php\">userlist</a> to see which users are logged in.<br />\n";
	if(in_array('admin', $_SESSION['rights'])) {
		echo "<br />Since you have administrator rights, your are allowed to enter the <a href=\"admin/\">administration area</a>.<br />\n";
	}
	if(in_array('cal_create', $_SESSION['rights'])) {
		echo "<br /><a href=\"createcal.php\">Create</a> a new calendar or <a href=\"editcal.php\">manage</a> your calendars.<br />\n";
	}
	echo "<br /><a href=\"callist.php\">Show calendars</a> in this database.<br />\n";
}
printfooter();
?>
