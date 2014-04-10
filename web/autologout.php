<?php
error_reporting(E_ALL);

// check if autologin is required
if(isset($_COOKIE['autologin']) AND !isset($_SESSION['user_id'])){
	$sql = "SELECT
	ID
	FROM
	users
	WHERE
	autologin = '".mysql_real_escape_string($_COOKIE['autologin'])."'
	";
	$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	$row = mysql_fetch_assoc($result);
	if(mysql_num_rows($result) == 1)
		doLogin($row['ID'], '1');
}

// refresh status "online"
if(isset($_SESSION['user_id'])){
	$sql = "UPDATE
		users
		SET
		last_action = '".time()."'
		WHERE
		ID = '".$_SESSION['user_id']."'
	";
	mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
}

// log user without autologin out
$sql = "UPDATE
	users
	SET
	session_id = NULL,
	autologin = NULL
	WHERE
	'".(time()-60*20)."' > last_action AND
	autologin IS NULL
";
mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());

// check if an user logged out holds a session and end it if necessary
if(isset($_SESSION['user_id'])){
		$sql = "SELECT
			session_id
			FROM
			users
			WHERE
			ID = '".$_SESSION['user_id']."'
		";
	$result = mysql_query($sql) OR die("<pre>\n".$sql."</pre>\n".mysql_error());
	$row = mysql_fetch_assoc($result);
	if(!$row['session_id']){
		$_SESSION = array();
		session_destroy();
	}
}
?>
