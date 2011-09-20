/*
	File: game.js
	Purpose: JavaScript code for the game core
*/


/***************************************************************************************************
	Initialization
***************************************************************************************************/

function initGame ()
{	
	$(document).bind ("contextmenu", function(evt) {evt.preventDefault ();return false;}); 
	$(document).bind ("dragstart",   function(evt) {evt.preventDefault ();return false;});
				
	getMap ("query.php?cmd=getmap&town=" + townID);	
	
	ui_init ();	
	
	$("#gamezoneClick")
		.click(function (evt) {
			if (!was_dragged) {
				contextMenu_hide ();
				var tile;
				if (tile = getTile (evt.pageX - pxLeft, evt.pageY - pxTop)) {
					ui_loadTile (tile);
				}
			}
		})
		.mousedown(function (evt)
		{
			if(evt.which == 3) {
				var tile;
				if (tile = getTile (evt.pageX - pxLeft, evt.pageY - pxTop)) {
					contextMenu_show (evt.pageX, evt.pageY, tile);
				}
			} else {
				drag_ok = true; was_dragged = false;
				
				contextMenu_hide ();
				evt.preventDefault (); evt.stopPropagation ();
				r_mx = evt.pageX; r_my = evt.pageY;
				
				$("#gamezoneClick").mousemove (function (evt)
				{
					if (drag_ok) {
						was_dragged = true;
						evt.preventDefault (); evt.stopPropagation ();
		
						pxLeft = max (min(areaWidth /2, pxLeft += evt.pageX - r_mx), -pxWidth + areaWidth / 2);
						pxTop  = max (min(areaHeight /2, pxTop += evt.pageY - r_my), -pxHeight + areaHeight / 2);

						$(document.body).css ("cursor", "move");
						$("#tileHolder").css ("left", pxLeft).css ("top", pxTop);
						ui_minimap_update ();

						r_mx = evt.pageX; r_my = evt.pageY;
					}
				});
			}
			evt.returnValue = false;
			return false;
		})
		.bind ('mouseup mouseleave', function (evt)
		{
			setTimeout ("was_dragged=false", 30);
			$(document.body).css ("cursor", "default");	
			$("#gamezoneClick").unbind ('mousemove');
			drag_ok = false;			
		});
		
	$("#gamezoneClick").mousewheel (function (evt, delta) {
		zoom (delta / Math.abs (delta) * 3);
	});
	
	$(window).resize (function ()
	{
		areaWidth = $("#gamezone").width ();
		areaHeight = $("#gamezone").height ();
		ui_minimap_update ();		
	})
}


var r_mx, r_my, drag_ok = false, was_dragged = false;

// Map
var fact = new Array ();
// Constants
var tileWidth = 120;
var tileHeight = 60;
var maxSize = 20, tileCost = 500;
var building = new Array ();
var domain = '/';
// Main variables
var pxWidth = 0, pxHeight = 0, pxLeft = 100, pxTop = 100, townName, sizeX = sizeY = 0;
var areaWidth, areaHeight;
var pol_show = false;
var cpt = normalize (0, 0);
var mapRot = 0, mapRnd = Math.random ();
var mapData;

/***************************************************************************************************
	Map Loading
***************************************************************************************************/

function getMap (query)
{	    
	$("#gwait").show ();
	$.getJSON (query, function (json)
	{  
	    if (json != 0) {
		    loadTownHeader (json.header);
		    
		    if (json.type == 0) {
	            map = json.map;
		        processMap ();
		    } else if (json.type == 1) {		    
		        map[json.idx] = json.tile;
		        		    
		        
				for (var k = 0; k < 4; ++k) {
					if (onMap (cpt.i + dx[k], cpt.j + dy[k])) {
						solveTile ({"i":cpt.i + dx[k], "j":cpt.j + dy[k]});
					}
				}
				solveTile (cpt);
				ui_loadTile (cpt);
		    }
		    
			loadBuildingList ();
	    
            ui_minimap_update ();
            
		    mapRnd = Math.random ();
			being_built = false;
			ui_minimap_update ();
	    } else {
	        window.location.replace (domain);
	    }	
	    $("#gamezone").show ();
	    $("#gwait").hide ();
	}).error (function (data)
	{
	    wait_end ();
	    
	    $("#gamezone").show ();
	    $("#gwait").hide ();

    	var idx = cpt.i * rsY + cpt.j;
		$("#x" + cpt.i + "_" + cpt.j + " .mapTileImg").attr ("src", "img/tile/" +  map[idx].id + "_" + building[map[idx].id].lvl + "_" + map[idx].tile + ".gif");
		warning (data.responseText);
		being_built = false;
	});
}
	
