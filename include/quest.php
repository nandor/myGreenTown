<?php

class Quest {
	function __construct ($id, $title, $desc, $fact = null, $build = null, $questReq = null, $reward = 0, $score = 0, $tech=null) {
		$this->id	    = $id;
		$this->title	= $title;
		$this->desc	    = $desc;
		
		$this->fact	    = $fact;
		$this->build	= $build;
		$this->reward	= $reward;
		$this->questReq = $questReq;
		$this->score	= $score;
		$this->tech     = $tech;
	}
	
	function checkReq ($town)
	{		
		global $factName;
		//Process factor requirements
		
		if ($this->fact) {
			$len = count ($this->fact);
				
			for ($i = 0; ($i < $len / 3); $i++) {
				if ($town->fact[$this->fact[$i * 3]] < $this->fact[$i * 3 + 1]) {
					return false;
				}
			}
		}
		//Process building requirements
		if ($this->build) {
			$len = count ($this->build);
					
			for ($i = 0; ($i < $len / 3); $i++) {
				if ($town->numBld[$this->build[$i * 3]] < $this->build[$i * 3 + 1]) {
					return false;
				}
			}
		}
		
		if ($this->questReq) {
			foreach ($this->questReq as $q) {
				if (!$town->questDone[$q]) {
					return false;
				}
			}
		}
				
		return true;
	}
	
	function checkObj ($town)
	{	
		global $factName;	
		//Process factor requirements
		
		if ($this->fact) {
			$len = count ($this->fact);
				
			for ($i = 0; ($i < $len / 3); $i++) {
				if ($town->fact[$this->fact[$i * 3]] < $this->fact[$i * 3 + 2]) {
					return false;
				}
			}
		}
		//Process building requirements
		if ($this->build) {
			$len = count ($this->build);
			
			if ($len % 3 == 0) {	
				for ($i = 0; ($i < $len / 3); $i++) {
					if ($this->build[$i * 3 + 2] > $town->numBld[$this->build[$i * 3]]) {
						return false;
					}
				}
			}
		}
		
		//Technologies
		if ($this->tech) {
		    foreach ($this->tech as $t) {
		        if (!$town->techRes[$t]) {
		            return false;
		        }
		    }
		}
		return true;
	}
	
	function toHTML ($town)
	{
		global $factName, $quests, $techs;
		echo   "<span id = 'q_title'>{$this->title}</span><div id = 'q_desc'>{$this->desc}</span><table id = 'q_table'><tr><th style = 'width:5%'>#</th><th>".__("Requirements")."</th><th>".__("Objectives")."</th></tr>";
		//factors first	
		if ($this->fact) {
			//Buildings
			$len = count ($this->fact);
		
			for ($i = 0; $i < $len / 3; $i++) {
				echo "<tr>
					<td><img src = 'img/{$this->fact[$i * 3]}.png' /></td>
					<td>";
				if ($this->fact[$i * 3 + 1]) {
					echo "{$this->fact[$i * 3 + 1]}";
				}
				echo " </td><td>";
				if ($this->fact[$i * 3 + 2]) {
					echo "{$this->fact[$i * 3 + 2]}";
				}
				echo "	</td>
				     </tr>";
			}
		}
		
		
		if ($this->build) {
			//Buildings
			$len = count ($this->build);
			global $buildings;
		
			for ($i = 0; $i < $len / 3; $i++) {
				echo "<tr>
					<td><img src = 'img/tile/{$this->build[$i * 3]}_0_0.gif' /></td>
					<td>";
				if ($this->build[$i * 3 + 1]) {
					echo "{$this->build[$i * 3 + 1]} {$buildings[$this->build[$i * 3]]->name}(s)";
				}
				echo " </td><td>";
				if ($this->build[$i * 3 + 2]) {
					echo "{$this->build[$i * 3 + 2]} {$buildings[$this->build[$i * 3]]->name}(s)";
				}
				echo "	</td>
				     </tr>";
			}
		}
		
		if ($this->tech) {
			foreach ($this->tech as $t) {
				echo "<tr><td><img src = 'img/tech.png' /></td><td></td><td>{$techs[$t]->title}</td></tr>";
			}
		}
		
		if ($this->questReq) {
			foreach ($this->questReq as $q) {
				echo "<tr><td><img src = 'img/quest.png' /></td><td colspan = '2'>{$quests[$q]->title}</td></tr>";
			}
		}	
					
		echo   "</table>
			<div id = 'q_reward'>Reward: {$this->reward} <img src = 'img/budget.png' />";
			
		if (!$town->questDone[$this->id] && !$town->questProg[$this->id] && $this->checkReq ($town)) {
			echo "<br /><input type = 'button' value = '".__("Claim")."' id = 'q_claim' quest = '{$this->id}'/>";
		}
		
		if ($town->questProg[$this->id] && $this->checkObj ($town)) {
			echo "<br /><input type = 'button' value = '".__("Complete")."' id = 'q_finish' quest = '{$this->id}'/>";
		}
		echo "</div>";
	}
	
