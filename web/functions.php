<?php
function printheader($title) {
	echo "<!DOCTYPE html>\n".
	"<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\n".
	"<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n".
	"<title>AMUNCA calendar</title>\n".
	"</head>\n<body>\n".
	"<div id=\"main\">\n";
	echo "<div id=\"topmenu\">\n".
	"<span id=\"calendarimg\">\n".
	"<a href=\"index.php\">\n".
	"<img height=\"40\" alt=\"A\" src=\"img/calendar_A.svg\" /> ".
	"<img height=\"40\" alt=\"M\" src=\"img/calendar_M.svg\" /> ".
	"<img height=\"40\" alt=\"U\" src=\"img/calendar_U.svg\" /> ".
	"<img height=\"40\" alt=\"N\" src=\"img/calendar_N.svg\" /> ".
	"<img height=\"40\" alt=\"C\" src=\"img/calendar_C.svg\" /> ".
	"<img height=\"40\" alt=\"A\" src=\"img/calendar_A.svg\" /> ".
	"</a>\n".
	"</span>\n";
	if(!isset($_SESSION['user_id'])) {
		echo "<form id=\"login\" ".
		" name=\"Login\" ".
		" action=\"login.php\" ".
		" method=\"post\" ".
		" accept-charset=\"ISO-8859-1\"><div>\n";
		echo "Username: ";
		echo "<input class=\"button\" style=\"height: 10px;\" size=\"10\" type=\"text\" name=\"user_name\" maxlength=\"32\" />\n";
		echo "Password: ";
		echo "<input class=\"button\" style=\"height: 10px;\" size=\"10\" type=\"password\" name=\"user_pswd\" />\n";
		echo "<input type=\"hidden\" name=\"autologin\" value=\"1\" />\n";
		echo "<button type=\"submit\" name=\"submit\" value=\"Log in\" class=\"button\" style=\"margin-right: 10px;\" >Log in</button>\n".
		"<b>or</b> <a class=\"button signupbutton\" href=\"signup.php\" style=\"margin-left: 10px;\" >Sign up</a>\n";
		echo "</div></form>\n";
	} else {
		$sql = "SELECT
			user_name
			FROM
			users
			WHERE
			ID = '".mysql_real_escape_string($_SESSION['user_id'])."'
		";
		$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
		$row = mysql_fetch_assoc($result);
		echo "<span id=\"login\"><a title=\"my profile\" href=\"myprofile.php\">".$row['user_name']."</a> | <a href=\"logout.php\">Log out</a></span>\n";
	}
	echo "</div>\n";
}
function printmenu() {
	echo "<div id=\"menu\">\n";
	if(!isset($_SESSION['user_id'])) {
		echo "<br />&nbsp;<a href=\"index.php\">Main page</a><br />\n";
	} else {
		echo "<br />&nbsp;<a href=\"index.php\">Main page</a><br />\n";
		echo "<br />&nbsp;<a href=\"userlist.php\">Userlist</a><br />\n";
		echo "<br />&nbsp;<a href=\"callist.php\">Show calendars</a><br />\n";
		if(in_array('cal_create', $_SESSION['rights'])) {
			echo "<br />&nbsp;<a href=\"createcal.php\">Create calendars</a><br />\n";
		echo "<br />&nbsp;<a href=\"editcal.php\">Manage calendars</a><br />\n";
		}
		echo "<br />&nbsp;<a href=\"editevent.php\">Edit events</a><br />\n";
		if(in_array('admin', $_SESSION['rights'])) {
			echo "<br />&nbsp;<a href=\"admin\">Administration</a><br />\n";
		}
	}
	echo "</div><div id=\"content\">\n";
}
function printadminmenu() {
	echo "<div id=\"menu\">\n";
	if(!isset($_SESSION['user_id'])) {
		echo "<br />&nbsp;<a href=\"../index.php\">Main page</a><br />\n";
	} else {
		echo "<br />&nbsp;<a href=\"../index.php\">Main page</a><br />\n";
		echo "<br />&nbsp;<a href=\"../userlist.php\">Userlist</a><br />\n";
		echo "<br />&nbsp;<a href=\"../callist.php\">Show calendars</a><br />\n";
		if(in_array('cal_create', $_SESSION['rights'])) {
			echo "<br />&nbsp;<a href=\"../createcal.php\">Create calendars</a><br />\n";
		}
		echo "<br />&nbsp;<a href=\"../editcal.php\">Manage calendars</a><br />\n";
		echo "<br />&nbsp;<a href=\"editevent.php\">Edit events</a><br />\n";
		if(in_array('admin', $_SESSION['rights'])) {
			echo "<br />&nbsp;<a href=\"index.php\">Administration</a><br />\n";
		}
	}
	echo "</div><div id=\"content\">\n";
}

function printfooter() {
	echo "</div></div>\n</body>\n</html>";
}

// check length of words in string and correct them if necessary
function shorten($str, $max=30, $range=5) {
	// divide up into lines
	$lines = explode("\n", $str);
	foreach($lines as $key_line => $line){
		// divide up into words
		$words = explode(" ", $line);
		// check length of each word
		foreach($words as $key_word => $word){
			if (strlen($word) > $max)
				$words[$key_word] = substr($word,0,$max-3-$range)."...".substr($word,-$range);
		}
		// concatenate new line
		$lines[$key_line] = implode(" ", $words);
	}
	// concatenate new text
	$str = implode("\n", $lines);
	return $str;
}


// log a user out
function doLogout() {
	// by deleting cookie
	if(isset($_COOKIE['autologin']))
		setcookie("autologin", "", time()-60*60);
	// and session id
	if(isset($_COOKIE['autologin'])) {
		$sql = "UPDATE
			users
			SET
			session_id = NULL,
			autologin = NULL
			WHERE
			ID = '".$_SESSION['user_id']."'
		";
		mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	}
}

// returns rights of a user
function getRights() {
	$rights = array();
	// choose rights out of database
	if(isset($_SESSION['user_id'])){
		$sql = "SELECT
		authority
		FROM
		user_rights
		WHERE
		user_id = '".$_SESSION['user_id']."'
		";
	$result = mysql_query($sql) OR die ("<pre>\n".$sql."</pre>\n".mysql_error());
	$rights = array();
	// return them as an array
	while($row = mysql_fetch_assoc($result))
		$rights[] = $row['authority'];
	}
	return $rights;
}

// log in a user
function doLogin($ID, $autologin=false) {
	// storing session id in database
	$sql = "UPDATE
		users
		SET
		session_id = '".mysql_real_escape_string(session_id())."',
		autologin = NULL,
		last_action = '".mysql_real_escape_string(time())."',
		last_login = '".mysql_real_escape_string(time())."'
		WHERE
		ID = '".$ID."'
	";
	mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	// autologin active?
	if($autologin){
		// generate random code
		$part_one = substr(time()-rand(100, 100000),5,10);
		$part_two = substr(time()-rand(100, 100000),-5);
		$Login_ID = md5($part_one.$part_two);
		// store code in cookie for a maximum of 1 year
		setcookie("autologin", $Login_ID, time()+60*60*24*365);
		$sql = "UPDATE
			users
			SET
			autologin = '".$Login_ID."'
			WHERE
			ID = '".$ID."'
		";
	mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	}
	
	// store data of user in session
	$sql = "SELECT
		user_name
		FROM
		users
		WHERE
		ID = '".$ID."'
	";
	$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	
	$row = mysql_fetch_assoc($result);
	$_SESSION['user_id'] = $ID;
	$_SESSION['user_name'] = $row['user_name'];
	// store user rights in session
	$_SESSION['rights'] = getRights();
}
?>