var dx = new Array (-1, 0, 1,  0), dy = new Array (0, 1, 0, -1);
      
function processMap ()
{										
	sizeX = (mapRot & 1) ? rsY: rsX;
	sizeY = (mapRot & 1) ? rsX: rsY;
	
	$("#tileHolder")
	    .html ("")
	    .css  ("width", pxWidth = ((sizeX + sizeY) * tileWidth) / 2)
	    .css  ("height", pxHeight = ((sizeX + sizeY) * tileHeight) / 2);

	baseX = 0, baseY = pxHeight - (sizeY + 1) * tileHeight / 2;
	
	for (var ri = 0; ri < sizeX; ri++) {
	    for (var rj = sizeY - 1; rj >= 0; rj--) {
	        pt = normalize (ri, rj);
	          
            //read data from xml
            var idx = pt.i * rsY + pt.j;
			if (map[idx].id == 1 && parseInt (Math.random () * 100) % 30 == 0) {
				initAnim (pt, parseInt (Math.random () * 100) % 3 + 1);
			}
		    
			tile = "<div id = 'x" + pt.i + "_" + pt.j + "' class = 'mapTile' style = 'left:" + (baseX + (tileWidth / 2) * rj) + "px;bottom:" + (baseY + (tileHeight / 2) * rj)+ "px;z-index:" + ((sizeY - rj + 1) * 100000 + ri * 100000) + "'>";
			tile += "<img class = 'mapTilePol'/><img class = 'mapTileImg'/>";
			tile += "<div id = 'cd_" + idx + "'></div>";
			tile += "</div>";
						
            $("#tileHolder").append (tile);
			
			map[idx].tile 	= 0;
	    }
		baseX += tileWidth / 2;
		baseY -= tileHeight / 2;
	}
	
	baseX = 0, baseY = pxHeight - (sizeY + 1) * tileHeight / 2;
		
	for (var i = 0; i < sizeX; ++i) {
	    for (var j = 0; j < sizeY; ++j) {
	        solveTile (normalize (i, j));
        }
	}
	
    if (pol_show) {
        $(".mapTilePol").show ();
    } else {
        $(".mapTilePol").hide ();
    }   

    $("#tileHolder").append ('<img id = "tile_selector" src = "img/tile/highlight.png">');

    $(window).trigger ("resize");

    setTimeout ("being_rotated = false;", 500);
	cpt = normalize (0, 0);
    ui_loadTile (cpt);	
        
	for (id in anim) {
		animate (id);
    }
}

function zoom (dd)
{
	otw = tileWidth, oth = tileHeight;
	tileWidth += dd;
	tileWidth = max (min (tileWidth, 240), 30);
	tileHeight = tileWidth / 2;
	
	$("#tileHolder")
	    .css  ("width", pxWidth = ((sizeX + sizeY) * tileWidth) / 2)
	    .css  ("height", pxHeight = ((sizeX + sizeY) * tileHeight) / 2);

	$("#tileHolder img").css ("width", tileWidth);
	
	
	baseX = 0, baseY = pxHeight - (sizeY + 1) * tileHeight / 2;
	for (var ri = 0; ri < sizeX; ri++) {
	    for (var rj = sizeY - 1; rj >= 0; rj--) {
	        pt = normalize (ri, rj);
			$("#x" + pt.i + "_" + pt.j).css ({
				left: (baseX + (tileWidth / 2) * rj),
				bottom: (baseY + (tileHeight / 2) * rj)
			});
		}
		baseX += tileWidth / 2;
		baseY -= tileHeight / 2;
	}
	baseX = 0, baseY = pxHeight - (sizeY + 1) * tileHeight / 2;
	
	$("#tile_selector")
		.css ("left", (baseX + (tileWidth / 2) * (cpt.rj + cpt.ri)))
		.css ("bottom", (baseY + (tileHeight / 2) * (cpt.rj - cpt.ri)));
	
	for (id in anim) {
		anim[id].elem.css ({
			left: (baseX + (tileWidth / 2) * (anim[id].y + anim[id].x)),
			bottom: (baseY + (tileHeight / 2) * (anim[id].y - anim[id].x))
		});
		anim[id].elem.stop ();
		animate (id);
	}
	
	ui_minimap_update ();
}

