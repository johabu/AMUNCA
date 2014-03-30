<?php

error_reporting(E_ALL);
include("mysql.php");

session_start();
echo "<h1>AMUNCA login area -- Sign up</h1>\n";
echo "<form ".
	" name=\"Sign up\" ".
	" action=\"".$_SERVER['PHP_SELF']."\" ".
	" method=\"post\" ".
	" accept-charset=\"ISO-8859-1\">\n";
	echo "<span style=\"font-weight:bold;\" ".
	" title=\"min.4\nmax.32\na-z A-Z 0-9 _\">\n".
	"Username:\n".
	"</span>\n";
	echo "<input type=\"text\" name=\"user_name\" maxlength=\"32\">\n";
	echo "<br />\n";
	echo "<span style=\"font-weight:bold;\" ".
	" title=\"min.8\">\n".
	"Password:\n".
	"</span>\n";
	echo "<input type=\"password\" name=\"user_pswd\">\n";
	echo "<br />\n";
	echo "<span style=\"font-weight:bold;\" ".
	" title=\"min.6\">\n".
	"Repeat password:\n".
	"</span>\n";
	echo "<input type=\"password\" name=\"user_pswdcheck\">\n";
	echo "<br />\n";
	echo "<span style=\"font-weight:bold;\" ".
	" title=\"name@mail.com\">\n".
	"Email address:\n".
	"</span>\n";
	echo "<input type=\"text\" name=\"user_email\" maxlength=\"70\">\n";
	echo "<br />\n";
	echo "<span>\n".
	"Show your email to others:\n".
	"</span>\n";
	echo "<input type=\"radio\" name=\"show_email\" value=\"1\"> yes\n";
	echo "<input type=\"radio\" name=\"show_email\" value=\"0\" checked> no\n<br />";
	echo "<input type=\"submit\" name=\"submit\" value=\"Sign up\">\n";
	echo "<input type=\"reset\" value=\"Reset\">\n";
	echo "</form>\n";

if (isset($_POST['submit']) AND $_POST['submit']=='Sign up'){
	// array of errors
	$errors = array();
	// all form fields existing?
	if (!isset($_POST['user_name'],
		$_POST['user_pswd'],
		$_POST['user_pswdcheck'],
		$_POST['user_email'],
		$_POST['show_email']))
		// if not, first error
		$errors = "Please use a form from the registration area.";
	else {
		// checking all form fields if correctly filled out
		// all usernames and email addresses to check if unique
		$usernames = array();
		$emails = array();
		$sql = "SELECT
		user_name,
		user_email
		FROM
		users
		";
		$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		while ($row = mysql_fetch_assoc($result)){
			$usernames[] = $row['user_name'];
			$emails[] = $row['user_email'];
		}
		// is username entered?
		if (trim($_POST['user_name'])=='')
			$errors[]= "Please insert a username.";
		// username has to be at least 4 characters long
		elseif (strlen(trim($_POST['user_name'])) < 4)
			$errors[]= "Your username has to be at least 4 characters long.";
		// only alphanumeric characters!
		elseif (!preg_match('/^\w+$/', trim($_POST['user_name'])))
			$errors[]= "Please use only alphanumeric characters (Numbers, letters and underscore).";
		// username unique?
		elseif (in_array(trim($_POST['user_name']), $usernames))
			$errors[]= "This username is already taken.";
		// is email address entered? 
		if (trim($_POST['user_email'])=='')
			$errors[]= "Please insert an email address.";
		// is this a valid email address?
		elseif (!preg_match('§^[\w\.-]+@[\w\.-]+\.[\w]{2,4}$§', trim($_POST['user_email'])))
			$errors[]= "The entered email address isn't valid.";
		// is this email address already taken?
		elseif (in_array(trim($_POST['user_email']), $emails))
				$errors[]= "This email address is already taken.";
		// is a password entered?
		if (trim($_POST['user_pswd'])=='')
				$errors[]= "Please insert a password.";
		// password has to be at least 8 characters long
		elseif (strlen(trim($_POST['user_pswd'])) < 8)
				$errors[]= "Your password is too short. It has to be at least 8 characters long.";
		// password confirmation entered?
		if (trim($_POST['user_pswdcheck'])=='')
				$errors[]= "Please repeat your password.";
		// password identical with confirmation?
		elseif (trim($_POST['user_pswd']) != trim($_POST['user_pswdcheck']))
				$errors[]= "Your password and password confirmation did not match. Please enter again.";
	}
	// any errors occured?
	if(count($errors)){
		echo "Your account could not be created:<br />\n";
		foreach($errors as $error)
			echo $error."<br />\n";
	}
	else {
		// insert new user into database
		$sql = "INSERT INTO
			users
			(user_name,
			user_email,
			user_pswd,
			show_email,
			registration_date
			)
		VALUES
			('".mysql_real_escape_string(trim($_POST['user_name']))."',
			'".mysql_real_escape_string(trim($_POST['user_email']))."',
			'".md5(trim($_POST['user_pswd']))."',
			'".mysql_real_escape_string(trim($_POST['show_email']))."',
			CURDATE()
			)
		";
		mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		echo "Thank you!\n<br />".
		"Your account has been created successfully.\n<br />".
		"You can now log in with your data.\n<br />";
	}
}

?>
