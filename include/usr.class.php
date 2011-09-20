<?php
include 'quest.php'; 
include 'town.class.php';

/**
	Stores and uses the data of the user
*/	
class User {
	/**
		Create a new user object from the database
	*/
	function __construct ($db) 
	{
		$this->id 				= $db['id'];
		$this->name 			= $db['name'];
		$this->pass 			= $db['pass'];
			
		// Building town list
		$tID 					= explode (",", $db['townID']);
		$this->numTown			= count ($tID);
		$this->towns 			= array();
		$this->carPercentage 	= $db['car_percent'];
				
		for ($i = 0; $i < $this->numTown; $i++) {
			$this->towns[$tID[$i]] = new Town ((int)$tID[$i]);
			$this->score += $this->towns[$tID[$i]]->fact['score'];
		}
		
		mysql_query ("UPDATE `user` SET `score` = '{$this->score}' WHERE `id` = '{$this->id}';");
	}
	
	/**
		Get the user data in XML form
	*/
	function toXML ()
	{
		$str = "";
		$str .= "<user>";
		$str .= "<id>".$this->id."</id>";
		$str .= "<name>".$this->name."</name>";
		$str .= "<towns>";
		
		foreach ($this->towns as $t) {
			$str .= "<town id='". $t->id ."' name='". $t->name ."' />";
		}
		
		$str .= "</towns></user>";
		return $str;
	}
	
	/**
		Get the user data in JSON form
	*/
	function toJSON ()
	{
		$str = "{\"user\":{\"id\":{$this->id},\"name\":\"{$this->name}\",\"numTowns\":{$this->numTown},\"towns\":[";
		
		foreach ($this->towns as $t) {
			$str .= "{\"id\":{$t->id}, \"name\":\"{$t->name}\"},";
		}

		return $str."0]}}";
	}

	/**
		Check if the user has a specific town
		@param id	ID of the town to check
		@return 	True / False
	*/
	function hasTown ($id)
	{
		foreach ($this->towns as $t) {
			if ($t->id == $id) {
				return true;
			}
		}
		return false;
	}
	
	/**
		Return the car fill percentage
		@return 	The percentage from 0.0 to 100.0
	*/
	function getCarPercent ()
	{
		return $this->carPercentage / 100.0;
	}
};

/**
	Initialise the user from cookies or session
*/
function initUser ()
{
    if (!isset ($_SESSION)) {
        session_start ();
    }
	if (isset ($_COOKIE['userName']) && isset ($_COOKIE['userPass'])) {
		$usr_db = mysql_get ("SELECT * FROM user WHERE `name` = '{$_COOKIE['userName']}' AND `pass` = '{$_COOKIE['userPass']}'");
		$_SESSION['usr'] = new User ($usr_db);
		
		if ($usr_db && $_SESSION['usr']) {		
			return $_SESSION['usr'];
		} else {
			return false;
		}		
	} else if (isset ($_SESSION['usr'])) {
		$usr_db = mysql_get ("SELECT * FROM user WHERE `id` = '{$_SESSION['usr']}'");
		return new User ($usr_db);	
	} else {
		return false;
	}
}

function logout ()
{

	setCookie ("userName", "", time() - 3600000);
	setCookie ("userPass", "", time() - 3600000);
	session_destroy ();
}

function sendConfirmMail ($to, $confirmKey)
{
	global $domain;
	$subject  = 'EcoGame registration';
	
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: Ecogame <admin@$domain.com>\r\n";
	
	$message  = "Welcome to EcoGame<br />";
	$message .= 'You have received this email because you have signed up for an ecoGame account.<br />';
	$message .= 'Please confirm your modifications to your account by clicking on the link below:<br />';
	$message .= "http://$domain/eco/query.php?cmd=verifMail&confirmKey=$confirmKey&mail=$to";
	$message .= ".<br /><br />Best regards, <br />the EcoGame Team";
	
	mail($to, $subject, $message, $headers);
}
?>