function solveTile (pt)
{
    var idx = pt.i * rsY + pt.j;
	 	  
	map[idx].tile = 0;
	
	if (building[map[idx].id].tile) {
		for (var k = 0; k < 4; ++k) {	
			var nx = pt.i + dx[k], ny = pt.j + dy[k], nidx = nx * rsY + ny;

			if (onMp (pt.i, pt.j) && onMp (nx, ny)) {
				if (map[nidx].id == map[idx].id) {
					map[idx].tile  |= 1 << (7 - k - mapRot) % 4; 
				}
			}
		}
	}

	$("#cd_" + idx).html ("");
	$("#x" + pt.i + "_" + pt.j + " .mapTilePol").attr ("src", "img/stat_map/" + (building[map[idx].id].fact.pol + 1) + ".png")

	if (map[idx].tl != 0) {
		$("#x" + pt.i + "_" + pt.j + " .mapTileImg").attr ("src", "img/tile/cons.gif");
	} else {
		$("#x" + pt.i + "_" + pt.j + " .mapTileImg").attr ("src", "img/tile/" +  map[idx].id + "_" + building[map[idx].id].lvl + "_" + map[idx].tile + ".gif");
	}
}

var updateInterval = null;

var factID = new Array ("score", "budget", "income", "goods", "prod", "store", "popHouse", "popWork",  "unemploy", "pol", "energy", "waste", "green", "water");

function loadTownHeader (header)
{	
	rsX = header.x;
	rsY = header.y;
		
	$(".townName").text (townName = header.name);
	$("#ui_newS").text (rsX + "x" + rsY);	
	for (var i = 0; i < factID.length; ++i) {
		fact[factID[i]] = header.fact[factID[i]];
	}
	
	$(".budget"). text (fact['budget']);
	$(".goods"). text (fact['goods']);
	
	if (updateInterval) {
		clearInterval (updateInterval);
	}
	
	updateStats (1e20);
	updateInterval = setInterval ("updateStats (60)", 1000);
}


function updateStats (v)
{
	fact['budget'] += fact['income'] / v;
	fact['goods'] += fact['prod'] / v;
	
	$(".goods").css ("color", "black");
	$(".budget").css ("color", "black");
			
	if (fact['goods'] <= 0 || fact['goods'] >= fact['store']) {
		$(".goods").css ("color", "red");
		fact['goods'] = Math.max (0, Math.min (fact['goods'], fact['store']));
	} 
	
	fact['unemploy'] = max (0, fact['unemploy']);
	
	if (fact['budget'] <= 0) {
		$(".budget").css ("color", "red");
		fact['budget'] = Math.max (0, fact['budget']);
	} 
	
	for (var i = 0; i < factID.length; ++i) {
		$("." + factID[i]).text (parseInt(fact[factID[i]]));
	}
	
	var doUpdate = false;
	for (var idx = sizeX * sizeY - 1; idx >= 0; idx--) {
		if (map[idx].tl != 0) {
			var i = --map[idx].tl;
			
			$("#cd_" + idx).html (parseInt (i / 3600) + ":" + parseInt ((i % 3600) / 60) + ":" + parseInt (i % 60));
			
			if (map[idx].tl == 0) {
				$("#cd_" + idx).html ("");
				doUpdate = true;
			}
		}
	}
	
	if (doUpdate) {
		getMap ("query.php?cmd=update");
	}
}

function onMp (x, y)
{
	return (0 <= x && x < rsX && 0 <= y && y < rsY);
}

