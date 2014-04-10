<?php
error_reporting(E_ALL);
include("mysql.php");
include("functions.php");
session_start();
include("autologout.php");

printheader("AMUNCA - userlist");
printmenu();

echo "<h2>List of AMUNCA users</h2>";
if(!isset($_SESSION['user_id'])) {
        echo "You are not logged in.<br />\nPlease <a href=\"login.php\">log in</a> first.\n";
        header("Location: index.php");
}
else {

echo "<table rules=\"groups\" width=\"50%\">\n";
echo "<thead><tr>\n";
echo "<td>";
echo "<b>Username</b>";
echo "</td>\n";
echo "<td>";
echo "<b>Date of registration</b>";
echo "</td>\n";
echo "<td>";
echo "<b>Last login</b>";
echo "</td>\n";
echo "<td>";
echo "<b>Status</b>";
echo "</td>\n";
echo "</tr></thead>\n<tbody>";

$sql = "SELECT
	ID,
	session_id,
	user_name,
	DATE_FORMAT(registration_date, '%d/%m/%Y') as regdate,
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
		$online = "<span style=\"color:green\">online</span>";
	else
		$online = "<span style=\"color:red\">offline</span>";
	echo "<tr>\n";
	echo "<td><br />";
	echo "<a href=\"profile.php?id=".$row['ID']."\">".$row['user_name']."</a>";
	echo "</td>\n";
	echo "<td><br />";
	echo $row['regdate']."";
	echo "</td>\n";
	echo "<td><br />";
	echo date('d/m/Y  H:i', $row['last_login'])."&nbsp;&nbsp;&nbsp;";
	echo "</td>\n";
	echo "<td><br />";
	echo $online;
	echo "</td>\n";
	echo "</tr>\n";
}
echo "</tbody></table>";
}
printfooter();
?>
