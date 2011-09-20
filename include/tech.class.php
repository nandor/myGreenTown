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
	$techs[0x00] = new tech (0x00, _("Researching"	         ), _("This enables researching"						),100   ,  array (0x01,0x05,0x09,0x0D,0x0F,0x10,0x11), -1  ,-1);
	$techs[0x01] = new tech (0x01, _("House lvl 2"	         ), _("Upgrade your houses, making them better."		),1000  ,  array (0x02)		     , 0x02, 1);
	$techs[0x02] = new tech (0x02, _("House lvl 3"	         ), _("Even better houses"	 							),3000  ,  array (0x03)		     , 0x02, 2);
	$techs[0x03] = new tech (0x03, _("House lvl 4"	         ), _("Your houses will have a smaller impact." 		),5000  ,  array (0x04)           , 0x02, 3);
	$techs[0x04] = new tech (0x04, _("House lvl 5"	         ), _("The best houses."      							),10000 ,  null				     , 0x02, 4);
	$techs[0x05] = new tech (0x05, _("Solar panel"	         ), _("A clean source of energy"      					),2000  ,  array (0x06)		     , 0x0A, 0);
	$techs[0x06] = new tech (0x06, _("Solar panel"	         ), _("A clean source of energy"      					),10000 ,  array (0x07)		     , 0x0A, 1);
	$techs[0x07] = new tech (0x07, _("Solar panel"	         ), _("A clean source of energy"      					),10000 ,  array (0x08)		     , 0x0A, 2);
	$techs[0x08] = new tech (0x08, _("Solar panel"	         ), _("A clean source of energy"      					),10000 ,  null				     , 0x0A, 3);
	$techs[0x09] = new tech (0x09, _("Flat lvl 2"	         ), _("Upgrade your flats, making them better."		    ),3000  ,  array (0x0A)		     , 0x03, 1);
	$techs[0x0A] = new tech (0x0A, _("Flat lvl 3"	         ), _("Even better flats"	 							),5000  ,  array (0x0B)		     , 0x03, 2);
	$techs[0x0B] = new tech (0x0B, _("Flat lvl 4"	         ), _("Your flats will have a smaller impact." 	        ),7000  ,  array (0x0C)           , 0x03, 3);
	$techs[0x0C] = new tech (0x0C, _("Flat lvl 5"	         ), _("The best flats."      							),12000 ,  null				     , 0x03, 4);
	$techs[0x0D] = new tech (0x0D, _("Eco house"             ), _("A house wich usues clean sources of energy."     ),3000  ,  array (0x0E)           , 0x10, 0);
	$techs[0x0E] = new tech (0x0E, _("Eco house lvl 2"       ), _("Upgrade your eco house."                         ),6000  ,  null                   , 0x10, 1);
	$techs[0x0F] = new tech (0x0F, _("Thermal power plant"   ), _("A new source of energy"                          ),5000  ,  null                   , 0x14, 0);
	$techs[0x10] = new tech (0x10, _("Geothermal power plant"), _("A new source of energy"                          ),5000  ,  null                   , 0x15, 0);
	$techs[0x11] = new tech (0x11, _("Nuclear power plant"   ), _("A new source of energy"                          ),5000  , null                    , 0x16, 0);
	
	
	
	
?>