function onMap (x, y) 
{
	if (!(mapRot & 1) && (x < 0 || sizeX <= x || y < 0 || sizeY <= y)) {
		return false;
	} else if ((mapRot & 1) && (x < 0 || sizeY <= x || y < 0 || sizeX <= y)) {
		return false;
	}
	return true;
}

function normalize (ri, rj) {	 
    i = ri, j = rj;
        
    switch (mapRot) {
        case 0: i = ri, j = rj;break;
        case 1: i = sizeY - rj - 1, j = ri;break;
        case 2: i = sizeX - ri - 1, j = sizeY - rj - 1;break;
        case 3: i = rj, j = sizeX - ri - 1;break;
    }
    return {"i":i, "j":j, "ri":ri, "rj":rj};
}

//Selection

function getTile (px, py)
{
	py -= (sizeY * tileHeight / 2)
	var ret = new Object ();
	
	i = Math.floor((px / 2 + py) / tileHeight);
	j = Math.floor((px / 2 - py) / tileHeight);
	
	ret = normalize (i, j);
	
	if (!onMap (ret.i, ret.j)) {
		return false;
	}
	
	return ret;
}

/***************************************************************************************************
	User Interface
***************************************************************************************************/

var being_rotated = false;

function ui_init ()
{
	$("#gameControl img").live ('click', function ()
	{
		switch ($(this).attr ("alt")) {
			case 'pol':
				pol_show = !pol_show;
				$(".mapTilePol").toggle ();
				break;
			case 'town':
				ui_panel_toggle (6, 'townmng.php');
				break;
			case 'quest':
			    ui_panel_toggle (1, 'quests.php');
				break;
			case 'tech':
			    ui_panel_toggle (2, 'tech.php');
				break;
			case 'profile':
			    ui_panel_toggle (3, 'profile.php');
				break;
		    case 'doc':
		        ui_panel_toggle (4, 'textile.php?page=' + cid + '.tex');
		        break;
			case 'stat':
			    ui_panel_toggle (5, 'towninfo.php');
				break;
			case 'mail':
			    ui_panel_toggle (6, 'mail.php');
				break;
			case 'logout':
				window.location.replace (domain);
				break;
			case 'compass':
			    if (!being_rotated) {
			        being_rotated = true;
			        mapRot = (mapRot + 1) % 4;
					
			        $(this).attr ("src", "img/compass" + mapRot + ".png");
			
	                getMap ("query.php?cmd=getmap&town=" + townID);	
			    }
			break;
		}
	});
	
	initContextMenu ();
	
	$("#ui_stats td").hover (function ()
	{
		$("#ui_stat_title").text ($("img", this).attr ("alt"));
	}, function ()
	{
		$("#ui_stat_title").text ("Stats");
	}).click (function ()
	{
		ui_panel_toggle (4, "textile.php?page=stats.tex");
	});
	
	$(".ui_bld_cat_title").click (function ()
	{
		$("tr", $(this).next ()).toggle ();
	});
		
		
	$("#ui_minimap_drag")
	    .mousedown (function (e)
	    {
		    e.preventDefault ();e.stopPropagation ();
		    ui_mx = e.pageX - $("#ui_minimap_drag").offset().left;
		    ui_my = e.pageY - $("#ui_minimap_drag").offset().top;
		
		    $("#ui_minimap_body").bind ('mousemove.ui_minimap', function (evt){
	            evt.preventDefault ();evt.stopPropagation ();
	            var off = $("#ui_minimap_body").offset ();

                var x = max (0, min (evt.pageX - off.left - ui_mx, ui_mini_width - $("#ui_minimap_drag").width ()));
                var y = max (0, min (evt.pageY - off.top - ui_my, ui_mini_height - $("#ui_minimap_drag").height ()));
                           
	            $("#ui_minimap_drag").css ("left", x).css ("top", y);
		
	            $("#tileHolder").css ({
		            left : (pxLeft = (- x * (pxWidth + areaWidth) / ui_mini_width + areaWidth / 2)),
		            top: (pxTop = (- y * (pxHeight + areaHeight) / ui_mini_height + areaHeight / 2))
	            });
		    });
				
		    $(document).mouseup (function (e)
		    {
			    $("#ui_minimap_body").unbind ('mousemove.ui_minimap');
		    });
	    });
	    
	$("#ui_box_cat").show ();
	$("#ui_box_ret").hide ();
	
	
			
	$(".ui_box_img")
		.mousedown (function (evt)
		{
		
			evt.preventDefault (); evt.stopPropagation ();

			bID = $(this).attr ("alt");
			
			if (bID > 0 && building[bID].avail) {					
				$("body").append ("<img src = 'img/tile/" + bID + "_" + building[bID].lvl + "_0.gif' id = 'ui_bld_drag' />");			
				
				$("#ui_bld_drag")
					.css ("bottom", $(window).height () - evt.pageY - 30)
					.css ("left", evt.pageX - tileWidth / 2);
			
				$("#tile_selector").hide ();
				
				$(document).bind ('mousemove.ui_bld', function (evt)
				{
					evt.preventDefault (); evt.stopPropagation ();
					$("#ui_bld_drag")
						.css ("bottom", $(window).height () - evt.pageY - 30)
						.css ("left", evt.pageX - tileWidth / 2);
					
					if ((evt.pageY < $(window).height () - 150) && (poz = getTile (evt.pageX - pxLeft, evt.pageY - pxTop))) {
						$("#tile_selector")
							.css ("left"  , (baseX + (tileWidth  / 2) * (poz.rj + poz.ri)))
							.css ("bottom", (baseY + (tileHeight / 2) * (poz.rj - poz.ri)))
							.show ();
					} else {
						$("#tile_selector").hide ();
					}
				});
			}
			$(document).bind ('mouseup.ui_bld', function (evt)
			{		
				
				$(document).unbind ('mousemove.ui_bld');
				$(document).unbind ('mouseup.ui_bld');
				$("#ui_bld_drag").remove ();
						
				if (bID < 0) {
					if (bID == -100) {
						$("#ui_box_ret").hide ();
						$("#ui_box_cat").show ();
						$("#ui_box_bld div").hide ();
					} else {
						$("#ui_box_ret").show ();
						$("#ui_box_cat").hide ();
						
						$("#ui_box_bld div").hide ();
						$(".c_" + (-bID)).show ();
					}	
					cCat = -bID;				
					return;
				}
				
				if (building[bID].avail) {
					if ((evt.pageY < $(window).height () - 150) && (poz = getTile (evt.pageX - pxLeft, evt.pageY - pxTop))) {
						cpt = poz;
						build (bID);
					} else {
						$("#ui_bld_drag").remove ();
						$("#tile_selector").show ();
						ui_loadTile (cpt);
					}		
				}
			});
		})
						 
		$(".ui_box_img").hover (function (evt)
		{
			id = parseInt ($(this).attr ("alt"));
			
			if (id < 0) return;
			
			loadBuildingInfo (id, true);
		}, function (evt)
		{
			loadBuildingInfo (cid);
		});
}

