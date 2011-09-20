<?php

/*
  FILE: town.class.php
  Purpose: definition of a town
*/

require_once ('building.php');
require_once ('tech.class.php');
require_once ('quest.php');

$factID = array ("score", "budget", "income", "goods", "prod", "store", "popHouse", "popWork",  "unemploy", "pol", "energy", "waste", "green", "wcons", "wproc", "wprod");
		
class Town {
	function __construct ($townID)
	{	
		$this->db = @mysql_get ("SELECT * FROM town WHERE `id` = '$townID';");
		
		$this->id = $this->db['id'];
		$this->name = $this->db['name'];
		
		//Initialize size
		$this->sizeX = $this->db['size'] >> 8;
		$this->sizeY = $this->db['size'] & 255;
		
		//Initialize quests and technologies
		$this->questDone   = array ();
		$this->questProg   = array ();
		$this->techRes     = array ();

		for ($i = 0; $i < 32; $i++) {
			for ($j = 0; $j < 8; $j++) {
				$this->questDone   [$i * 8 + 7 - $j] = (ord($this->db['questDone'][$i]    ) >> $j) & 1;
				$this->questProg   [$i * 8 + 7 - $j] = (ord($this->db['questProgress'][$i]) >> $j) & 1;
				$this->techRes     [$i * 8 + 7 - $j] = (ord($this->db['techRes'][$i]     ) >> $j) & 1;
			}
		}
		
		//Initialize the map	
		$arr = $this->db['map'];
		$this->map      = array ();
		$this->tleft    = array ();
		
		for ($i = 0; $i < $this->sizeX * $this->sizeY; $i++) {
			$this->map[$i]   = (int)ord($arr[2 * $i]);
			$this->tleft[$i] = (int)ord($arr[2 * $i + 1]);
		}
		
		$this->fact 	    = array ();	
		$this->numBld 	    = array ();	
		$this->bldLvl 	    = array ();
		$this->lastUpdate   = strtotime ($this->db['lastUpdated']);
		
		$this->scoreBase     = 0;		
		$this->scorePol      = 0;
		$this->scoreUnemploy = 0;
		$this->scoreWaste    = 0;
	    $this->scoreGreen    = 0;
	    
	    $this->trashProd     = 0;
	    $this->trashRecl     = 0;
	    
		$this->fact['score'] = $this->db['score'];
		$this->fact['budget'] = $this->db['budget'];
		$this->fact['goods']  = $this->db['goods'];	
		
		$this->update ();
	}
	