	function addToNews ()
	{
	
	}
};

$quests = array ();
$achiev = array ();

$quests[0x00] = new Quest (0x00, __("Introduction"), __("<p>Welcome to your town! This is the first quest from the game and it will help you to get started</p><p>First of all, you should build some roads. They are vital to communication and each building has to have access to a road.</p><p>Build roads on 5 tiles. You can do this by right-clicking and selecting the road icon.</p><p>To claim this quest, click on the 'Claim' button</p>"), 
			   null, array (0x01, 0, 5), null, 1500, 5, null);
$quests[0x01] = new Quest (0x01, __("Charging the batteries"), __("<p>You've got some roads, but there isn't anyone to use them. Build a windmill to produce electricity, so you can build houses and factories later.</p>"),
			   null, array (0x04, 0, 1), array (0x00), 1500, 10, null);	
$quests[0x02] = new Quest (0x02, __("Filling the tanks"), __("<p>Besides energy, you need water. You should build a water tank to supply your town with water</p>"),
			   null, array (0x12, 0, 1), array (0x01), 1200, 10, null);	
$quests[0x03] = new Quest (0x03, __("Treating the water"), __("<p>You should build a water treatement station. Otherwise, nobody will move into your town because you don't have potable water.</p>"),
			   null, array(0x13, 0, 1), array(0x02), 1200, 10, null);
$quests[0x04] = new Quest (0x04, __("Moving in"), __("<p>Before you can start building your town, you need to set up the adnimistration of your town. You should build a Town Hall.</p><p>Build a Town Hall.</p>"),
			   null, array (0x0B, 0, 1), array (0x03), 1200, 10, null);				   
$quests[0x05] = new Quest (0x05, __("Healthcare"), __("<p>People won't move into your town unless you set up the right conditions for them.</p><p>Build a Hospital to help your town's inhabitants cure of diseases.</p>"),
			   null, array (0x0C, 0, 1), array (0x04), 1300, 15, null);
$quests[0x06] = new Quest (0x06, __("Catch'em all!"), __("<p>Neither the world we live in, nor your town aren't Heaven and people aren't angels. There are a lot of crimes.</p><p>Build a Police departement to reduce crimes.</p>"),
			   null, array (0x0D, 0, 1), array(0x05), 1330, 15, null);
$quests[0x07] = new Quest (0x07, __("My house is on fire"),__("<p>Now that you solved the crimes problem, there is one more thing to do before people will start moving in.</p><p>Accidents happen and you have to be prepared for them.</p><p>Build a fire departement to protect your builldings from flames!</p>"),
			   null, array(0x0E, 0, 1), array(0x06), 1330, 15, null);
$quests[0x08] = new Quest (0x08, __("Hello world!"),__("<p>Your town is ready! You can build houses for your residents!.</p><p>Build 3 houses. If you don't have enough space, try to purchase new lands to extend yout town.</p>"),
			   null, array (0x02, 0, 3), array (0x07), 1360, 15, null);
$quests[0x09] = new Quest (0x09, __("Touching the clouds"),__("<p>Flats are better than houses when we are talking abuot shelters. A flat can shelter a lot more people than a normal house.</p><p>Build 2 flats.</p>"),
			   null, array(0x03, 0, 2), array(0x08), 1360, 15, null); 
$quests[0x0A] = new Quest (0x0A, __("Education"),__("<p>Although 9 of 10 teenagers say that they don't like anything related to school, attending one is very important for education.</p><p>Build a school for kid's education.</p>"),
			   null, array(0x08, 0, 1), array(0x09), 1650, 15, null);