var ui_c_ns, cpt, cid, clvl;

function ui_loadTile (pt)
{
    cpt = pt;
	$(".ui_hover").remove ();
		
    cidx = cpt.i * rsY + cpt.j;

	ui_c_ns = false;
	$("#tile_selector")
		.css ("left", (baseX + (tileWidth / 2) * (cpt.rj + cpt.ri)))
		.css ("bottom", (baseY + (tileHeight / 2) * (cpt.rj - cpt.ri)));
		
	cid = map[cidx].id;
	
	
	if (ui_panel_displayed && ui_panel_last == 4) {
	    $("#ui_panel_content").load ("textile.php?page=" + cid + ".tex");
	}
	loadBuildingInfo (cid);
	
}

cCat = -1;

function loadBuildingList ()
{	  
	$.ajax({type: "GET",
		data: "cmd=abld",
		url:  "query.php",
		cache: false,
		success: function(data)
		{						
			for (var i in building) {
				building[i].avail = false;
			}
			
			$("#ui_box_bld div").css ("opacity", "0.6");			
			var blist = data.split (" ");
			for (var i = 0; i < blist.length; ++i) {
				var bID = parseInt (blist[i]);		
				
				if (bID) {
					building[bID].avail = true;
					$("#ui_box_bld img[alt=" + bID +"]").parent ().css ("opacity", "1.0");
				}
			}
			
			$("#ui_box_bld div").hide ();
			$(".c_" + cCat).show ();
		}
	});
}

