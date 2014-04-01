<?php
error_reporting(E_ALL);
include("mysql.php");
include("functions.php");
session_start();
include("autologout.php");

printheader("AMUNCA - create a calendar");
echo "<h2>Create a new AMUNCA calendar</h2>";
if(!isset($_SESSION['user_id'])) {
        //die("You are not logged in.<br />\nPlease log in first.\n");
        header("Location: index.php");
} elseif (!isset($_SESSION['rights']) OR !in_array('cal_create', $_SESSION['rights'])) {
	echo "You are not allowed to create new calendars. Please return to the <a href=\"index.php\">main page</a>";
} else {
	echo "<form ".
	" name=\"Create calendar\" ".
	" action=\"".$_SERVER['PHP_SELF']."\" ".
	" method=\"post\" ".
	" accept-charset=\"ISO-8859-1\">\n";
	echo "<span style=\"font-weight:bold;\"> ".
	"Calendar name: *<br />\n".
	"</span>\n";
	echo "<input type=\"text\" name=\"cal_name\" value=\"\" maxlength=\"100\">\n";
	echo "<br /><span style=\"font-size: small;\">The calendar name can consist of up to 100 characters</span><br /><br />\n";
	echo "<span style=\"font-weight:bold;\"> ".
	"Description:<br />\n".
	"</span>\n";
	echo "<input type=\"text\" size=\"40\" name=\"cal_desc\" value=\"\" maxlength=\"500\">\n";
        echo "<br /><span style=\"font-size: small;\">Enter a description for the new calendar</span><br /><br />\n";
	echo "<input type=\"submit\" name=\"submit\" value=\"Create calendar\">\n";
	echo "</form>\n";

	if (isset($_POST['submit']) AND $_POST['submit']=='Create calendar'){
		// array of errors
		$errors = array();
		// all form fields existing?
		if (!isset($_POST['cal_name'],$_POST['cal_desc'])) {
			// if not, first error
			$errors = "Please use a form from the calendar creation page.";
		} else {
		// checking all form fields if correctly filled out
		// all usernames and email addresses to check if unique
		$calnames = array();
		$sql = "SELECT
			cal_name
			FROM
			calendars
		";
		$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		while ($row = mysql_fetch_assoc($result)){
			$calnames[] = $row['cal_name'];
		}
		// is calendar name entered?
		if (trim($_POST['cal_name'])=='')
			$errors[]= "Please insert a calendar name.";
		// only alphanumeric characters!
		elseif (!preg_match('/^\w+$/', trim($_POST['cal_name'])))
			$errors[]= "Please use only alphanumeric characters (Numbers, letters and underscore).";
		// calendar name unique?
		elseif (in_array(trim($_POST['cal_name']), $calname))
			$errors[]= "This calendar does already exist. Please create a new one.";
		}	
		// any errors occured?
		if(count($errors)){
			echo "The calendar could not be created:<br />\n";
			foreach($errors as $error)
				echo "<span class=\"error\">".$error."</span><br />\n";
		} else {		
			$sql = "INSERT INTO
				user_rights
				(user_id,
				authority
				)
				VALUES
				('".$_SESSION['user_id']."',
				'".mysql_real_escape_string(trim($_POST['cal_name']))."'
				)
			";
			mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
			$sql = "INSERT INTO
				calendars
				(cal_name,
				cal_desc
				)
			VALUES
				('".mysql_real_escape_string(trim($_POST['cal_name']))."',
				'".mysql_real_escape_string(trim($_POST['cal_desc']))."'
				)
			";
			mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
			echo "Congratulations!\n<br />".
			"The new calendar has been created successfully.\n<br />";
		}
	}
}
echo "<br />Return to the <a href=\"index.php\">main page</a>\n";
printfooter();
?>
