<?php
error_reporting(E_ALL);

if(isset($_POST['ID']) AND $_POST['ID'] != 0) {
	if(isset($_POST['submit']) AND $_POST['submit']=='Update data'){
		// create array for errors
		$errors = array();
		// check if all form fields exist
		if(!isset($_POST['user_email'],
			$_POST['show_email']))
			// if not, show error
			$errors[]= "Please use the form from the user administration page.";
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
		
		$sql = "SELECT
			user_email
			FROM
			users
			WHERE
			ID = '".mysql_real_escape_string($_POST['ID'])."'
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
			echo "<span class=\"error\">Data could not be changed.</span><br />\n".
			"<br />\n";
			foreach($errors as $error)
				echo "<span class=\"error\">".$error."</span><br />\n";
		}
		else{
			$sql = "UPDATE
				users
				SET
				user_email =  '".mysql_real_escape_string(trim($_POST['user_email']))."',
				show_email = '".mysql_real_escape_string(trim($_POST['show_email']))."'
				WHERE
				ID = '".mysql_real_escape_string($_POST['ID'])."'
			";
		mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		echo "Data updated successfully.<br />\n";
		}
	}
	// Change password
	elseif(isset($_POST['submit']) AND $_POST['submit'] == 'Change password') {
		$errors=array();
		if(!isset($_POST['user_pswd'],
			$_POST['user_pswdcheck']))
			$errors[]= "Please use the form from the user administration page.";
		else {
			if(trim($_POST['user_pswd'])=="")
				$errors[]= "Please enter new password.";
			elseif(strlen(trim($_POST['user_pswd'])) < 6)
				$errors[]= "The password is too short. It has to be at least 8 characters long.";
			if(trim($_POST['user_pswdcheck'])=="")
				$errors[]= "Please confirm the new password.";
			elseif(trim($_POST['user_pswd']) != trim($_POST['user_pswdcheck']))
				$errors[]= "Confirmation of new password failed.";
		}
		if(count($errors)){
			echo "<span class=\"error\">The new password could not be changed.</span><br />\n".
			"<br />\n";
			foreach($errors as $error)
				echo "<span class=\"error\">".$error."</span><br />\n";
		}
		else{
			$sql = "UPDATE
				users
				SET
				user_pswd ='".md5(trim($_POST['user_pswd']))."'
				WHERE
				ID = '".$_POST['ID']."'
			";
		mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		echo "New password successfully updated and stored.<br />\n";
		}
	}
	// Change rights
	elseif(isset($_POST['submit']) AND $_POST['submit'] == 'Change rights') {
		// Alle rights löschen
		$sql = "DELETE FROM
			user_rights
			WHERE
			user_id = '".$_POST['ID']."'
		";
		mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		// save chosen rights
		if(isset($_POST['rights'])){
			foreach($_POST['rights'] as $right){
				$sql = "INSERT INTO
					user_rights
					(user_id,
					authority
					)
					VALUES
					('".$_POST['ID']."',
					'".$right."'
					)
				";
			mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
			}
		}
		echo "Rights successfully applied to user.<br />\n";
	}
	else {
		$sql = "SELECT
			user_name,
			user_email,
			show_email
			FROM
			users
			WHERE
			ID = '".mysql_real_escape_string($_POST['ID'])."'
		";
		$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		$row = mysql_fetch_assoc($result);
		echo "<h3>Manage user data</h3>\n";
		echo "<form ".
		" name=\"Data\" ".
		" action=\"index.php?page=user&action=edit\" ".
		" method=\"post\" ".
		" accept-charset=\"ISO-8859-1\">\n";
		echo "<span style=\"font-weight:bold;\" >\nUsername:\n";
		echo htmlentities($row['user_name'], ENT_QUOTES)."\n";
		echo "</span><br /><br />\n";
		echo "<span style=\"font-weight:bold;\" ".
		" title=\"name@mail.com\">\n".
		"Email address:\n".
		"</span>\n";
		echo "<input type=\"text\" name=\"user_email\" maxlength=\"70\" value=\"".htmlentities($row['user_email'], ENT_QUOTES)."\">\n";
		echo "<br />\n";
		echo "<span>\n".
		"Show email address to other users:\n".
		"</span>\n";
		if($row['show_email']==1){
			echo "<input type=\"radio\" name=\"show_email\" value=\"1\" checked> yes\n";
			echo "<input type=\"radio\" name=\"show_email\" value=\"0\"> no\n";
		}
		else{
			echo "<input type=\"radio\" name=\"show_email\" value=\"1\"> yes\n";
			echo "<input type=\"radio\" name=\"show_email\" value=\"0\" checked> no\n";
		}
		echo "<br /><input type=\"submit\" name=\"submit\" value=\"Update data\">\n";
		echo "<input type=\"hidden\" name=\"ID\" value=\"".$_POST['ID']."\">\n";
		echo "</form><br /><br />\n";
		
		//set password
		echo "<h3>Set user password</h3>\n";
		echo "<form ".
		" name=\"user_pswd\" ".
		" action=\"index.php?page=user&action=edit\" ".
		" method=\"post\" ".
		" accept-charset=\"ISO-8859-1\">\n";
		echo "<span style=\"font-weight:bold;\" ".
		" title=\"min.8\">\n".
		"New password:\n".
		"</span>\n";
		echo "<input type=\"password\" name=\"user_pswd\">\n";
		echo "<br />\n";
		echo "<span style=\"font-weight:bold;\" ".
		" title=\"min.8\">\n".
		"Confirm new password:\n".
		"</span>\n";
		echo "<input type=\"password\" name=\"user_pswdcheck\">\n";
		echo "<br />\n";
		echo "<input type=\"submit\" name=\"submit\" value=\"Change password\">\n";
		echo "<input type=\"hidden\" name=\"ID\" value=\"".$_POST['ID']."\">\n";
		echo "</form><br /><br />\n";

		// rights
		echo "<h3>Manage user rights</h3>\n";
		echo "<form ".
		" name=\"rights\" ".
		" action=\"index.php?page=user&action=edit\" ".
		" method=\"post\" ".
		" accept-charset=\"ISO-8859-1\">\n";
		$sql = "SELECT
			authority
			FROM
			user_rights
			WHERE
			user_id = '".$_POST['ID']."'
		";
		$result_rights = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		$user_rights = array();
		while($row_rechte = mysql_fetch_assoc($result_rights))
			$user_rights[] = $row_rechte['authority'];
		
		$rights = array('admin','user_manage','cal_create');
		foreach($rights as $right){
			if(in_array($right, $user_rights))
				echo "<input type=\"checkbox\" name=\"rights[]\" value=\"".$right."\" checked>\n";
			else
				echo "<input type=\"checkbox\" name=\"rights[]\" value=\"".$right."\">\n";
			echo "<span>\n".
			$right."\n".
			"</span>\n";
			echo "<br />\n";
		}
		echo "<input type=\"submit\" name=\"submit\" value=\"Change rights\">\n";
		echo "<input type=\"hidden\" name=\"ID\" value=\"".$_POST['ID']."\">\n";
		echo "</form><br /><br />\n";
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
		" action=\"index.php?page=user&action=edit\" ".
		" method=\"post\" ".
		" accept-charset=\"ISO-8859-1\">";
		echo "<select name=\"ID\">\n";
		echo " <option value=\"0\">Please choose a user</option>\n";
		while($row = mysql_fetch_assoc($result)) {
			echo " <option value=\"".$row['ID']."\">\n";
			echo $row['user_name']."\n";
			echo " </option>\n";
		}
		echo "</select>\n";
		echo "<input type=\"submit\" name=\"submit\" value=\"Choose user\">";
		echo "</form><br /><br />\n";
	}
}
?>