function loadBuildingInfo (id, more)
{
	$("#ui_bImg").attr ("src", $("#x" + cpt.i + "_" + cpt.j + " .mapTileImg").attr ("src"));
	$("#ui_bName").html (building[id].name);
	
	$("#ui_stats").hide ();
	$("#ui_stats_more").hide ();
	
	if (building[id].build) {
		$("#ui_stats").show ();
										
		$("#b_pop")   .text (building[id].fact.pop);
		$("#b_energy").text (building[id].fact.energy);
		$("#b_prod")  .text (building[id].fact.prod);
		$("#b_income").text (building[id].fact.income);
		$("#b_pol")   .text (building[id].fact.pol);
		$("#b_water") .text (building[id].fact.water);
		$("#b_waste") .text (building[id].fact.waste);
		$("#b_score") .text (building[id].fact.score);
	}	
	
	if (more) {
		$("#ui_stats_more").show ();
		$("#b_money").text (building[id].fact.costB);
		$("#b_goods").text (building[id].fact.costG);
		$("#b_btime").text (building[id].fact.btime);
		
		$("#ui_stats_more table .req").remove ();
		if (!building[id].avail) {
			for (var i in building[id].req) {
				$("#ui_stats_more table").append ("<tr class = 'req'><td colspan = '2'>" + building[building[id].req[i]].name + "</td></tr>");
			}
		}
	}
}

being_built = false;
function build (bID)
{	
	if (!being_built) {
		being_built = true;
		if (bID > 1) {
			lastBuild = bID;
			$("#contextMenu img[alt=3]").attr ("src", "img/tile/" + bID + "_" + building[bID].lvl + "_0.gif").show ();
		} else {
			$("#contextMenu img[alt=3]").hide ();
		}
		$("#x" + cpt.i + "_" + cpt.j + " .mapTileImg").attr ("src", "img/tilewait.gif");
		getMap ("query.php?cmd=bld&x=" + cpt.i + "&y=" + cpt.j + "&id=" + bID);
	}
}

/***************************************************************************************************
	Context Menu
***************************************************************************************************/

function contextMenu_show (mx, my, pt)
{
	ui_loadTile (pt);
	$("#contextMenu").css ("top", my).css ("left", mx). fadeIn ('fast');
}

function contextMenu_hide ()
{
	$("#contextMenu").fadeOut ('fast');
}

function initContextMenu ()
{	
	$("#contextMenu").hide ();	
	$("#contextMenu img[alt=3]").hide ();
	$("#contextMenu img").click (function (evt)
	{
		evt.preventDefault (); evt.stopPropagation ();
		switch (parseInt ($(this).attr ("alt"))) {
			case 1:	build (0x01);  break;
			case 2:	build (0x00);  break;
			case 3: build (lastBuild); break;
		}
		contextMenu_hide ();
	});
}

/***************************************************************************************************
	Minimap
***************************************************************************************************/

var ui_mx = 0, ui_my = 0;

function ui_minimap_update ()
{
	$("#ui_map").attr ("src", "minimap.php?&rot=" + mapRot + "&rnd=" + mapRnd + "&town=" + townID);
	
	ui_mini_width = (areaWidth < areaHeight) ? (150 * areaWidth / areaHeight) : 260;
	ui_mini_height = (areaWidth < areaHeight) ? 150 : (260 * areaHeight / areaWidth);

	$("#ui_minimap_body")
		.css ("width", ui_mini_width)
		.css ("height", ui_mini_height)
		.css ("top", (150 - ui_mini_height) / 2);
	
	$("#ui_minimap_drag")
		.attr ("width", ui_mini_width * areaWidth / (areaWidth + pxWidth))
		.attr ("height", ui_mini_height * areaHeight / (areaHeight + pxHeight))
		.css ("left", ui_mini_width * (areaWidth / 2 - pxLeft) / (areaWidth + pxWidth))
		.css ("top", ui_mini_height * (areaHeight / 2 - pxTop) / (areaHeight + pxHeight));	
			
	$("#ui_map")
		.css ("width", ui_mini_width * pxWidth / (areaWidth + pxWidth))
		.css ("height", ui_mini_height * pxHeight / (areaHeight + pxHeight))
		.css ("margin-left", -parseInt ($("#ui_map").width()) / 2)
		.css ("margin-top", -parseInt ($("#ui_map").height()) / 2);
}

