<?php
error_reporting(E_ALL);
include("mysql.php");
include("functions.php");
session_start();
include("autologout.php");

printheader("AMUNCA - manage your calendars");
printmenu();

echo "<h2>Create, edit or remove events and appointments</h2>";
if(!isset($_SESSION['user_id'])) {
        //die("You are not logged in.<br />\nPlease log in first.\n");
        header("Location: index.php");
}
else {
if(isset($_POST['cal_name'])) {
	//create event:
	if(isset($_POST['submit']) AND $_POST['submit']=='Add event'){
		// create array for errors
		$errors = array();
		if(!isset($_POST['event_name']) OR trim($_POST['event_name'])=='') {
			$errors[]= "Please insert an event name.";
		} else {
			$eventnames = array();
			$sql = "SELECT
				event_name
				FROM
				".mysql_real_escape_string(trim($_POST['cal_name']))."
			";
			$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
			while ($row = mysql_fetch_assoc($result)){
				$eventnames[] = $row['event_name'];
			}
			// only alphanumeric characters!
			if (!preg_match('/^\w+$/', trim($_POST['event_name'])))
				$errors[]= "Please use only alphanumeric characters (Numbers, letters and underscore).";
			// event name unique?
			elseif (in_array(trim($_POST['event_name']), $eventnames))
				$errors[]= "This eventname does already exist. Please choose a different one.";
		}	
		if(count($errors)){
			echo "<span class=\"error\">Event could not be added.</span><br />\n".
			"<br />\n";
			foreach($errors as $error)
				echo "<span class=\"error\">".$error."</span><br />\n";
		}
		else{
			$sql = "INSERT INTO
				".mysql_real_escape_string(trim($_POST['cal_name']))."
				(event_name)
				VALUES
				('".mysql_real_escape_string($_POST['event_name'])."')
			";
			mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
			echo "Event added successfully.<br /><a href=\"index.php\">Main page</a>\n";
		}
	}
	//delete event
	elseif(isset($_POST['submit2']) AND $_POST['submit2']=='Delete event'){
		// create array for errors
		$errors = array();
		if(!isset($_POST['event_name']))
			$errors[]= "Please use the form from the event editing page.";
		else{
			if(count($errors)){
				echo "<span class=\"error\">Event could not be deleted.</span><br />\n".
				"<br />\n";
				foreach($errors as $error)
					echo "<span class=\"error\">".$error."</span><br />\n";
			}
			else{
				echo "<span class=\"error\"><b>This feature is not implemented yet!</b></span><br />\n";
			}
		}
	}
	else {
		echo "<form ".
		" name=\"Data\" ".
		" action=\"".$_SERVER['PHP_SELF']."\" ".
		" method=\"post\" ".
		" accept-charset=\"ISO-8859-1\"><div>\n";
		echo "<h3>Add new event to calendar \"".$_POST['cal_name']."\":</h3>\n";
		echo "Enter an name for the event: <br /><input type=\"text\" name=\"event_name\" /><br /><br />\n";
		echo "Enter an description for the event: <br />";
		echo "<textarea name=\"cal_desc\" cols=\"50\" rows=\"7\"></textarea><br /><br />\n";
		echo "<br /><br /><br />\n";
		echo "<button type=\"submit\" class=\"button\" name=\"submit\"".
		"value=\"Add event\"><img src=\"img/add.svg\" height=\"50px\"><br />Add event</button>\n";
		echo "<br /><br /><h3>Delete event</h3>\n";
		echo "<button type=\"submit\" class=\"button\" name=\"submit2\"".
		"value=\"Delete event\"><img src=\"img/warning.svg\" height=\"50px\"><br />Delete event</button>\n";
		echo "<input type=\"hidden\" name=\"cal_name\" value=\"".htmlentities($_POST['cal_name'], ENT_QUOTES)."\" />\n";
		echo "</div></form><br /><br />\n";
	}
} else {
	if(in_array('admin', $_SESSION['rights'])) {
		$sql = "SELECT
			ID,
			cal_name
			FROM
			calendars
			ORDER BY
			cal_name ASC
		";
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
	}
	$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	if(!mysql_num_rows($result))
		echo "There are no calendars stored in the database created by you.\n";
	else {
		echo "<form ".
		" action=\"".$_SERVER['PHP_SELF']."\" ".
		" method=\"post\" ".
		" accept-charset=\"ISO-8859-1\"><div>\n";
		echo "Choose a calendar to add events, edit them or remove them.<br />\n";
		echo "<select class=\"button\" name=\"cal_name\">\n";
		echo " <option value=\"0\">Please choose a calendar:</option>\n";
		while($row = mysql_fetch_assoc($result)) {
			echo " <option value=\"".$row['cal_name']."\">\n";
			echo $row['cal_name']."\n";
			echo " </option>\n";
		}
		echo "</select>\n";
		echo "<button class=\"button\" type=\"submit\" name=\"submit\" value=\"Choose calendar\">Choose calendar</button>";
		echo "</div></form><br /><br />\n";
	}
}
}
printfooter();
?>
