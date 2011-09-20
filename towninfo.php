<?php
	include 'include/lang.php';
	include 'include/lib.php';
	include 'include/config.php';
	include 'include/db.php';
	include 'include/usr.class.php';
		
	if (!($usr = initUser())) {
		echo "<h1>You cannot access this page!</h1>"; exit ();
	}
	
	$town = $usr->towns[$_SESSION['town']];
	
	function makeBox ($type, $img) {
		echo '<div alt = "'.$type.'" class = "border">
		<div class = "ctl"></div><div class = "ctr"></div>
		<div class = "cbl"></div><div class = "cbr"></div>
		<div class = "top"></div><div class = "lft"></div>
		<div class = "rgt"></div><div class = "btm"></div>
		<div class = "back"><img src = "'.$img.'" /></div>
		<div class = "ctnt '.$type.'"></div>
		</div>';
	}
?>

<script type = "text/javascript">
    $("#stat_content").children ().hide ();
    $("#stat_score").show ();
    $("#sleft").hide ();
    
    var numTabs = $("#stat_sliding").children ().length;
    var ctab = 0;
    
    $("#stat_sliding > div").click (function () {
        $("#stat_content").children ().hide ();
        $("#stat_" + $(this).attr ("alt")).show ();
    });
    
    $("#sleft").click (function ()
	{
		if (ctab > 0) {
			ctab --;
			$("#stat_sliding").animate ({left: "+=120px"}, 'fast', function ()
			{
				if (ctab > 0) {$("#sleft").show ();} else {$("#sleft").hide ();}
				if (ctab < numTabs - 4) {$("#sright").show ();} else {$("#sright").hide ();}
			});
		}
	});
	
	$("#sright").click (function ()
	{
		if (ctab < numTabs - 1) {
			ctab ++;
			$("#stat_sliding").animate ({left: "-=120px"}, 'fast', function ()
			{
				if (ctab > 0) {$("#sleft").show ();} else {$("#sleft").hide ();}
				if (ctab < numTabs - 4) {$("#sright").show ();} else {$("#sright").hide ();}
			});
		}
	});
</script>
		
<div id = "stat_slider">
    <img id = "sleft" src = "img/tleft.png" />
    <div id = "stat_sliding">
    <?
	    makeBox ('score',    'img/score.png');
	    makeBox ('pol',      'img/pol.png');
	    makeBox ('green',    'img/green.png');
	    makeBox ('waste',    'img/waste.png');
	    makeBox ('water',    'img/water.png');
	    makeBox ('energy',   'img/energy.png');
	    makeBox ('popHouse', 'img/pop.png');
	    makeBox ('budget',   'img/budget.png');
	    makeBox ('goods',    'img/goods.png');
    ?>
    </div>
    <img id = "sright" src = "img/tright.png" />
</div>

<div class = "stat_hr" />

<div id = "stat_content">
	<div id = "stat_score">
	    <span class = 'title'><?php echo _("Score"); ?></span><hr />
	    <?php echo _("The score shows how good your town is. It is affected by the score given by the buildings and some other factors."); ?>
	    <table id = "stat_summary">
	        <tr>
	            <th><?php echo _("Base Score"); ?></th>
	            <td><? echo $town->scoreBase ?></td>
	        </tr>
	        <tr>
	            <th><?php echo _("Pollution"); ?></th>
	            <td><? echo $town->scorePol ?>%</td>
	        </tr>
	        <tr>
	            <th><?php echo _("Waste"); ?></th>
	            <td><? echo $town->scoreWaste ?></td>
	        </tr>
	        <tr>
	            <th><?php echo _("Green Areas"); ?></th>
	            <td><? echo $town->scoreGreen ?>%</td>
	        </tr>
	    </table>
	</div>
	<div id = "stat_pol">
	    <span class = 'title'><? echo _("Pollution"); ?></span><hr />
	    <?php echo _("This number indicates how polluted your town is. It's the average of the pollution of every building."); ?>
	</div>
	<div id = "stat_green">
	    <span class = 'title'><? echo _("Green Areas"); ?></span><hr />
	    <?php echo _("Show how many 'green' areas you have in your town. This percentage affects the score of your town."); ?>
	</div>
	<div id = "stat_waste">
	    <span class = 'title'><? echo _("Waste");?></span><hr />
	    <?php echo _("Indicates the ammount of trash produced by your town. This ammount is deducted from your score."); ?>
	    <table id = "stat_summary">
	        <tr>
	            <th><?php echo _("Trash produced"); ?></th>
	            <td><? echo $town->trashProd ?>t</td>
	        </tr>
	        <tr>
	            <th><?php echo _("Trash disposed"); ?></th>
	            <td><? echo $town->trashRecl ?>t</td>
	        </tr>
	    </table>
	</div>
	<div id = "stat_water">
	    <span class = 'title'><? echo _("Water"); ?></span><hr />
	    <?php echo _("Shows how much water is available."); ?>
    </div>
	<div id = "stat_energy">
	    <span class = 'title'><? echo _("Energy"); ?></span><hr />
	    <?php echo _("Shows how much energy is available. Energy is used by your buildings and you must ensure that you produced it without polluting your town."); ?>
    </div>
	<div id = "stat_popHouse">
	    <span class = 'title'><? echo _("Population"); ?></span><hr />
	    <?php echo _("Shows how many citizens do you have and how many of them have a place to work."); ?>
	     <table id = "stat_summary">
	        <tr>
	            <th><?php echo _("Inhabitants"); ?></th>
	            <td><span class = 'popHouse'></span></td>
	        </tr>
	        <tr>
	            <th><?php echo _("Workplaces"); ?></th>
	            <td><span class = 'popWork'></span></td>
	        </tr>
	        <tr>
	            <th><?php echo _("Unemployment"); ?></th>
	            <td><span class = 'unemploy'></span> %</td>
	        </tr>
	     </table>
    </div>
	<div id = "stat_budget">
	    <span class = 'title'><?php echo _("Budget"); ?></span><hr />
	    <?php echo _("The financial statistics of your town."); ?>
	     <table id = "stat_summary">
	        <tr>
	            <th><?php echo _("Budget"); ?></th>
	            <td><span class = 'budget'></span> ecos</td>
	        </tr>
	        <tr>
	            <th><?php echo _("Income"); ?></th>
	            <td><span class = 'income'></span> ecos/min</td>
	        </tr>
	     </table>
    </div>
	<div id = "stat_goods">
	    <span class = 'title'><?php echo _("Goods"); ?></span><hr />
	    <?php echo _("The industrial statistics of your town."); ?>
	     <table id = "stat_summary">
	        <tr>
	            <th><?php echo _("Goods"); ?></th>
	            <td><span class = 'goods'></span>t</td>
	        </tr>
	        <tr>
	            <th><?php echo _("Production"); ?></th>
	            <td><span class = 'prod'></span>t</td>
	        </tr>
	        <tr>
	            <th><?php echo _("Storage"); ?></th>
	            <td><span class = 'store'></span>t</td>
	        </tr>
	     </table>
    </div>
</div>