var ui_panel_displayed = false, ui_panel_last = -1;

function ui_panel_toggle (id, page)
{
    if (!ui_panel_displayed) {
        $("#ui_info_panel").animate ({height: $(window).height () - 220});
        ui_panel_displayed = true;
        
        $("#ui_info_panel").html ("<img id = 'popup_wait_img' src = 'img/wait.gif' />");
        
        ui_panel_last = id;
        
        $("#ui_info_panel").load (page);
    } else {    
        if (ui_panel_last == id) {
            $("#ui_info_panel").animate ({height: 0}).html ("");
            ui_panel_displayed = false;
        } else {
            ui_panel_displayed = false;
            $("#ui_info_panel").animate ({height: 0}, function ()
            {
                ui_panel_toggle (id, page);
            });
        }
    }
}


//Animated objects

var dt = 500;

var anim = [], numAnim = 0;

function initAnim (pt, type)
{	
	var id = "anim_" + (numAnim++);
	var elem = $("<img id = '" + id + "' class = 'anim' />");
		
	$("#tileHolder").prepend (elem);
		
	anim[id] = {"type": type, "id":id, "elem":elem, "x": pt.i, "y": pt.j, "ld":-1};
	anim[id].elem.css ("left"  , (baseX + (tileWidth  / 2) * pt.rj )).css ("bottom", (baseY + (tileHeight / 2) * pt.rj ))
}

function animate (id)
{
	var cx = anim[id].x, cy = anim[id].y;
	var kp = [], nkp = 0, canld = false, q = anim[id].ld;
	
	var dd = [
		{"x": -tileWidth / 2, "y": tileHeight / 2},{"x": tileWidth / 2, "y": tileHeight / 2},
		{"x": tileWidth / 2, "y": -tileHeight / 2},{"x": -tileWidth / 2, "y": -tileHeight / 2}
	];

	for (var i = 0; i < 4; ++i) {
		if (onMap (cx + dx[i], cy + dy[i]) && 1 == map[(cx + dx[i]) * rsY + cy + dy[i]].id && i != anim[id].ld) {
			kp[nkp++] = i;
			
			if (i == anim[id].ld) {
				canld = true;
				break;
			}
		}
	}
	if (nkp == 0 && anim[id].ld == -1) {
		anim[id].elem.remove ();
		delete anim[id];
		return;
	}
	
	if (anim[id].ld != -1 && nkp == 0) {
		q = anim[id].ld;
		if (1 != map[(cx + dx[q]) * rsY + cy + dy[q]].id) {
			anim[id].elem.remove ();
			delete anim[id];
			return;		
		}
	} else {
		q = kp[parseInt (Math.random () * 100) % nkp] % 4;
	}
	
	anim[id].ld = (q + 2) % 4;;
	anim[id].elem.attr ("src", "img/anim/" + anim[id].type + "_" + ((q + mapRot) % 4 )+ ".gif")	
	
	oz = parseInt ($("#x" + anim[id].x + "_" + anim[id].y).css ("z-index"));
	ox = anim[id].x, oy = anim[id].y;
	anim[id].x = nx = ox + dx[q];
	anim[id].y = ny = oy + dy[q];
	nz = parseInt ($("#x" + anim[id].x + "_" + anim[id].y).css ("z-index"));
		
	switch ((q + mapRot) % 4) {
		case 0: case 1:
			anim[id].elem.css ("z-index", oz);
			$("#x" + ox + "_" + oy).css ("z-index", oz - 1)
			break;
		case 2: case 3:
			anim[id].elem.css ("z-index", nz);
			$("#x" + nx + "_" + ny).css ("z-index", nz - 1)
			break;
	}
	
	anim[id].elem.animate ({left: "+=" + dd[(q + mapRot) % 4].x, bottom: "+=" + dd[(q + mapRot) % 4].y}, dt, 'linear', function ()
	{
		animate ($(this).attr ("id"));
	});
}