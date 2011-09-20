<?php	
	/*
		File: query.php
		Purpose: process ajax requests
	*/
    include 'include/lang.php';
	include 'include/lib.php';
	include 'include/config.php';
	include 'include/usr.class.php';
	include 'include/db.php';
	
    if ($_GET['cmd'] == 'getTown') {
        if (!isset ($_GET['town'])) {
	        echo "0"; exit ();
	    }
	    $town = new Town ($_GET['town']);
	    echo $town->getSimpleMapData ();
        exit ();
	}
	if (isset ($_GET['cmd'])) {
		$cmd = $_GET['cmd'];
    }
    if (isset ($_POST['cmd'])) {
        $cmd = $_POST['cmd'];
    }
    
    if (!isset ($cmd)) {
        echo "0";
        exit ();
    }
    
	if (!($usr = initUser())) {
		echo "0"; die (0);
	}
	
	switch ($cmd) {
		case 'logout':
		{	
			logout ();
					
			echo "1";
			break;
		}
		case 'getmap':
		{
			if (!isset ($_GET['town']) || !$usr->hasTown ($_GET['town'])) {
			    echo "0"; exit ();
			}
			
			//initialize the town for this session
			$_SESSION['town'] = $_GET['town'];
			echo $usr->towns [$_GET['town']]->getMapDataJSON ();
			break;
		}	
		case 'abld':
		{
		    if (!isset ($_SESSION['town'])) {
		        echo "0"; exit ();
            }
            
			echo $usr->towns [$_SESSION['town']]->getAvailBld ();
			break;
		}
		case 'bld':
		{	
		    if (!isset ($_GET['x'], $_GET['y'], $_GET['id'], $_SESSION['town'])) {
		        echo _("Game error!"); exit ();
		    }
		    
			echo $usr->towns[$_SESSION['town']]->build ($_GET['x'], $_GET['y'], intval ($_GET['id']));
			break;
		}
		case 'renametown':
		{
		    if (!isset ($_GET['new'])) {
		        echo _("Game error!"); exit ();
		    }
		    
		    if (!valid_name ($_GET['new'])) {
		        echo _("Invalid name!"); exit ();
		    }
		
			echo $usr->towns[$_SESSION['town']]->rename (mysql_real_escape_string ($_GET['new']));
					
			break;
		}
		case 'getachiev':
		{
			echo $usr->getAchiev ();
			break;
		}
		case 'getquest':
		{
		    if (!isset ($_GET['id'])) {
		        echo _("Error!"); exit ();
            }
			
			echo $quests[$_GET['id']]->toHTML ($usr->towns[$_SESSION['town']]);
            break;
		}
		case 'getDesc':
		{
			echo $techs[$_GET['i']]->desc;
			break;
		}
		case 'research':
		{
			if (!isset ($_GET['i'])) {
		        echo _("Game error!");
				break;
            }
			$usr->towns[$_SESSION['town']]->techRes[$_GET['i']] = 1;
			if($usr->towns[$_SESSION['town']]->fact['budget'] < $techs[$_GET['i']]->cost) {
				echo _("You don't have enough money");exit ();
			}
			$usr->towns[$_SESSION['town']]->fact['budget'] -= $techs[$_GET['i']]->cost;
			$usr->towns[$_SESSION['town']]->update ();
			
			echo "{\"building\":[";
			foreach ($buildings as $b) {
				echo $b->toJSON ($usr->towns[$_SESSION['town']]->bldLvl[$b->id]);
			}
			echo "0]}";
			break;
		}
		case 'setCarPerc':
		{
			if(!isset ($_GET['perc'])) {
				echo '0';
				break;
			}
			$percent = $_GET['perc'];
			mysql_query ("UPDATE `user` SET `car_percent` = $percent WHERE `id` = " . ($usr->id) . ";");
			echo '1';
			break;
		}
		case 'update':
		{
			echo $usr->towns [$_SESSION['town']]->getMapDataJSON ();
			break;
		}
        case 'sendmail':
        {
            if (!isset ($_POST['to']) || !isset ($_POST['title']) || !isset ($_POST['txt'])) {
                echo "Error";
                exit (0);
            }
            
            $_POST['to'] = mysql_real_escape_string ($_POST['to']);
            $_POST['txt'] = strip_tags(mysql_real_escape_string ($_POST['txt']));
            $_POST['title'] = mysql_real_escape_string ($_POST['title']);
            
            $from  = $usr->towns[$_SESSION['town']]->name;
            
            $rcpt = mysql_get ("SELECT id FROM town WHERE `name` = '{$_POST['to']}';");           
            
            if ($rcpt['id'] <= 0) {
                echo "Invalid recipient!";
                exit (0);
            }
            
            mysql_query ("INSERT INTO mail(`from`, `to`, `title`, `text`) VALUES('$from', '{$rcpt['id']}', '{$_POST['title']}', '{$_POST['txt']}');");
            
            echo "<span style = 'color:green'>"._("Message sent!")."</span>";
            
            break;
        }
		default:
		{
			echo "0";
			break;
		}
	}
			
	mysql_close ($dbconn);
?>
