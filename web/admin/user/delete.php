<?php
error_reporting(E_ALL);

if(isset($_POST['ID']) AND $_POST['ID'] != 0) {
	if(isset($_POST['submit']) AND $_POST['submit'] == 'Delete user') {
		// rights l�schen
		$sql = "DELETE FROM
			user_rights
			WHERE
			user_id = '".$_POST['ID']."'
		";
		mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		// Delete user
		$sql = "DELETE FROM
			users
			WHERE
			ID = '".$_POST['ID']."'
		";
		mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		
		echo "User was deleted.<br />\n";
	}
	elseif(isset($_POST['submit']) AND $_POST['submit'] == 'Choose user') {
		echo "Are you sure you want to delete this user?<br />\n";
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
			ID = '".mysql_real_escape_string($_POST['ID'])."'
		";
		$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		$row = mysql_fetch_assoc($result);
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "Username:\n";
		echo "</td>\n";
		echo "<td>\n";
		echo "<b>".htmlentities($row['user_name'], ENT_QUOTES)."</b> (";
		if($row['session_id'] AND (time()-60*2 < $row['last_action']))
			echo "<span style=\"color:green\">online</span>";
		else
			echo "<span style=\"color:red\">offline</span>";
		echo ")</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>";
		echo "Email address:";
		echo "</td>\n";
		echo "<td>";
		if($row['show_email']==1)
			echo htmlentities($row['user_email'], ENT_QUOTES);
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>";
		echo "Registration date :";
		echo "</td>\n";
		echo "<td>";
		echo $row['regdate']."";
		echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>";
		echo "Last login:";
		echo "</td>\n";
		echo "<td>";
		echo date('d.m.Y H:i \U\h\r', $row['last_login']);
		echo "</td>\n";
		echo "</tr>";
		echo "</table>\n";
		
		echo "<form ".
		"action=\"index.php?section=admin&page=user&action=delete\" ".
		"method=\"post\"><div>\n";
		echo "<input type=\"hidden\" name=\"ID\" value=\"".$_POST['ID']."\" />\n";
		echo "<input type=\"submit\" name=\"submit\" value=\"Delete user\" /><br /><br />\n";
		echo "</div></form>\n";
	}
}
else {
	$sql = "SELECT
		ID,
		user_name
		FROM
		users
		ORDER BY
		user_name ASC
	";
	$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	if(!mysql_num_rows($result))
		echo "There are no users stored in the database\n";
	else {
		echo "<form ".
		" action=\"index.php?page=user&action=delete\" ".
		" method=\"post\" ".
		" accept-charset=\"ISO-8859-1\">";
		echo "<select name=\"ID\">\n";
		echo " <option value=\"0\">Choose a user</option>\n";
		while($row = mysql_fetch_assoc($result)) {
			echo " <option value=\"".$row['ID']."\">\n";
			echo $row['user_name']."\n";
			echo " </option>\n";
		}
		echo "</select>\n";
		echo "<input type=\"submit\" name=\"submit\" value=\"Choose user\">";
		echo "</form>\n";
	}
}
?> 
