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
	echo "<table width=\"50%\">\n";
	echo " <tr>\n";
	echo "  <td>";
	echo "Calendar name";
	echo "  </td>\n";
	echo "  <td>";
	echo "Calendar description";
	echo "  </td>\n";
	echo "  <td>";
	echo "Creation date";
	echo "  </td>\n";
	echo " </tr>\n";

	$sql = "SELECT
		cal_name,
		cal_desc,
		DATE_FORMAT(creation_date, '%d.%m.%Y') as creationdate
		FROM
		calendars
		ORDER BY
		cal_name ASC
	";
	$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());

	while ($row = mysql_fetch_assoc($result)) {
		echo " <tr>\n";
		echo "  <td>\n";
		echo $row['cal_name']."</a>\n";
		echo "  </td>\n";
		echo "  <td>\n";
		echo $row['cal_desc']."\n";
		echo "  </td>\n";
		echo "  <td>\n";
		echo $row['creationdate']."\n";
		echo "  </td>\n";
		echo " </tr>\n";
	}
	echo "</table>";
}
printfooter();
?>
