<?php
error_reporting(E_ALL);
include("mysql.php");
session_start();

echo "<form ".
" name=\"user_pswd\" ".
" action=\"".$_SERVER['PHP_SELF']."\" ".
" method=\"post\" ".
" accept-charset=\"ISO-8859-1\">\n";
echo "Username:\n";
echo "<input type=\"text\" name=\"user_name\" maxlength=\"32\">\n";
echo "<br />\n";
echo "<input type=\"submit\" name=\"submit\" value=\"Send\">\n";
echo "</form>\n";

if(isset($_POST['submit']) AND $_POST['submit']=='Send'){
	// check data
	$errors = array();
	if(!isset($_POST['user_name']))
		$errors[] = "Please use the right form";
	else{
		if(trim($_POST['user_name']) == "")
			$errors[] = "Please enter your username.";
		// search username
		$sql = "SELECT
			user_email
			FROM
			users
			WHERE
			user_name = '".mysql_real_escape_string(trim($_POST['user_name']))."'
		";
		$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		$row = mysql_fetch_assoc($result);
		if(!$row)
			$errors[] = "This username does not exist.\n";
	}
	if(count($errors)){
		echo "Your password could not be sent.<br />\n".
		"<br />\n";
		foreach($errors as $error)
			echo $error."<br />\n";
		echo "<br />\n";
	}
	else {
		// create new password
		$password = substr(md5(microtime()),0,8);
		$sql = "UPDATE
			users
			SET
			user_pswd = '".md5(trim($password))."'
			WHERE
			user_name = '".mysql_real_escape_string(trim($_POST['user_name']))."'
		";
		mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		
		// send email to user
		$username = trim($_POST['user_name']);
		$recipient = $row['user_email'];
		$titel = "AMUNCA access information";
		$mailbody = "Your access data to AMUNCA have been changed.\nThese are your new access information:\n".
		"username: ".$username."\n".
		"password: ".$password."\n\n".
		"Your old password has been deleted. Please log in soon in order to change your new password.";
		$header = "From: info@amunca.de\n";
		if(@mail($recipient, $titel, $mailbody, $header)){
			echo "Your new password has been sent to you successfully.<br />\n";
		}
		//error while sending mail:
		else{
			echo "An error occured while sending an email with your new password. Please contact the system administrator.<br />\n";
		}
	}
}
?>
