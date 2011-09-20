<?php
	include 'include/lang.php';
	include 'include/lib.php';
	include 'include/config.php';
	include 'include/usr.class.php';
	include 'include/db.php';
	@session_start();
	
	if (!($usr = initUser ())) {
		echo _("<h1>You don't have permission to access this page!</h1>");
		exit ();
	}
	
	$town = $usr->towns[$_SESSION['town']];
	
	if ($town->numBld [0x0F] <= 0) {
		echo _("<h1>You need a research center to research technologies!</h1>");
		exit ();
	}
?>
<div id = 'tech_title'><? echo _("Research Center"); ?></div>
<div id = 'tech_body'>
	<div id = 'tech_tree_holder'>
		<?php
			$numLeaf = 0;
			dfs (0x00, 100, 0, true);
	
			function dfs ($n, $width, $left, $enabled) {
				global $techs, $numLeaf, $town;
		
				echo "<div class = 'tech_hold' style = 'width:$width%;left:$left%'>";
				echo "<div style = 'background-color:".($enabled ? ($town->techRes[$n] ? 'blue' : 'green') : 'gray')."' class = 'tech_node'><span tech = '$n' teche = '".($enabled && !$town->techRes[$n])."'>{$techs[$n]->title}</span><div>{$techs[$n]->desc}<br /> Cost: {$techs[$n]->cost}<br /></div></div>";	
		
				$numChild = count ($techs[$n]->son);	
				$width = intval (100 /$numChild);
		
				if ($n != 0x00) {
					echo "<div class = 'tech_horz_up'></div>";
				}
				if ($numChild > 0) {
					echo "<div class = 'tech_horz_down'></div>";
				} else {
					$numLeaf ++;
				}
		
				echo "<div class = 'tech_vert' style = 'width:".(($numChild - 1) * $width)."%;left:".($width/2)."%'> </div><div class = 'tech_child' style = 'width:100%'>";		
				if ($techs[$n]->son) {	
					$i = 0;
					foreach ($techs[$n]->son as $s) {
						dfs ($s, $width, $width * ($i ++), $town->techRes[$n]);
					}
				}
				echo "</div></div>";
			}
		?>
	</div>
</div>
<div id = 'tech_msg'>

</div>
<script type = "text/javascript">	

function research (i) 
{
	$.getJSON ("query.php?cmd=research&i=" + i, function (json)
	{	
		$("#tech_msg").html ("<img src = 'img/wait.gif'  / >");
		building = json.building;
		
		for (var i = 0; i < sizeX; ++i) {
			for (var j = 0; j < sizeY; ++j) {
				var pt = normalize (i, j);
    			var idx = pt.i * rsY + pt.j;
				$("#x" + pt.i + "_" + pt.j + " .mapTileImg").attr ("src", "img/tile/" +  map[idx] + "_" + building[map[idx]].lvl + "_" + map[idx].tile + ".gif");
		    }
		}
		
		loadBuildingList ();
		
		$("#ui_panel_content").hide ().load ("tech.php").show ();
		
		$("#tech_msg").hide ();
	}).error (function (data)
	{
		$("#tech_msg").html (data.responseText + "<input style = 'right:0px' type = 'button' value = 'Close' onclick = '$(\"#tech_msg\").hide ()' />").css ("background-color", "red");
	});
}

$("#tech_tree_holder").css ("width", <? echo $numLeaf ?> * 300);	
$("#tech_msg").hide ();

$(".tech_node > span").click (function ()
{
	$("#tech_msg").html ($(this).next ().html ()).css ("background-color", "green");
	
	if ($(this).attr ("teche") == "1") {
		$("#tech_msg").append ("<input style = 'left:0px'  type = 'button' value = '<? echo _("Research");?>' onclick = 'research (" + $(this).attr ("tech") + ")' />");
	}
	
	$("#tech_msg").append ("<input style = 'right:0px' type = 'button' value = '<? echo _("Close");?>' onclick = '$(\"#tech_msg\").hide ()' />");
	
	$("#tech_msg").show ();
});

<?php
	foreach ($town->fact as $n => $f) {
		echo "fact['$n'] = {$f};";
	}
?>
</script>
