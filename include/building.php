<?php

//Class definition
$tileCost = 500;

class Building
{
	function __construct ($id, $name, $type, $pop, $pol, $energy, $waste, $costBudget, $costGoods, $income, $prod, $storage, $score, $buildable, $water, $tileable, $needRoad, $limit, $buildTime, $req)
	{
		$this->id		    = $id;
		$this->name 		= $name;
		$this->tileable		= $tileable;
		$this->type		    = $type;
		$this->needRoad     = $needRoad;
		$this->limit        = $limit;
		
		$this->fact[0]['btime']		= $buildTime;
		$this->fact[0]['water']		= $water;	
		$this->fact[0]['pop']		= $pop;
		$this->fact[0]['score']		= $score;
		$this->fact[0]['energy']	= $energy;
		$this->fact[0]['income']	= $income;
		$this->fact[0]['prod']		= $prod;
		$this->fact[0]['store']		= $storage;
		$this->fact[0]['waste']		= $waste;
		
		$this->fact[0]['costB']		= $costBudget;
		$this->fact[0]['costG']		= $costGoods;
		$this->fact[0]['pol']		= $pol;
		
		
		for ($i = 1; $i <= MAXBLVL; $i++) {
			foreach ($this->fact[0] as $f => $v) {
				$this->fact[$i][$f] = intval ($this->fact[0][$f] + abs ($this->fact[0][$f] * BLVLINC * $i));
			}
					
			$this->fact[$i]['waste'] = intval ($this->fact[0]['waste'] - abs ($this->fact[0]['waste'] * BLVLINC * $i));
			$this->fact[$i]['costB'] = intval ($this->fact[0]['costB'] - abs ($this->fact[0]['costB'] * BLVLINC * $i));
			$this->fact[$i]['costG'] = intval ($this->fact[0]['costG'] - abs ($this->fact[0]['costG'] * BLVLINC * $i));
			$this->fact[$i]['pol']	 = intval ($this->fact[0]['pol'] * (1 - BLVLINC * $i));
		}
		
		$this->buildable	= $buildable;
		$this->req		    = $req;
	}
	
	function toJSON ($lvl)
	{
		$str  = "{\"id\":{$this->id},\"name\":\"{$this->name}\",".
				"\"lvl\":$lvl,".
			  	"\"type\":{$this->type},".
			  	"\"tile\":". (($this->tileable) ? 1 : 0) .",".
			  	"\"build\":". (($this->buildable) ? 1 : 0) .",".
			  	"\"fact\":".json_encode ($this->fact[max ($lvl, 0)], true).",".
			  	"\"req\":".json_encode ($this->req)."},";
		return $str;
	}
}


$bldCategory = array (
	2	=>	__("Houses"),
	3	=>	__("Energy"),
	4	=>	__("Economy"),
	5	=>	__("Garbage disposal"),
	6	=>	__("Infrastructure"),
	7	=>	__("Leisure"),
);

//array
$buildings = array ();

