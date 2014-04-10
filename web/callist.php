<?php
error_reporting(E_ALL);
include("mysql.php");
include("functions.php");
session_start();
include("autologout.php");

printheader("AMUNCA - calendars");
printmenu();

echo "<h2>List of AMUNCA calendars</h2>";
if(!isset($_SESSION['user_id'])) {
        //die("You are not logged in.<br />\nPlease log in first.\n");
        header("Location: index.php");
}
else {
	echo "<table rules=\"groups\" width=\"50%\">\n";
	echo "<thead><tr>\n";
	echo "<td>";
	echo "<b>Calendar name</b>";
	echo "</td>\n";
	echo "<td>";
	echo "<b>Calendar description</b>";
	echo "</td>\n";
	echo "<td>";
	echo "<b>Creation date</b>";
	echo "</td>\n";
	echo "</tr></thead>\n<tbody>";

	$sql = "SELECT
		cal_name,
		cal_desc,
		DATE_FORMAT(creation_date, '%d/%m/%Y') as creationdate
		FROM
		calendars
		ORDER BY
		cal_name ASC
	";
	$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());

	while ($row = mysql_fetch_assoc($result)) {
		echo "<tr>\n";
		echo "<td><br />";
		echo $row['cal_name'];
		echo "</td>\n";
		echo "<td><br />";
		echo $row['cal_desc'];
		echo "</td>\n";
		echo "<td><br />";
		echo $row['creationdate'];
		echo "</td>\n";
		echo "</tr>\n";
	}
	echo "</tbody></table>";
}
printfooter();
?>
