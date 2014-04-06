<?php
error_reporting(E_ALL);
include("mysql.php");
include("functions.php");
session_start();
include("autologout.php");

printheader("AMUNCA - manage your calendars");
printmenu();

echo "<h2>Manage your calendars</h2>";
if(!isset($_SESSION['user_id'])) {
        //die("You are not logged in.<br />\nPlease log in first.\n");
        header("Location: index.php");
}
else {
if(isset($_POST['ID']) AND $_POST['ID'] != 0) {
	if(isset($_POST['submit']) AND $_POST['submit']=='Update data'){
		// create array for errors
		$errors = array();
		if(!isset($_POST['cal_desc']))
			$errors[]= "Please use the form from the calendar managing page.";
		else{
			if(count($errors)){
				echo "<span class=\"error\">Data could not be changed.</span><br />\n".
				"<br />\n";
				foreach($errors as $error)
					echo "<span class=\"error\">".$error."</span><br />\n";
			}
			else{
				$sql = "UPDATE
					calendars
					SET
					cal_desc = '".mysql_real_escape_string(trim($_POST['cal_desc']))."'
					WHERE
					ID = '".mysql_real_escape_string($_POST['ID'])."'
				";
			mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
			echo "Calendar updated successfully.<br /><a href=\"index.php\">Main page</a>\n";
			}
		}
	}
	else {
		$sql = "SELECT
			cal_name,
			cal_desc
			FROM
			calendars
			WHERE
			ID = '".mysql_real_escape_string($_POST['ID'])."'
		";
		$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		$row = mysql_fetch_assoc($result);
		echo "<h3>Manage calendar data</h3>\n";
		echo "<form ".
		" name=\"Data\" ".
		" action=\"".$_SERVER['PHP_SELF']."\" ".
		" method=\"post\" ".
		" accept-charset=\"ISO-8859-1\">\n";
		echo "<span style=\"font-weight:bold;\" >\nCalendar name:</span>\n";
		echo htmlentities($row['cal_name'], ENT_QUOTES)."\n";
		echo "<br /><br />\n";
		echo "<span style=\"font-weight:bold;\" >\nCalendar description:</span><br />\n";
		echo "<textarea name=\"cal_desc\" cols=\"50\" rows=\"7\">".htmlentities($row['cal_desc'], ENT_QUOTES)."</textarea>\n";
		echo "<br /><br />\n";
		echo "<br /><input type=\"submit\" name=\"submit\" value=\"Update data\">\n";
		echo "<input type=\"hidden\" name=\"ID\" value=\"".$_POST['ID']."\">\n";
		echo "</form><br /><br />\n";
	}
} else {
	$sql = "SELECT
		ID,
		cal_name
		FROM
		calendars
		WHERE
		creator_id = '".mysql_real_escape_string($_SESSION['user_id'])."'
		ORDER BY
		cal_name ASC
	";
	$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	if(!mysql_num_rows($result))
		echo "There are no calendars stored in the database created by you.\n";
	else {
		echo "<form ".
		" action=\"".$_SERVER['PHP_SELF']."\" ".
		" method=\"post\" ".
		" accept-charset=\"ISO-8859-1\">";
		echo "<select name=\"ID\">\n";
		echo " <option value=\"0\">Please choose a calendar</option>\n";
		while($row = mysql_fetch_assoc($result)) {
			echo " <option value=\"".$row['ID']."\">\n";
			echo $row['cal_name']."\n";
			echo " </option>\n";
		}
		echo "</select>\n";
		echo "<input type=\"submit\" name=\"submit\" value=\"Choose calendar\">";
		echo "</form><br /><br />\n";
	}
}
}
printfooter();
?>
