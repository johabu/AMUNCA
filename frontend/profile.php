<?php
error_reporting(E_ALL);
include("mysql.php");
include("functions.php");
session_start();
include("autologout.php");

// check $_GET parameters
if(!isset($_GET['id'])) {
	echo "No user chosen.<br />\n".
	"Please use a link from the <a href=\"userlist.php\">userlist</a>\n";
}
else {
	// cast $_GET parameter as integer
	$_GET['id'] = (int)$_GET['id'];
	$sql = "SELECT
		session_id,
		user_name,
		user_email,
		show_email,
		DATE_FORMAT(registration_date, '%d.%m.%Y') as regdate,
		last_action,
		last_login
		FROM
		users
		WHERE
		ID = '".mysql_real_escape_string($_GET['id'])."'
	";
	$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	$row = mysql_fetch_assoc($result);
	if(!$row){
		echo "No valid user chosen.<br />\n".
		"Please use a link from the <a href=\"userlist.php\">userlist</a>\n";
	}
	else{
		echo "<table>\n";
		echo " <tr>\n";
		echo "  <td>\n";
		echo "user_name :\n";
		echo "  </td>\n";
		echo "  <td>\n";
		echo htmlentities($row['user_name'], ENT_QUOTES)."\n";
		echo " (";
		if($row['session_id'] AND (time()-60*5 < $row['last_action']))
			echo "<span style=\"color:green\">online</span>\n";
		else
			echo "<span style=\"color:red\">offline</span>\n";
		echo ")";
		echo "  </td>\n";
		echo " </tr>\n";
		echo " <tr>\n";
		echo "  <td>\n";
		echo "Email address:\n";
		echo "  </td>\n";
		echo "  <td>\n";
		if($row['show_email']==1)
			echo htmlentities($row['user_email'], ENT_QUOTES)."\n";
		echo "  </td>\n";
		echo " </tr>\n";
		echo " <tr>\n";
		echo "  <td>\n";
		echo "Date of registration:\n";
		echo "  </td>\n";
		echo "  <td>\n";
		echo $row['regdate']."\n";
		echo "  </td>\n";
		echo " </tr>\n";
		echo " <tr>\n";
		echo "  <td>\n";
		echo "Last login:\n";
		echo "  </td>\n";
		echo "  <td>\n";
		echo date('d.m.Y H:i \U\h\r', $row['last_login'])."\n";
		echo "  </td>\n";
		echo " </tr>\n";
		echo "</table>\n";
	}
}
?>
