<?php
    include 'include/lang.php';
	include 'include/config.php';
	include 'include/db.php';
	include 'include/usr.class.php';
	@session_start ();
	
	$loggedIn = (($usr = initUser()) != false) ? true : false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>myGreenTown</title>
		
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
		<meta name = "keywords" content = "ecology,eco,game,web,thinkquest,project,mygreentown,greentown,green,ecology,myGreenTown" />
		<meta name = "description" content = "myGreenTown - a web-based, ecological town building game" />
		
		<link rel = "shortcut icon" type = "image/x-icon"	href="favicon.ico" >
		<link rel = "stylesheet" 	type = "text/css" 		href = "style/main.css" />
		<link rel = "stylesheet" 	type = "text/css" 		href = "style/home.css" />
		<link rel = "stylesheet" 	type = "text/css" 		href = "style/doc.css" />
		<link rel = "stylesheet"    type = "text/css"       href = "style/ranking.css" />
		<link rel = "stylesheet" 	type = "text/css" 		href = 'http://fonts.googleapis.com/css?family=Ubuntu' >
		
		<script type = "text/javascript" src = "sha256.js"></script>
		<script type = "text/javascript" language = "javascript" src = "script/jQuery.js" > </script>
		<script type = "text/javascript" language = "javascript" src = "script/json2.js" > </script>	
		<script type = "text/javascript" language = "javascript" src = "script/lang.js" > </script>
		<script type = "text/javascript" language = "javascript" src = "script/lib.js" > </script>
		<script type = "text/javascript" language = "javascript" src = "script/home.js" > </script>
		<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>		
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-23914249-1']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
		<script type = "text/javascript">
			var user = null;
			<?php
			    $other_lang = $_SESSION['lang'] == 'en' ? 'ro' : 'en';
                $lang = $_SESSION['lang'];
			?>
			<?php echo "language = '{$_SESSION['lang']}';\n";?>
			<?php echo "other_language = '$other_lang';\n";?>
			$(document).ready (function ()
			{			    
				<?php
					if ($loggedIn) {
						echo "user = JSON.parse('".$usr->toJSON()."').user;\n";
						echo "menu_welcome ();\n";
					} else {
						echo "menu_login ();\n";
					}
					echo "loggedIn = ".($loggedIn ? 'true':'false').";\n";
				?>
			});
		</script>
	</head>
	
	<body>
		<div id = "toolbar">
			<span>
				<img src = 'img/menu_register.png'  alt = 'register.php' />
				<? echo _('Register'); ?>
			</span>
			<span>
				<img src = 'img/menu_ranking.png' 	alt = 'ranking.php' />
				<? echo _('Ranking'); ?>
			</span>
			<span>
				<img src = 'img/menu_docs.png' 		alt = 'doc.php' />
				<? echo _('Documentation'); ?>
			</span>
			<span>
				<img src = 'img/menu_info.png' 		alt = 'textile.php?page=tutorial.tex' />
				<? echo _('Tutorial'); ?>
			</span>
			<span>
				<img src = 'img/forum.png' 			alt = 'forum' />
				<? echo _('Forum'); ?>
			</span>
		    <span class = 'lang'>
				<img src = 'img/en.png'  alt = 'en' />
            </span>
		    <span class = 'lang'>
				<img src = 'img/ro.png'  alt = 'ro' />
            </span>
			<span id = "return">
				<img src = 'img/ret.png' 			alt = 'ret' />
				<? echo _('Return'); ?>
			</span>
		</div>
		
		<div id = "page_ctnt"> </div>
		
		<img src="img/title.png" id = "home_title">	
		
	    <div id = "gamePreview"></div>
		<div id = "menu">
			<div id = "menu_login"></div>
			<div id = "menu_tselect" ></div>
		</div>
		<div id ="ad">
		    <?php
		        if (DISPLAY_ADS) {
		            echo '<script type="text/javascript"><!--
                    google_ad_client = "ca-pub-7699632345237995";
                    /* banner_homepage */
                    google_ad_slot = "9898266293";
                    google_ad_width = 728;
                    google_ad_height = 90;
                    //-->
                    </script>
                    <script type="text/javascript"
                    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                    </script>';
                }
            ?>
		</div>
	</body>
</html>
