<?php
error_reporting(E_ALL);
// check if user has right to enter this area
if(!in_array('user_manage', $_SESSION['rights']))
	die("You are not allowed to enter this page!<br />Please return to the <a href=\"admin.php\">main administration panel</a>\n");

switch(isset($_GET['action'])?$_GET['action']:''){
	case 'edit':
		include 'user/edit.php';
		echo "Back to the <a href=\"index.php?page=user\">user administration panel</a>\n";
		break;
		
	case 'delete':
		include 'user/delete.php';
		echo "Back to the <a href=\"index.php?page=user\">user administration panel</a>\n";
		break;
		
	default:
		$actions = array('edit' => 'Edit User', 'delete' => 'Delete User');
                foreach($actions as $action => $name)
			echo "<a href=\"index.php?page=user&action=".$action."\">".$name."</a><br /><br />\n";
		break;
}

echo "<p>Back to the <a href=\"index.php\">main administration panel</a></p>";
?>
