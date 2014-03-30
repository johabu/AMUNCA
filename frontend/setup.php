<?php
error_reporting(E_ALL);
include("mysql.php");
echo "<h1>AMUNCA login area -- Setup</h1>";

$sql = "DROP TABLE IF EXISTS users";
@mysql_query($sql);
$sql = 'CREATE TABLE `users` ('
	. ' `ID` INT AUTO_INCREMENT NOT NULL, '
	. ' `autologin` VARCHAR(32) NULL, '
	. ' `session_id` VARCHAR(32) NULL, '
	. ' `user_name` VARCHAR(30) NOT NULL, '
	. ' `user_pswd` VARCHAR(32) NOT NULL, '
	. ' `user_email` VARCHAR(70) NOT NULL, '
	. ' `show_email` BOOL NULL, '
	. ' `registration_date` DATE NULL, '
	. ' `last_login` INT NOT NULL DEFAULT \'0\', '
	. ' `last_action` INT NOT NULL DEFAULT \'0\','
	. ' PRIMARY KEY (`ID`),'
	. ' UNIQUE (`user_name`, `user_email`)'
	. ' )';
if(mysql_query($sql))
	echo "<p>Table 'users' successfully created</p>";
else {
	echo "<p>Table 'users' could not be created.</p>";
	echo "<h2>Query</h2>\n";
	echo "<pre>".$sql."</pre>\n";
	echo "<h2>ERROR</h2>";
	echo "<p>".mysql_error()."</p>";
	die();
}

$sql = "DROP TABLE IF EXISTS user_rights";
@mysql_query($sql);
$sql = 'CREATE TABLE `user_rights` ('
	. ' `ID` INT AUTO_INCREMENT NOT NULL, '
	. ' `user_id` INT NOT NULL, '
	. ' `authority` VARCHAR(100) NOT NULL, '
	. ' PRIMARY KEY (`ID`)'
	. ' )';

if(mysql_query($sql))
	echo "<p>Table 'user_rights' sucessfully created</p>";
else {
	echo "<p>Table 'user_rights' could no be created.</p>";
	echo "<h2>Query</h2>\n";
	echo "<pre>".$sql."</pre>\n";
	echo "<h2>ERROR</h2>";
	echo "<p>".mysql_error()."</p>";
	die();
}

$sql = "INSERT INTO
	users
	(user_name,
	user_email,
	show_email,
	user_pswd,
	registration_date
	)
VALUES
	('admin',
	'admin@email.com',
	'1',
	'".md5('admin')."',
	CURDATE()
	)
";
if(mysql_query($sql))
	echo "<p>User 'admin' successfully inserted<br /><b>Important:</b> user <b>admin</b> has password <b>admin</b>. Please login as
	admin first in order to change the password and the email address.</p>";
else {
	echo "<p>User 'admin' could not be inserted.</p>";
	echo "<h2>Query</h2>\n";
	echo "<pre>".$sql."</pre>\n";
	echo "<h2>ERROR</h2>";
	echo "<p>".mysql_error()."</p>";
	die();
}

$sql = "SELECT
	LAST_INSERT_ID()
";
$result = mysql_query($sql);
$ID = mysql_result($result,0);
$sql = "INSERT INTO
	user_rights
	(user_id,
	authority
	)
VALUES
	('".$ID."',
	'admin'
	)
";
if(mysql_query($sql))
	echo "<p>User 'admin' holds administration rights now</p>";
else {
	echo "<p>Adminstration rights could not be applied to user 'admin'</p>";
	echo "<h2>Query</h2>\n";
	echo "<pre>".$sql."</pre>\n";
	echo "<h2>ERROR</h2>";
	echo "<p>".mysql_error()."</p>";
	die();
}

$sql = "INSERT INTO
	user_rights
	(user_id,
	authority
	)
VALUES
	('".$ID."',
	'user_manage'
	)
";
if(mysql_query($sql))
	echo "<p>Right 'user_manage' successfully applied to user 'admin'</p>";
else {
	echo "<p>Right 'user_manage' could not be applied to user 'admin'</p>";
	echo "<h2>Query</h2>\n";
	echo "<pre>".$sql."</pre>\n";
	echo "<h2>ERROR</h2>";
	echo "<p>".mysql_error()."</p>";
	die();
}

echo "<h2>Setup finished successfully!</h2>";
echo "<p>Back to the <a href=\"index.php\">main page</a></p>";

?>