$quests[0x0B] = new Quest (0x0B, __("Starting the production"), __("<p>If you didn't notice already, you are losing goods. This is because you consume, but you don't produce anything. You should build some factories right now.<p>Build 2 factories. If you'll start losing money, build more homes!</p>"),
			   null, array (0x05, 0, 1), array (0x0A), 3000, 15, null);
$quests[0x0C] = new Quest (0x0C, __("Going bigger"), __("<p>It seems that you can store only 100 goods, but you need more. You can expand your storage capacity by building warehouses.</p><p>Build a warehouse.</p>"),
			   null, array (0x06, 0, 1), array (0x0B), 1700, 15, null);
$quests[0x0D] = new Quest (0x0D, __("Filling up"),__("<p>Produce 300 goods!</p>"),
			   array ('goods', 0, 300), null, array (0x0B), 2100, 15, null);
$quests[0x0E] = new Quest (0x0E, __("Leisure"), __("<p>Entertainment is important if you want to attract young people to your town and to convince the inhabitants not to move to another town.</p><p>Build some parks and a museum!</p>"),
			   null, array(0x11, 0, 4, 0x18, 0, 1), array (0x0C), 1400, 15, null);
$quests[0x0F] = new Quest (0x0F, __("Wastes"), __("<p>Wastes are deducted directly form your score.</p><p>Build a garbage dump in order to deposit the waste.</p>"),
			   null, array(0x07, 0, 1), array(0x0E), 1400, 15, null);
$quests[0x10] = new Quest (0x10, __("I'm lovin' it"),__("<p>Doubtless, neither you nor your town's inhabitants don't like how starvation sounds.</p><p>Build a restaurant to feed your people.</p>"),
			   null, array(0x17, 0, 1), array(0x0F), 300, 15, null);
$quests[0x11] = new Quest (0x11, __("Town development"), __("<p>Congratulation for getting this far!</p><p>Now, to get even further with the development of your town, build a Research center. Scientists who will work here will develop new sources of energy. Furthermore, you will be able to upgrade some of your existing buildings and to create others.</p><p>Build a Research center and enable researching.</p>"),
			   null, array(0x0F, 0, 1), array(0x10), 5000, 20, array(0x00));
$quests[0x12] = new Quest (0x12, __("Solar energy"), __("<p>As windmills, solar panels produce electricity. Click the 'Research' button and then research your solar panels.</p><p>Build a solar panel.</p>"),
			   null, array (0x0A, 0, 1), array(0x11), 4500, 20, array(0x05));
$quests[0x13] = new Quest (0x13, __("My eco house"),__("<p>Eco houses are now available. They consume and they polute less than normal houses.</p><p>Research and build some.</p>"),
			   null, array(0x10, 0, 5), array(0x12), 5500, 20, array(0x0D));
$quests[0x14] = new Quest (0x14, __("Higher production, less pollution"), __("<p>Sounds impossible?!</p><p>Eco factories make it possible. They consume less energy, as using their own, they pollute less and they produce more than a normal factory.</p><p>Build 5 Eco factories!</p>"),
			   null, array(0x19, 0, 5), array(0x13), 5000, 20);
$quests[0x15] = new Quest (0x15, __("Power plants"), __("<p>Nuclear, thermal and geothermal power plants are built to produce energy.</p><p>However, they pollute a lot. They are not recomanded but they are very useful.</p>"),
			   null, null, array(0x14), 15000, 20, array(0x0F, 0x10, 0x11));
$quests[0x16] = new Quest (0x16, __("Upgrades"), __("<p>You can upgrade even further some of your buildings. Upgrading them will lower their wastes and polution and increase the income and population.</p><p>Upgrade your houses, flats and solar panels to level 2."),
			   null, null, array(0x15), 6000, 20, array(0x01, 0x09, 0x06));
$quests[0x17] = new Quest (0x17, __("IQuest"),__("<p>If you read this text you should know that you have succesfully finished all the quest!</p><p>We would like to congratulate you: Congratulations!! </p><p>And we would like to thank you for playing: Thank you!!</p><p>But... once you finished the quests doesn't mean that you finished the game. Continue to expand your town, continue  to build in order to create the best green town. You can always check yours and other users' score.</p><p>Finally, we would like to wish you good luck: GOOD LUCK!!</p><p>P.S.Here is a litle bonus.</p>"),
			   null, null, array(0x16), 10000, 50, null);
?>
