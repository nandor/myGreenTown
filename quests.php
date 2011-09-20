<?php				
	include 'include/lang.php';
	include 'include/lib.php';
	include 'include/config.php';
	include 'include/db.php';
	include 'include/usr.class.php';
		
	if (!($usr = initUser())) {
		echo _("<h1>You cannot access this page!</h1>");
		exit ();
	}
	
	if (isset ($_POST['claim'])) {
		$usr->towns[$_SESSION['town']]->claimQuest ($_POST['claim']);
	} else if (isset ($_POST['complete'])) {
		$usr->towns[$_SESSION['town']]->finishQuest ($_POST['complete']);
	}
	
	$town = $usr->towns[$_SESSION['town']];
		
	$prog = array (); $avail = array (); $done = array (); $unavail = array ();
	
	foreach ($quests as $id => $q) {
		if ($town->questDone[$id]) {
			array_push ($done, $id);
		} else if ($town->questProg[$id]) {
			array_push ($prog, $id);
		} else if ($q->checkReq ($town)) {
			array_push ($avail, $id);
		} else {
			array_push ($unavail, $id);
		}
	}
?>
<div id = 'quest_body'>
	<div id = 'quest_sidebar'>
		<div class = 'quest_category'>
			<span class = 'quest_cat_title'><?php echo _("In progress");?></span>
			<div class = 'quest_container'>
				<?php
					foreach ($prog as $id) {
						echo "<div quest = '$id'>{$quests[$id]->title}</div>";
					}
				?>
			</div>
		</div>
		<div class = 'quest_category'>
			<span class = 'quest_cat_title'><?php echo _("Available");?></span>
			<div class = 'quest_container'>
				<?php
					foreach ($avail as $id) {
						echo "<div quest = '$id' class = 'q_avail'>{$quests[$id]->title}</div>";
					}
				?>
			</div>
		</div>
		<div class = 'quest_category'>
			<span class = 'quest_cat_title'><?php echo _("Unavailable");?></span>
			<div class = 'quest_container'>
				<?php
					foreach ($unavail as $id) {
						echo "<div quest = '$id'>{$quests[$id]->title}</div>";
					}
				?>
			</div>
		</div>
		<div class = 'quest_category'>
			<span class = 'quest_cat_title'><?php echo _("Completed");?></span>
			<div class = 'quest_container'>
				<?php
					foreach ($done as $id) {
						echo "<div quest = '$id'>{$quests[$id]->title}</div>";
					}
				?>
			</div>
		</div>
	</div>
	<div id = 'quest_titlebar'>
		<?php echo _("Task Log");?>
	</div>
	<div id = 'quest_content'>
		<div id = 'quest_msg'>
			<?php echo _("Welcome to the task log!");?>
		</div>
	</div>
</div>
<script type = 'text/javascript' language = 'javascript'>
	$(".quest_container"). hide ();
	
	$(".quest_cat_title").each (function () {
		$(this).append (" (" + $("div", $(this).siblings ()). size () + ")");
	});
	
	$(".quest_cat_title"). click (function ()
	{
		$(this).siblings (".quest_container").toggle ('normal');
	});
	
	$(".quest_container div").click (function ()
	{
		$("#quest_content"). html ("<img src = 'img/wait.gif'>");
		
		$.ajax({type: "GET",
			data: "cmd=getquest&id=" + $(this).attr ("quest"),
			url:  "query.php",
			cache: false,
			success: function(data)
			{
				$("#quest_content"). html (data).hide (). fadeIn ();	
				var cQuest = 0;

				$("#q_claim").click (function ()
				{
				    c_Quest = $(this).attr ("quest");
					$("#quest_body"). html ("<img src = 'img/wait.gif'>");
					$.ajax ({
                        type: "POST",
                        url: "quests.php",
                        data: "claim=" + c_Quest,
                        success: function (res)
                        {
						    $("#quest_body"). html (res).hide (). fadeIn ();
                        }
                    });
				});
				
				
				$("#q_finish").click (function ()
				{
					$("#quest_body"). html ("<img src = 'img/wait.gif'>");
					$.ajax ({
					    type: "POST",
					    url: "quests.php",
					    data: "complete=" + $(this).attr ("quest"),
					    success: function (res)
					    {
						    $("#quest_body"). html (res).hide (). fadeIn ();
					    }
				    });
				});
			}
		});
	});
	
	<?php
		foreach ($town->fact as $n => $f) {
			echo "fact['$n'] = {$f};";
		}
	?>
</script>

