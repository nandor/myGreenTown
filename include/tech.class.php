<?php
	class tech {
		function __construct ($id, $title, $desc, $cost, $enable, $building, $lvl)
		{
			$this->son 		  = $enable;
			$this->id 	      = $id;
			$this->title      = $title;
			$this->desc       = $desc;
			$this->cost       = $cost;
			$this->building	  = $building;
			$this->lvl		  = $lvl;
		}
	}
	
	$techs    = array();
	
	//			ID	TITLE		DESC							COST	ENABLES		BUILDING	LVL	
	$techs[0x00] = new tech (0x00, __("Researching"	         ), __("This enables researching"						),100   ,  array (0x01,0x05,0x09,0x0D,0x0F,0x10,0x11), -1  ,-1);
	$techs[0x01] = new tech (0x01, __("House lvl 2"	         ), __("Upgrade your houses, making them better."		),1000  ,  array (0x02)		     , 0x02, 1);
	$techs[0x02] = new tech (0x02, __("House lvl 3"	         ), __("Even better houses"	 							),3000  ,  array (0x03)		     , 0x02, 2);
	$techs[0x03] = new tech (0x03, __("House lvl 4"	         ), __("Your houses will have a smaller impact." 		),5000  ,  array (0x04)           , 0x02, 3);
	$techs[0x04] = new tech (0x04, __("House lvl 5"	         ), __("The best houses."      							),10000 ,  null				     , 0x02, 4);
	$techs[0x05] = new tech (0x05, __("Solar panel"	         ), __("A clean source of energy"      					),2000  ,  array (0x06)		     , 0x0A, 0);
	$techs[0x06] = new tech (0x06, __("Solar panel"	         ), __("A clean source of energy"      					),10000 ,  array (0x07)		     , 0x0A, 1);
	$techs[0x07] = new tech (0x07, __("Solar panel"	         ), __("A clean source of energy"      					),10000 ,  array (0x08)		     , 0x0A, 2);
	$techs[0x08] = new tech (0x08, __("Solar panel"	         ), __("A clean source of energy"      					),10000 ,  null				     , 0x0A, 3);
	$techs[0x09] = new tech (0x09, __("Flat lvl 2"	         ), __("Upgrade your flats, making them better."		    ),3000  ,  array (0x0A)		     , 0x03, 1);
	$techs[0x0A] = new tech (0x0A, __("Flat lvl 3"	         ), __("Even better flats"	 							),5000  ,  array (0x0B)		     , 0x03, 2);
	$techs[0x0B] = new tech (0x0B, __("Flat lvl 4"	         ), __("Your flats will have a smaller impact." 	        ),7000  ,  array (0x0C)           , 0x03, 3);
	$techs[0x0C] = new tech (0x0C, __("Flat lvl 5"	         ), __("The best flats."      							),12000 ,  null				     , 0x03, 4);
	$techs[0x0D] = new tech (0x0D, __("Eco house"             ), __("A house wich usues clean sources of energy."     ),3000  ,  array (0x0E)           , 0x10, 0);
	$techs[0x0E] = new tech (0x0E, __("Eco house lvl 2"       ), __("Upgrade your eco house."                         ),6000  ,  null                   , 0x10, 1);
	$techs[0x0F] = new tech (0x0F, __("Thermal power plant"   ), __("A new source of energy"                          ),5000  ,  null                   , 0x14, 0);
	$techs[0x10] = new tech (0x10, __("Geothermal power plant"), __("A new source of energy"                          ),5000  ,  null                   , 0x15, 0);
	$techs[0x11] = new tech (0x11, __("Nuclear power plant"   ), __("A new source of energy"                          ),5000  , null                    , 0x16, 0);
	
	
	
	
?>
