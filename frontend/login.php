<?php
error_reporting(E_ALL);
include("mysql.php");
include("functions.php");
// start session
session_start();
include("autologout.php");

echo "<form ".
" name=\"Login\" ".
" action=\"".$_SERVER['PHP_SELF']."\" ".
" method=\"post\" ".
" accept-charset=\"ISO-8859-1\">\n";
echo "Username:\n";
echo "<input type=\"text\" name=\"user_name\" maxlength=\"32\">\n";
echo "<br />\n";
echo "Password:\n";
echo "<input type=\"password\" name=\"user_pswd\">\n";
echo "<br />\n";
echo "Stay logged in ";
echo "<input type=\"checkbox\" name=\"autologin\" value=\"1\">\n";
echo "<br />\n";
echo "<input type=\"submit\" name=\"submit\" value=\"Log in\">\n";
echo "<br />\n";
echo "<a href=\"password.php\">Forgotten your password?</a>\n";
echo "</form>\n";

if(isset($_POST['submit']) AND $_POST['submit']=='Log in'){
	// if username and password match
	$sql = "SELECT
		ID
		FROM
		users
		WHERE
		user_name = '".mysql_real_escape_string(trim($_POST['user_name']))."' AND
		user_pswd = '".md5(trim($_POST['user_pswd']))."'
	";
	$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	// get id of user
	$row = mysql_fetch_assoc($result);
	if (mysql_num_rows($result)==1){
		doLogin($row['ID'], isset($_POST['autologin']));
		echo "<h3>Welcome ".$_SESSION['user_name']."</h3>\n";
		echo "You have successfully logged in.<br /><a href=\"index.php\">Main page</a>\n";
		header("Location: index.php");
	}
	else{
		echo "Username or password entered is not correct. Please retry.<br />\n";
	}
}
?>
