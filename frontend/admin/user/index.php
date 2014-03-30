<?php
error_reporting(E_ALL);
// check if user has right to enter this area
if(!in_array('user_manage', $_SESSION['rights']))
	die("You are not allowed to enter this page!<br />Please return to the <a href=\"../index.php\">administration page</a>\n");

switch(isset($_GET['action'])?$_GET['action']:''){
	case 'edit':
		include 'user/edit.php';
		echo "Back to the <a href=\"index.php?page=user\">user administration page</a>\n";
		break;
		
	case 'delete':
		include 'user/delete.php';
		echo "Back to the <a href=\"index.php?page=user\">user administration page</a>\n";
		break;
		
	default:
		$actions = array('edit','delete');
		
		foreach($actions as $action)
			echo "<a href=\"index.php?page=user&action=".$action."\">".$action."</a><br />\n";
		break;
}

echo "<p>Back to the <a href=\"index.php\">administration page</a></p>";
?>
