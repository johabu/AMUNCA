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
"access through the web browser its users are not bound to specific platforms. Another ".
"feature of AMUNCA are multi-user calendars which allow users <br />to ".
"harmonize their arrangements easily.<br /><br />\n";
if(!isset($_SESSION['user_id'])) {
	
} else {
	if(in_array('cal_create', $_SESSION['rights'])) {
		echo "<br /><form style=\"position: absolute; width: 49%;\" action=\"createcal.php\">";
		echo "<button style=\"width: 100%;\" type=\"submit\" class=\"button\">".
		"<img src=\"img/calendar_add.svg\" alt=\"create\" /><br />Create a new calendar</button></form>\n";
		echo "<form style=\"position: relative; width: 49%; left: 50%;\" action=\"editcal.php\">";
		echo "<button style=\"width: 100%;\" type=\"submit\" class=\"button\">".
		"<img src=\"img/calendar_edit.svg\" alt=\"edit\" /><br />Manage your calendars</button></form>\n";
	} else {
		echo "<form style=\"position: relative; width: 99%;\" action=\"editcal.php\">";
		echo "<button style=\"width: 100%;\" type=\"submit\" class=\"button\">".
		"<img src=\"img/calendar_edit.svg\" alt=\"edit\" /><br />Manage your calendars</button></form>\n";
	}
	echo "<br /><form style=\"position: absolute; width: 49%;\" action=\"userlist.php\">";
	echo "<button style=\"width: 100%;\" type=\"submit\" class=\"button\">".
	"<img src=\"img/users.svg\" alt=\"userlist\" /><br />Look at the userlist to see which users are logged in</button></form>\n";
	echo "<form style=\"position: relative; width: 49%; left: 50%;\" action=\"callist.php\">";
	echo "<button style=\"width: 100%;\" type=\"submit\" class=\"button\">".
	"<img src=\"img/view-calendar.svg\" alt=\"callist\" /><br />Show calendars in this database</button></form>\n";
	if(in_array('admin', $_SESSION['rights'])) {
		echo "<br /><form style=\"position: relative; width: 99%;\" action=\"admin\">";
		echo "<button style=\"width: 100%;\" type=\"submit\" class=\"button\">".
		"<img src=\"img/configure.svg\" alt=\"admin\" /><br />You are allowed to enter the administration area</button></form>\n";
	}
}
printfooter();
?>
