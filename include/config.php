<?php
	/*
		File: include/config.php
		Purpose: contains important values
	*/

	//Server configuration
	$cfg = array (  
	        "siteName"  	=> "myGreenTown",
			"domain"    	=> "www.mygreentown.info",
			"db_host" 	    => "mygreentown.info",
			"db_user"   	=> "mygreent_admin",
			"db_pass"   	=> "gr33np@ss",
			"db_name"   	=> "mygreent_main",
			"cache_expire" 	=> 54000);
			
	//Game configuration
	define ('DISPLAY_ADS', 1);
	define ('GAME_SPEED', 1000);	
	define ('MAXTOWN', 5);
	define ('MAXBLVL', 4);
	define ('BLVLINC', 0.1);
	define ('MAXSIZE', 50);
	define ('MAXNAMELENGTH', 20);
	define ('INVALID_CHAR',  "!@#$%^&*_+=-/><,.?~`");	
	define ('MINGREEN', 30);
	define ('TILECOST', 500);
?>
