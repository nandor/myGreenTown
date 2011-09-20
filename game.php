<?php
    include 'include/lang.php';
	include 'include/config.php';
	include 'include/usr.class.php';
	include 'include/db.php';
	
	if (!($usr = initUser()) ||
	    !array_key_exists ('id', $_GET)||
	    !$usr->hasTown ($_GET['id'])) {
		header ("Location: index.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>myGreenTown</title>
		
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />	
		
		<link rel = "shortcut icon" type = "image/x-icon" 	href = "favicon.ico" />		
		<link rel = "stylesheet" 	type = "text/css" 		href = "style/main.css"/>
		<link rel = "stylesheet" 	type = "text/css" 		href = "style/game.css"/>
		<link rel = "stylesheet" 	type = "text/css" 		href = "style/tech.css"/>
		<link rel = "stylesheet" 	type = "text/css" 		href = "http://fonts.googleapis.com/css?family=Ubuntu" />
		
		<script type = "text/javascript" src = "sha256.js"></script>
		<script type = "text/javascript" src = "script/jQuery.js" > </script>
		<script type = "text/javascript" src = "script/mousewheel.js" > </script>
		<script type = "text/javascript" src = "script/json2.js" > </script>		
		<script type = "text/javascript" src = "script/lib.js" > </script>
		<script type = "text/javascript" src = "script/game.js" > </script>
		
		<script type = "text/javascript">
		<?php
			echo "townID = {$_GET['id']};\nbuilding = JSON.parse ('{\"building\":[";
				foreach ($buildings as $b) {
					echo $b->toJSON ($usr->towns[$_GET['id']]->bldLvl[$b->id]);
				}
			echo "0]}').building;\nuser = JSON.parse ('".$usr->toJSON ()."');\n";	
			echo "percent = {$usr->getCarPercent ()};\n";
		?>
		
		$(document).ready (function ()
		{
			initGame ();
			initTooltips ();
		});
		</script>
	</head>
	
	<body>
	
		<div id = "game">	
		<div id = "dbg" style = "position: absolute; color: black; top: 100px; left: 100px;z-index:500000">
		
		</div>
			<div id = "gameMenu">
		        <div id = "ui_info_panel">
		        	<div id='ui_panel_back'></div><div id = 'ui_panel_content'></div>
		        </div>
				<span id = "gameStatus">
					<span class='gameStatusItem townName'></span>
					<span tooltip = "<?php echo _("Score "); ?>" ><img src='img/score.png'/><span class='score'></span></span>
					<span tooltip = "<?php echo _("Budget"); ?>"><img src='img/budget.png'/><span class='budget'></span></span>
					<span tooltip = "<?php echo _("Goods"); ?>"><img src='img/goods.png'/><span class='goods'> </span></span>
				</span>
			<div id = "ad">
			    <?php
			        if (DISPLAY_ADS) {
			            echo '<script type="text/javascript"><!--
                            google_ad_client = "ca-pub-7699632345237995";
                            /* banner_ingame */
                            google_ad_slot = "5948451868";
                            google_ad_width = 468;
                            google_ad_height = 60;
                            //-->
                            </script>
                            <script type="text/javascript"
                            src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script>';
                    }
                ?>
            </div>
			</div>	
			<div id = "gameControl">
				<img alt = 'logout'    tooltip = "<?php echo _("Log out"); ?>"             src = 'img/logout.png'      />
				<img alt = 'profile'   tooltip = "<?php echo _("Manage your account"); ?>" src = 'img/profile.png'     />
				<img alt = 'quest'     tooltip = "<?php echo _("View the Task Log"); ?>"   src = 'img/quest.png'       />
				<!--<img alt = 'doc'       tooltip = "<?php echo _("View documentation"); ?>"  src = 'img/menu_info.png'   />-->
				<img alt = 'tech'      tooltip = "<?php echo _("Research center"); ?>"     src = 'img/tech.png'        />
				<img alt = 'stat'      tooltip = "<?php echo _("View the statistics"); ?>" src = 'img/stat.png'        />
				<img alt = 'pol'       tooltip = "<?php echo _("View the pollution"); ?>"  src = 'img/pol.png'         />
				<img alt = 'mail'      tooltip = "<?php echo _("View the mail"); ?>"       src = 'img/mail.png'        />
				<img alt = 'compass'   tooltip = "<?php echo _("Rotate your town"); ?>"    src = 'img/compass0.png'    />
			</div>		
			<div id = "contextMenu">
				<img alt = "3" src = "img/tile/0_0_0.gif"/>
				<img alt = "1" src = "img/tile/1_0_0.gif"/>
				<img alt = "2" src = "img/dozer.png"/>
			</div>			
			<div id = "gamezoneClick"></div>
			<div id = "gamezone">
		        <img id = "gwait" src = "img/wait.gif" />
				<div id = "tileHolder"> </div>
			</div>
			<div id = "ui_body">
				<div id = 'ui_panel'>
					<div id = 'ui_minimap'>
						<div id = 'ui_minimap_body'>
							<img alt = "minimap" id = "ui_map" src = "img/wait.gif" />
							<img alt = "minimap_drag" id = "ui_minimap_drag" src = 'img/minimap_drag.png' />
						</div>
					</div>
				</div>
				<div id = 'ui_box'>
					<div id = "ui_box_cat">
					<?php				
					foreach ($bldCategory as $id => $name) {
						echo "<div><img class = 'ui_box_img' src = 'img/cat_$id.png' alt = '-$id'/><span>$name</span></div>";
					}		
					?>	
					</div>
					<div id = "ui_box_bld">
					<?php
						foreach ($buildings as $id => $bld) {
							$lvl = max($usr->towns[$_GET['id']]->bldLvl[$id], 0);
							echo "<div class = 'c_{$bld->type}'><img class = 'ui_box_img' src = 'img/tile/{$id}_{$lvl}_0.gif' alt = '{$id}' /><span>{$bld->name}</span></div>\n";
						}	
					?>
					</div>
					<div id = "ui_box_ret">
						<div>
							<img class = 'ui_box_img' src = 'img/ret.png' alt = '-100'/>
							<div class = 'ui_box_info'><?php echo _("Return"); ?></div>
						</div>
					</div>			
				</div>
				<div id = 'ui_building'>
				    <div style = "position:absolute"><img id = "ui_bImg" src = "img/tile/0_0_0.gif"/></div>
				    <div id = 'ui_bName'><?php echo _("Grassland"); ?></div>
				    <div id = 'ui_stats'>
					    <table>
						    <tr><th colspan = '2' id = 'ui_stat_title'><?php echo _("Stats"); ?></th></tr><tr>
							    <td><img src = 'img/pop.png' alt = "<?php echo _("Population"); ?>" /><span id = 'b_pop'></span></td>
							    <td><img src = 'img/energy.png' alt = "<?php echo _("Energy"); ?>" /><span id = 'b_energy'></span></td>
						    </tr><tr>
							    <td><img src = 'img/pol.png' alt = "<?php echo _("Pollution"); ?>" /><span id = 'b_pol'></span></td>
							    <td><img src = 'img/waste.png' alt = "<?php echo _("Waste"); ?>" /><span id = 'b_waste'></span></td>
						    </tr><tr>
							    <td><img src = 'img/income.png' alt = "<?php echo _("Income"); ?>" /><span id = 'b_income'></span></td>
							    <td><img src = 'img/prod.png'  alt = "<?php echo _("Production"); ?>"/><span id = 'b_prod'></span></td>
						    </tr><tr>
							    <td><img src = 'img/water.png' alt = "<?php echo _("Water"); ?>" /><span id = 'b_water'></span></td>
							    <td><img src = 'img/score.png' alt = "<?php echo _("Score "); ?>" /><span id = 'b_score'></span></td>
						    </tr>
					    </table>
				    </div>
				    <div id = 'ui_stats_more'>
					    <table>
						    <tr><th colspan = '2'><?php echo _("Requires"); ?></th></tr><tr>
							    <td><img src = 'img/budget.png' alt = "<?php echo _("Population"); ?>" /><span id = 'b_money'></span></td>
							    <td><img src = 'img/goods.png' alt = "<?php echo _("Energy"); ?>" /><span id = 'b_goods'></span></td>
						    </tr>
						    <tr>
						    	<td colspan = "2">
						    		<img src = 'img/time.png' alt = "<?php echo _("Build time"); ?>" /><span id = 'b_btime'></span>
						    	</td>
						    </tr>
					    </table>
				    </div>
		        </div>
			</div>
		</div>
	</body>
</html>
<?php
	//deinitialization here
	mysql_close ();
?>
