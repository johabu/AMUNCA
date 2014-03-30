<?php
error_reporting(E_ALL);
include("mysql.php");

session_start();

echo "<table>";
echo " <tr>\n";
echo "  <td>\n";
echo "Username\n";
echo "  </td>\n";
echo "  <td>\n";
echo "Date of registration\n";
echo "  </td>\n";
echo "  <td>\n";
echo "Last login\n";
echo "  </td>\n";
echo "  <td>\n";
echo " \n";
echo "  </td>\n";
echo " </tr>\n";

$sql = "SELECT
	ID,
	session_id,
	user_name,
	DATE_FORMAT(registration_date, '%d.%m.%Y') as regdate,
	last_login,
	last_action
	FROM
	users
	ORDER BY
	user_name ASC
";
$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());

while ($row = mysql_fetch_assoc($result)) {
	// if a session id exists and if the user was not inactive for more than 5 minutes
	// he is considered as being online
	if($row['session_id'] AND (time()-60*5 < $row['last_action']))
		$online = "<span style=\"color:green\">online</span>\n";
	else
		$online = "<span style=\"color:red\">offline</span>\n";
	echo " <tr>\n";
	echo "  <td>\n";
	echo "<a href=\"profile.php?id=".$row['ID']."\">".$row['user_name']."</a>\n";
	echo "  </td>\n";
	echo "  <td>\n";
	echo $row['regdate']."\n";
	echo "  </td>\n";
	echo "  <td>\n";
	echo date('d.m.Y H:i \U\h\r', $row['last_login'])."\n";
	echo "  </td>\n";
	echo "  <td>\n";
	echo $online;
	echo "  </td>\n";
	echo " </tr>\n";
}
echo "</table>";
?>