//Buildings			             ID    NAME	 	 TYPE	POP	POL 	ENERGY	WASTE	COSTB	COSTG	INCOME	PROD	STORAGE	SCORE	BUILD	WCONS	TILEABLE NEEDROAD LIMIT BTIME REQ
$buildings[0x00] = new Building (0x00, __("Grassland"		    ),0  	,0  	, 1	    ,  0	, 0	    , 0	    ,0	    , 0	    ,0	    ,0	    ,0	    , false	,0	    , false, false,     0,   0, null);
$buildings[0x01] = new Building (0x01, __("Road"			        ),1  	,0	    , 10	,  0	, 1	    , 50	,0	    , 0	    ,0	    ,0	    ,2	    , false ,0	    , true , false,     0,   0, null);
$buildings[0x02] = new Building (0x02, __("Normal House"	        ),2  	,5	    , 12	, -20	, 20	, 120	,0	    , 25	,-10	,0	    ,15	    , true	,-20	, false, true,      0,  15, array (0x0C));
$buildings[0x03] = new Building (0x03, __("Flat"			        ),2 	,50	    , 25	, -50	, 10	, 680	,10	    , 50	,-10	,0	    ,25     , true	,-100	, false, true,      0,  20, array (0x02));
$buildings[0x04] = new Building (0x04, __("Wind turbine"	        ),3  	,-10	, 10	,  300	, 1	    , 500	,0	    , -25	,0	    ,0	    ,45	    , true	,0	    , false, false,     0,  30, null);
$buildings[0x05] = new Building (0x05, __("Factory"		        ),4  	,-50	, 25	, -50	, 20	, 1000	,0	    , -20	,50	    ,0  	,28	    , true	,-50	, false, true,      0, 120, array (0x0E));
$buildings[0x06] = new Building (0x06, __("Warehouse"		    ),4  	,-10	, 25	, -10	, 5	    , 700	,30 	, -30	,0	    ,300	,28	    , true	,0	    , false, true,      0, 300, array (0x05));
$buildings[0x07] = new Building (0x07, __("Garbage Dump"	        ),5  	,-10	, 25	, 0	    ,-100	, 400	,0	    , -30	,0	    ,0	    ,17	    , true	,0	    , false, false,     0, 200, array (0x02));
$buildings[0x08] = new Building (0x08, __("School"			    ),6 	,-5	    , 10	, -50	, 20	, 650	,70 	, -20   ,-10	,0	    ,25	    , true	,-30	, false, true,      1, 120, array (0x02));
$buildings[0x09] = new Building (0x09, __("Recycling station"    ),5 	,-1	    , 1	    , 0	    ,-50	, 1400	,0	    , 0	    , 0 	,0	    ,34	    , true	,0	    , false, true,      0, 120, array (0x07));
$buildings[0x0A] = new Building (0x0A, __("Solar panels"         ),3 	,-5	    , 2	    , 200	, 0	    , 500	,0	    , 0	    , 0 	,0	    ,45	    , true	,0	    , false, false,     0,  90, array (0x0F));
$buildings[0x0B] = new Building (0x0B, __("Town Hall"            ),6 	,-30	, 10	, -30	, 0	    , 200	,0	    , -10	, 0 	,0	    ,26	    , true	,-5	    , false, true,      1, 170, null);
$buildings[0x0C] = new Building (0x0C, __("Hospital"             ),6 	,-30	, 10	, -30	, 0	    , 300	,0	    , -10	, 0 	,0	    ,23	    , true	,-5	    , false, true,      1, 120, array (0x0B));
$buildings[0x0D] = new Building (0x0D, __("Police departement"   ),6 	,-30	, 10	, -30	, 0	    , 330	,0	    , -10	, 0 	,0	    ,23	    , true	,-5	    , false, true,      1, 200, array (0x0C));
$buildings[0x0E] = new Building (0x0E, __("Fire departement"     ),6 	,-30	, 10	, -30	, 0	    , 330	,0	    , -10	, 0 	,0	    ,23	    , true	,-5	    , false, true,      1, 200, array (0x0C));
$buildings[0x0F] = new Building (0x0F, __("Research Center"      ),6 	,-30	, 10	, -30	, 0	    , 1500	,100	, -10	, 0 	,0	    ,38	    , true	,-5	    , false, false,     1, 300, array (0x17));
$buildings[0x10] = new Building (0x10, __("Eco House"            ),2     ,20     ,5      ,-5     ,10     , 500   ,0      , 50    ,-5     ,0      ,30   	, true  ,-5	    , false, true,      0, 100, array (0x0A)); 
$buildings[0x11] = new Building (0x11, __("Park"          		),7     ,0      ,0      ,0      ,1      , 100   ,0      , 0     ,0     	,0      ,16    	, true  ,0	    , true , false,     0,  10, array (0x02)); 
$buildings[0x12] = new Building (0x12, __("Water Tank"     	    ),6     ,-10    ,3      ,-1     ,1      , 200   ,0      ,-50    ,0     	,0      ,18    	, true  ,3000	, false, false,     0, 200, null); 
$buildings[0x13] = new Building (0x13, __("Water Treatment"   	),6     ,-10    ,3      ,-5     ,10     , 400   ,10     ,-50    ,0     	,0      ,33    	, true  ,2000	, false, false,     0, 200, null); 
$buildings[0x14] = new Building (0x14, __("Thermal power plant" 	),3     ,-100   ,20     ,500    ,60     , 1200  ,110    ,-50    ,0    	,0      ,45     , true  ,2000	, false, false,     0, 200, array (0x0F)); 
$buildings[0x15] = new Building (0x15, __("Geothermal power plant"),3    ,-10    ,3      ,300    ,5      , 1200  ,50     ,-20    ,0    	,0      ,45    	, true  ,2000	, false, false,     0, 200, array (0x0F)); 
$buildings[0x16] = new Building (0x16, __("Nuclear power plant" 	),3     ,-200   ,5      ,2000   ,50     , 1200  ,-100   ,-20    ,0     	,0      ,45    	, true  ,2000	, false, false,     0,1500, array (0x0F)); 
$buildings[0x17] = new Building (0x17, __("Restaurant"           ),7     ,-7     ,15     ,-60    ,10     , 300   ,30     ,15     ,0      ,0      ,18     , true  ,-60    , false, true,      0, 300, array (0x06));           
$buildings[0x18] = new Building (0x18, __("Museum"               ),7     ,-5     ,10     ,-45    ,7      , 350   ,50     ,20     ,0      ,0      ,23     , true  ,-45    , false, true,      0, 300, array (0x08));
$buildings[0x19] = new Building (0x19, __("Eco Factory"          ),4     ,-7     ,4      ,-20    ,5      , 1000  ,250    ,-10    ,70     ,0      ,50     , true  ,-45    , false, true,      0, 150, array (0x0F));

define ('ID_ROAD'	, 0x01);
define ('ID_DEL'	, 0x00);
define ('BLD_WASTE'	, 5);

?>
