<?php
    error_reporting(E_ALL);
    include("mysql.php");
    include("functions.php");

    session_start();
    include("autologout.php");


    // log out user
    doLogout();
    // clear $_SESSION
    $_SESSION = array();
    // delete session
    session_destroy();
    echo "You logged out successfully.<br /><a href=\"index.php\">Main page</a>\n";
    header("Location: index.php");
?>