	function update ($updateDB = true)
	{	
		global $buildings, $techs;
		
		// Update the facts
		$currentTime = time ();		
		
		// Update map & factors
		$this->fact['score']		= 0;
		$this->fact['income']		= 0;
		$this->fact['prod']		    = 0;
		$this->fact['store']		= 100;	
		$this->fact['popWork']		= 0;
		$this->fact['popHouse']		= 0;	
		$this->fact['pol']		    = 0;
		$this->fact['energy']		= 0;
		$this->fact['waste']		= 0;	
		$this->fact['water']		= 0;
		
			
		for ($i = 0; $i < 0xFF; $i++) {
			$this->numBld[$i] = $this->bldLvl[$i] = 0;
		}		
				
		foreach ($techs as $i => $t) {
			if ($this->techRes [$i] && $t->building != -1) {
				$this->bldLvl[$t->building] = max ($this->bldLvl[$t->building], $t->lvl);
			} else {
				if ($t->building != -1 && $t->lvl == 0) {
					$this->bldLvl[$t->building] = -1;
				}
			}
		}		
				
		$numGreen = 0;
		
		$str = "";
		for ($i = 0; $i < $this->sizeX * $this->sizeY; $i++) {
			$this->tleft[$i] = max(0, $this->tleft[$i] - ($currentTime - $this->lastUpdate));
			
			$str .= dechex (($this->map[$i]   >>  4) & 15).dechex ($this->map[$i]   & 15);
			$str .= dechex (($this->tleft[$i] >>  4) & 15).dechex ($this->tleft[$i] & 15);
			
			if ($this->tleft[$i] == 0) {
				$this->numBld [$this->map[$i]] ++;
		
				$bld = $buildings[$this->map[$i]];
				$lvl = $this->bldLvl [$bld->id];
				
				$this->fact['pol']   	+= $bld->fact[$lvl]['pol'];
				$this->fact['score']	+= $bld->fact[$lvl]['score'];
				$this->fact['store'] 	+= $bld->fact[$lvl]['store'];
				$this->fact['income'] 	+= $bld->fact[$lvl]['income'];
				$this->fact['energy'] 	+= $bld->fact[$lvl]['energy'];
				$this->fact['prod']	    += $bld->fact[$lvl]['prod'];
				$this->fact['waste']	+= $bld->fact[$lvl]['waste'];			
				$this->fact['water']	+= $bld->fact[$lvl]['water'];
				
				$this->trashProd += max ($bld->fact[$lvl]['waste'], 0);
				$this->trashRecl -= min ($bld->fact[$lvl]['waste'], 0);
				
				$this->fact[($bld->fact[$lvl]['pop']    < 0) ? 'popWork'  : 'popHouse'] += abs ($bld->fact[$lvl]['pop']);
				
				if ($bld->type == 7) {
					$numGreen++;
				}
			}
		}
				
		$this->scoreBase = $this->fact['score'];
		
		if ($this->sizeX * $this->sizeY > 0) {
		    //Compute pollution
		
			$this->fact['green'] = $numGreen / ($this->sizeX * $this->sizeY) * 100;
		    $this->fact['pol'] = intval ($this->fact['pol'] / ($this->sizeX * $this->sizeY));
		    $pol = $this->fact['pol'] / 25;
			
			
		    //unemployment		
		    $pop = $this->fact['popWork'] ? ($this->fact['popHouse'] / $this->fact['popWork']) : 0;
		
		    $this->fact['unemploy'] = ($this->fact['popHouse']) ? ((1 - $this->fact['popWork'] / $this->fact['popHouse']) * 100) : 0;
		
		    if ($pop < 1) {				
			    $this->fact['prod']   = (($this->fact['prod'] 	< 0) ? -1 : 1) * abs ($this->fact['prod'  ] * $pop);
			    $this->fact['income'] = (($this->fact['income'] < 0) ? -1 : 1) * abs ($this->fact['income'] * $pop);
		    }
		    
		    $this->fact['goods'] = max(0, min ($this->fact['goods'] + intval ($this->fact['prod'] * ($currentTime - $this->lastUpdate) / 60), $this->fact['store']));		
		    $this->fact['budget'] = max (0, $this->fact['budget'] + intval ($this->fact['income'] * ($currentTime - $this->lastUpdate) / 60));
		
		    $this->fact['waste'] = max (0, $this->fact['waste']);		
		    $this->fact['score'] = max (0, $this->fact['score'] - $this->fact['waste']);
		    
		    
		    $this->fact['score'] *= 1 + ($this->fact['green']) / 50;
		    
			$this->scorePol         = (1.5 - $pol) * 100 - 100;
		    $this->scoreWaste       = -$this->fact['waste'];
		    $this->scoreGreen       = (1 + ($this->fact['green']) / 50) * 100 - 100;
		}
		
		$this->lastUpdate = $currentTime;
		
		//New quests
		global $quests;		
		foreach ($quests as $i => $q) {
			if ($this->questDone [$i]) {
				$this->fact['score'] += $q->score;
			}
		}
				
		$this->fact['score'] = $this->fact['score'] * (1.5 - $pol) * (1 - max($this->fact['unemploy'] / 200, 0) / 2);
				
		//Write quests
		$questP = ""; $questD = ""; $techR = ""; $techE = "";
		for ($i = 0; $i < 32; $i++) {
			$a = 0;$b = 0;$c = 0; $d = 0;
			for ($j = 0; $j < 8; $j++) {
				if ($this->questProg[$i * 8 + 7 - $j]) {
					$b |= (1 << $j);
				}
				if ($this->questDone[$i * 8 + 7 - $j]) {
					$a |= (1 << $j);
				}
				if ($this->techRes[$i * 8 + 7 - $j]) {
					$d |= (1 << $j);
				}
			}
			$questD .= dechex($a >> 4) . dechex ($a & 15);
			$questP .= dechex($b >> 4) . dechex ($b & 15);
			$techR  .= dechex($d >> 4) . dechex ($d & 15);
		}
		
		if ($updateDB) {
			mysql_query ("UPDATE town SET ".
		
					"`map` 		   = 0x$str,".
					"`lastUpdated` = FROM_UNIXTIME({$currentTime}),".
					"`score` 	   = '{$this->fact['score']}',".
					"`budget` 	   = '{$this->fact['budget']}',".
					"`goods` 	   = '{$this->fact['goods']}',".
					"`name`		   = '{$this->name}',".
					"`questProgress` = 0x$questP,".
					"`questDone`   = 0x$questD,".
					"`techRes`     = 0x$techR ".
				
					"WHERE id = '{$this->id}';");
		}
	}
	
	function getHeaderJSON ()
	{
	    return "{\"x\":{$this->sizeX},\"y\":{$this->sizeY},\"name\":\"{$this->name}\",\"fact\":".json_encode ($this->fact)."}";
	}
	
	function getMapDataJSON ()
	{
	    $json = "{\"type\":0,\"header\":{$this->getHeaderJSON()},\"map\":[";
	    
	    for ($i = 0; $i < $this->sizeX * $this->sizeY; $i++) {
	        $json .= "{\"id\":{$this->map[$i]}, \"tl\":{$this->tleft[$i]}},";
	    }
	    
	    $json .= "0]}";
	    return $json;	
	}
	
	function getSimpleMapData ()
	{
	    $json = "{\"sizeX\":{$this->sizeX}, \"sizeY\":{$this->sizeY}, \"map\":[";
	    
	    for ($i = 0; $i < $this->sizeX * $this->sizeY; $i++) {
	        $json .= "{$this->map[$i]},";
	    }
	    
	    $json .= "0]}";
	    return $json;
	}
	
