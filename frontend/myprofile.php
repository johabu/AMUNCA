<?php
error_reporting(E_ALL);
include("mysql.php");
include("functions.php");
session_start();
include("autologout.php");

printheader("AMUNCA - My profile");
printmenu();
echo "<h2>My Profile</h2>";
if(!isset($_SESSION['user_id'])) {
	//die("You are not logged in.<br />\nPlease log in first.\n");
	header("Location: index.php");
}
else {
	// change and update user data
	if(isset($_POST['submit']) AND $_POST['submit']=='Update data') {
		// array for errors
		$errors = array();
		// do all form fields exist?
		if(!isset($_POST['user_email'],
			$_POST['show_email']))
			// if not, print error
			$errors = "Please use the right form (see your profile).";
		else{
			$emails = array();
			$sql = "SELECT
				user_email
				FROM
				users
			";
			$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
			while($row = mysql_fetch_assoc($result))
				$emails[] = $row['user_email'];
			// current user email
			$sql = "SELECT
				user_email
				FROM
				users
				WHERE
				ID = '".mysql_real_escape_string($_SESSION['user_id'])."'
			";
			$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
			$row = mysql_fetch_assoc($result);
			if(trim($_POST['user_email'])=='')
				$errors[]= "Please enter an email address.";
			elseif(!preg_match('§^[\w\.-]+@[\w\.-]+\.[\w]{2,4}$§', trim($_POST['user_email'])))
				$errors[]= "The entered email address isn't valid.";
			elseif(in_array(trim($_POST['user_email']), $emails) AND trim($_POST['user_email'])!= $row['user_email'])
				$errors[]= "This email address is already taken.";
		}	
		if(count($errors)){
			echo "Your data could not be updated.<br />\n".
			"<br />\n";
			foreach($errors as $error)
				echo $error."<br />\n";
			echo "<br />\n";
		}
		else{
			$sql = "UPDATE
				users
				SET
				user_email =  '".mysql_real_escape_string(trim($_POST['user_email']))."',
				show_email = '".mysql_real_escape_string(trim($_POST['show_email']))."'
				WHERE
				ID = '".mysql_real_escape_string($_SESSION['user_id'])."'
			";
			mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
			echo "Your data has been updated successfully.<br />\n";
		}
	}
	// change password
	elseif(isset($_POST['submit']) AND $_POST['submit'] == 'Change password') {
		$errors=array();
		// old password
		$sql = "SELECT
			user_pswd
			FROM
			users
			WHERE
			ID = '".$_SESSION['user_id']."'
		";
		$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		$row = mysql_fetch_assoc($result);
		if(!isset($_POST['user_pswd'],
			$_POST['user_pswdcheck'],
			$_POST['old_user_pswd']))
			$errors[]= "Please use the right form (see your profile).";
		else {
			if(trim($_POST['user_pswd'])=="")
				$errors[]= "Please insert a new password.";
			elseif(strlen(trim($_POST['user_pswd'])) < 8)
				$errors[]= "Your password has to be at least 8 characters long.";
			if(trim($_POST['user_pswdcheck'])=="")
				$errors[]= "Pleas confirm your new password";
			elseif(trim($_POST['user_pswd']) != trim($_POST['user_pswdcheck']))
				$errors[]= "Your new password and the confirmation did not match.";
			// old password entered correctly?
			if(trim($row['user_pswd']) != md5(trim($_POST['old_user_pswd'])))
				$errors[]= "Your old password was not correct.";
		}
		if(count($errors)){
			echo "Your password could not be changed.<br />\n".
			"<br />\n";
			foreach($errors as $error)
				echo "<span class=\"error\">".$error."</span><br />\n";
			echo "<br />\n";
		}
		else{
			$sql = "UPDATE
				users
				SET
				user_pswd ='".md5(trim($_POST['user_pswd']))."'
				WHERE
				ID = '".$_SESSION['user_id']."'
			";
		mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		echo "Your password was updated successfully.<br />\n";
		}
	}
	else {
	$sql = "SELECT
		user_name,
		user_email,
		show_email
		FROM
		users
		WHERE
		ID = '".mysql_real_escape_string($_SESSION['user_id'])."'
	";
	$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	$row = mysql_fetch_assoc($result);
	echo "<form ".
	" name=\"user_data\" ".
	" action=\"".$_SERVER['PHP_SELF']."\" ".
	" method=\"post\" ".
	" accept-charset=\"ISO-8859-1\">\n";
	echo "<span style=\"font-weight: bold;\">\n".
	"Your username:\n".
	"</span>\n";
	echo htmlentities($row['user_name'], ENT_QUOTES)."\n";
	echo "<br />\n";
	echo "<span style=\"font-weight:bold;\" ".
	" title=\"name@mail.com\">\n".
	"Your email address:<br />\n".
	"</span>\n";
	echo "<input type=\"text\" name=\"user_email\" maxlength=\"70\" value=\"".htmlentities($row['user_email'], ENT_QUOTES)."\">\n";
	echo "<br />\n";
	echo "<span>\n".
	"Show your email address to others:\n".
	"</span>\n";
	if($row['show_email']==1){
		echo "<input type=\"radio\" name=\"show_email\" value=\"1\" checked> yes\n";
		echo "<input type=\"radio\" name=\"show_email\" value=\"0\"> no\n";
	}
	else{
		echo "<input type=\"radio\" name=\"show_email\" value=\"1\"> yes\n";
		echo "<input type=\"radio\" name=\"show_email\" value=\"0\" checked> no\n";
	}
	echo "<br />\n";
	echo "<input type=\"submit\" name=\"submit\" value=\"Update data\">\n";
	echo "</form><br /><br />\n";

	echo "<form ".
	" name=\"user_pswd\" ".
	" action=\"".$_SERVER['PHP_SELF']."\" ".
	" method=\"post\" ".
	" accept-charset=\"ISO-8859-1\">\n";
	echo "<span style=\"font-weight:bold;\" ".
	" title=\"at least 8 characters\">\n".
	"Old password:<br />\n".
	"</span>\n";
	echo "<input type=\"password\" name=\"old_user_pswd\">\n";
	echo "<br />\n";
	echo "<span style=\"font-weight:bold;\" ".
	" title=\"min.6\">\n".
	"New password:<br />\n".
	"</span>\n";
	echo "<input type=\"password\" name=\"user_pswd\">\n";
	echo "<br />\n";
	echo "<span style=\"font-weight:bold;\" ".
	" title=\"min.6\">\n".
	"Confirm new password:<br />\n".
	"</span>\n";
	echo "<input type=\"password\" name=\"user_pswdcheck\">\n";
	echo "<br />\n";
	echo "<input type=\"submit\" name=\"submit\" value=\"Change password\">\n";
	echo "</form>\n";
	}
}
printfooter();

?>
