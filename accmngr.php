<?php
	include 'include/lang.php';
	include 'include/config.php';
	include 'include/lib.php';
	include 'include/usr.class.php';
	include 'include/db.php';
	@session_start ();
	
	if (isset ($_POST['cmd'])) {
	    switch ($_POST['cmd']) {
	        case 'register':
	        {
	            if (!isset ($_POST['name'], $_POST['pass'])) {
	                echo __("Server error"); exit ();
	            }
	            
	            if ($_POST['captcha'] != $_SESSION['captcha']) {
	                echo __("Invalid verification text entered!"); exit ();
	            }
	            
			    $db = mysql_query ("SELECT id FROM user WHERE `name` = '{$_POST['name']}';");
			    if (mysql_num_rows($db) != 0) {
			        echo __("Username is already used!"); exit ();
			    }
			     
			    $name = trim ($_POST['name']); 
		
			    if (!valid_name ($name)) {
			        echo __("Invalid username!"); exit ();
			    }   
		
			    $name = mysql_real_escape_string ($name);
			    $pass = mysql_real_escape_string ($_POST['pass']);
						
	       		$achievDone   = "";
	       		for($i = 0; $i < 32; $i++)
	       			$achievDone   .= "00";
	       			
	       		mysql_query ("ALTER TABLE user AUTO_INCREMENT = 1;");
					       		
			    $townID = addTown ("{$_POST['name']}ville");
			
	       		mysql_query ("INSERT INTO user(`name`, `pass`, `townID`, `achiev`) VALUES ('$name', '$pass', '$townID', 0x$achievDone);");
	       		
		        echo "ok";			    
		        break;
	        }
		    case 'changeName':
		    {
		        if (!isset ($_POST['newName'])) {
		            echo __("Game error!"); exit ();
		        }
		        
		        if (!($usr = initUser ())) {
		            echo __("You cannot access this page!");
		        }
		        
			    $name = $usr->name;
			
			    $newName = trim ($_POST['newName']); 	
						
			    if (!valid_name ($newName)) {
			        echo __("Invalid username!"); exit ();
			    }   
			
			    if (mysql_num_rows(mysql_query ("SELECT * FROM user WHERE name = '$newName';")) > 0) {
			        echo __("Username is already used!"); exit ();
			    }
										
			    mysql_query ("UPDATE user SET `name`= '$newName' WHERE `name` = '$name';");
						
			    $usr->name = $newName;
			    
			    if (isset ($_COOKIE['userName'])) {
			        setCookie ("userName", $usr->name, time() + 3600000);
			    }
			    echo "ok";
			
			    break;
		    }
			case 'changePass':
			{
			    if (!isset ($_POST['newPass'], $_POST['pass'])) {
			        echo __("Game error!"); exit ();
			    }
			    
			    if (!($usr = initUser ())) {
		            echo __("You cannot access this page!");
		        }
		         
				$name = $usr->name;
				$pass = $usr->pass;
				
				$passOld = mysql_real_escape_string ($_POST['pass']);
				$passNew = mysql_real_Escape_string ($_POST['newPass']);
								
				if ($passOld != $pass) {
				    echo __("Invalid password!"); exit ();
				}
				
				mysql_query ("UPDATE user SET `pass`= '$passNew' WHERE `name`= '$name';");
			
				$usr->pass = $passNew;
				
				if (isset ($_COOKIE['userName'])) {
				    setCookie ("userName", $usr->name, time() + 3600000);
				    setCookie ("userPass", $usr->pass, time() + 3600000);
				}
				
				echo "ok";
				break;
			}
			case 'addtown':
			{
				if (!isset ($_POST['name'])) {
				    echo __("Game error!"); exit ();
				}
				
				$newName = mysql_real_escape_string ($_POST['name']);
				
				if (!valid_name ($newName, false)) {
			        echo __("Invalid name!"); exit ();
			    }  
			    
				if (!($usr = initUser ())) {
		            echo __("You cannot access this page!");
		        }
				
				if ($usr->numTown > MAXTOWN) {
				    echo __("You have too many towns!"); exit ();
				}
				
				$newTownID = addTown ($newName);
				
				mysql_query ("UPDATE user SET `townID` = CONCAT(`townID`, ',{$newTownID}') WHERE `id`= '{$usr->id}';");
				echo "ok";
				break;
			}
			case 'deleteTown':
			{
			    if (!isset ($_POST['town'])) {
			        echo __("Game error!"); exit ();
			    }
			    
			    if (!($usr = initUser ())) {
		            echo __("You cannot access this page!");
		        }					
				$id = intval ($_POST['town']);
				
				if (!$usr->hasTown ($id)) {
				    echo __("You don't own that town!"); exit ();
				}
				
				if ($usr->numTown == 1) {
					echo __("You can't delete your last town!"); exit ();
				}
				
				mysql_query("DELETE FROM `town` WHERE `id` = '$id';");
				
				$i = 0;
				$newArray = "";
				foreach($usr->towns as $town) {
					$currentID = $town->id;
					if($currentID != $id) {
						if($i != 0) {
							$newArray .= ',';
						} else {
							$i = 1;
						}
						$newArray .= $currentID;
					}
				}
				mysql_query("UPDATE `user` SET `townID`= '$newArray' WHERE `id`= '{$usr->id}';");
				echo "ok";
				break;
			}
			default:
			{
			    echo __("Invalid command!");
			    break;
			}
        }
	} else {
	    echo __("Server error");
	}	
	
	mysql_close ();
?>
