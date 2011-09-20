<?php
    /**
        @file login.php
        @author Licker Nandor
        @brief Login handling
    */
    
    include 'include/lang.php';
	include 'include/config.php';
	include 'include/usr.class.php';
	include 'include/db.php';

	@session_start ();

	if (!isset ($_POST['user']) || !isset ($_POST['pass']) || !isset($_POST['rem'])) {	  
		echo "0";
		exit ();
	}  
	
	$user = mysql_real_escape_string ($_POST['user']);
	$pass = mysql_real_escape_string ($_POST['pass']);
	$rem  = mysql_real_escape_string ($_POST['rem']);
	
	//Check if the user exists and the right password was given												
	$usr_db = mysql_get ("SELECT * FROM user WHERE `name` = '$user' AND `pass` = '$pass'");
												
	if ($usr_db == FALSE) {
		echo "1";
		exit ();
	}

	$_SESSION['usr'] = $usr_db['id'];
	$usr = new User ($usr_db);

    //Set the cookies if necessary										
	if ($rem == "true") {
		setCookie ("userName", $usr->name, time() + 3600000);
		setCookie ("userPass", $usr->pass, time() + 3600000);
	}
	
	echo $usr->toJSON ();
?>