	function getAvailBld ()
	{
		global $buildings;
		$str = "";
		
		foreach ($buildings as $bld) {
			if ($bld->buildable) {
				$ok = true;
				if ($bld->req != null) {
					foreach ($bld->req as $req) {
						$ok = $this->numBld[$req] > 0;
						if (!$ok) {
							break;
						}
					}
				}
				
				if ($ok && $this->bldLvl [$bld->id] != -1) {
					$str .= $bld->id . " ";
				}
			};
		}
		return $str;
	}
	
	function onMap ($x, $y) {
		return 0 <= $x && $x < $this->sizeX && 0 <= $y && $y < $this->sizeY;
	}
	
	function build ($x, $y, $id)
	{		
		global $buildings;
				
		$lvl = $this->bldLvl [$id];
		
		if ($lvl == -1) {
			return _("You can't build this yet!");
		}
		$this->update (false);
		
		if ($this->map[$x * $this->sizeY + $y] != 0 && $id != 0) {
			return _("You must demolish first!");
		}
		
		$costB = $buildings[$id]->fact[$lvl]['costB'];
		$costG = $buildings[$id]->fact[$lvl]['costG'];
				
		if ($buildings[$id]->limit && $this->numBld[$id] + 1 > $buildings[$id]->limit) {
		    return _("Build limit reached!");
		}
		
		if ($this->fact['budget'] < $costB) {
			return _("You don't have enough money!");
		}
		
		if ($this->fact['goods'] < $costG) { 
			return _("You don't have enough goods!");
		}
		
		$oldID = $this->map[$x * $this->sizeY + $y];
		if ($buildings[$oldID]->fact[$lvl]['energy'] != 0 || $buildings[$id]->fact[$lvl]['energy'] != 0) {
			if ($id == 0) {
			
				if ($this->fact['energy'] - $buildings[$oldID]->fact[$lvl]['energy'] < 0) {
					return _("You won't produce enough energy!");
				}
			
				if ($this->fact['water'] - $buildings[$oldID]->fact[$lvl]['water'] < 0) {
					return _("You won't have enough water!");
				}
			} else {
				if ($this->fact['energy'] + $buildings[$id]->fact[$lvl]['energy'] < 0) {
					return _("You don't produce enough energy!"); 
				}
				if ($this->fact['water'] + $buildings[$id]->fact[$lvl]['water'] < 0) {
					return _("You don't have enough water!"); 
				}
			}
		}
		$dx = array (-1, -1, -1, 0, 1, 1,  1,  0);
		$dy = array (-1,  0,  1, 1, 1, 0, -1, -1);
		
		$roadNext = false;
		
		for ($i = 0; $i < 8; $i++) {
			$nx = $x + $dx[$i];
			$ny = $y + $dy[$i];
			
			if ($this->onMap ($nx, $ny) && $this->map[$nx * $this->sizeY + $ny] == ID_ROAD) {
				$roadNext = true;
				break;
			}
		}
		
		if (!$roadNext && $buildings[$id]->needRoad) {
			return _("Buildings must be constructed near roads!");
		}
		
		$id = (int)$id;
		$idx = $x * $this->sizeY + $y;
		
		$this->map[$idx] = $id;
		$this->tleft[$idx] = $buildings[$id]->fact[$lvl]['btime'];
					
		$this->fact['budget'] -= $costB;
		$this->fact['goods'] -= $costG;
					
		$this->update ();	
			
	    return "{\"type\":1,\"header\":{$this->getHeaderJSON()},\"idx\":$idx,\"tile\":{\"id\":{$this->map[$idx]}, \"tl\":{$this->tleft[$idx]}}}";
	}
	
	function rename ($newName)
	{
		$this->name = $newName;	
		$this->update ();
		
		return "{\"type\":2,\"header\":{$this->getHeaderJSON()}}";
	}
	
	function claimQuest ($q) {
		global $quests;	
		if (!$this->questDone[$q]) {	
			$this->questProg[$q] = 1;
			$this->update ();
		}
	}
	
	function finishQuest ($q) {
		global $quests;	
		if ($this->questProg[$q] && $quests[$q]->checkObj ($this)) {
			$this->questProg [$q] = 0;
			$this->questDone [$q] = 1;
			$this->fact['budget'] += $quests[$q]->reward;
			$this->update ();
		}
	}
}


function addTown ($name)
{
	$town = "";$questDone = "";
	
	for($i = 0; $i < 1600; $i++)
		$town.= "0000";
	for($i = 0; $i < 32; $i++)
		$questDone.= "00";
	
	$cTime = time();
	mysql_query ("ALTER TABLE town AUTO_INCREMENT = 1;");
	mysql_query ("INSERT INTO town (`name`, `lastUpdated`, `map`,`questDone`, `questProgress`, `techRes`)".
		     "VALUES ('$name', FROM_UNIXTIME($cTime), 0x$town, 0x$questDone, 0x$questDone, 0x$questDone);");
		   		
	return mysql_insert_id ();
}

?>
