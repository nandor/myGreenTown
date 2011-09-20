<?php
    include 'include/lang.php';
    include 'include/config.php';
    include 'include/db.php';
    
    // Retrieve the number of existing towns
	$db_res   = mysql_get ("SELECT COUNT(`id`) FROM `town`");
	$numTowns = intval($db_res['COUNT(`id`)']);
?>
<div id = "ranking">
    <script type = "text/javascript">
        function loadFrom (page) {
	        newItemsPerPage = parseInt (($("#rank_body").height () - 50) / 22 - 1);
	       
	        $.getJSON ("getrank.php?pos=" + (itemsPerPage * page) + "&cnt=" + newItemsPerPage, function (json)
	        {
	            $("#rank_table").html ('<tr><th style = "width: 60px;"><? echo _("Rank");?></th><th style = "width: 270px;"><? echo _("Name");?></th><th><? echo _("Score");?></th></tr>');
	            for (var i = 0; i < json.length; ++i) {
	                $("#rank_table").append ("<tr onclick = 'loadTown (" + json[i].id + ")'><td>" + (itemsPerPage * page + i + 1) + "</td><td>" + json[i].name + "</td><td tid = " + json[i].id + ">" + json[i].score + "</td></tr>");					
	            }
	        });
	        
	        page = parseInt (page * itemsPerPage / newItemsPerPage);
	        numPage = parseInt (numItems / newItemsPerPage) + 1;
	        $("#rank_control").html ("");
	        	        
			for (var i = 1; i <= min (3, numPage); ++i) {
			    $("#rank_control").append ("<span id = '" + (i - 1) + "'>" + i + "</span>");
			}
			
			if (page > 6)
			    $("#rank_control").append ("...");
			
			for (var i = max (4, page - 2); i < max (min (page + 2, numPage - 2), 1); ++i)
			    $("#rank_control").append ("<span id = '" + (i - 1) + "'>" + i + "</span>");
			    
			if (page + 2 < numPage - 2)
			    $("#rank_control").append ("...");
			
			for (var i = max (numPage - 2, 4); i <= numPage; ++i)
			    $("#rank_control").append ("<span id = '" + (i - 1) + "'>" + i + "</span>");
			    
	        itemsPerPage = newItemsPerPage;
	        $("#" + page).addClass ("curPage");
        }
       
       
        var loading = false;       
        function loadTown (town)
        {			
            if (loading)
                return;
                
            loading = true;
            var dx = new Array (-1, 0, 1,  0), dy = new Array (0, 1, 0, -1);
      	
      	    $("#rank_wait").show ();
      	    $("#rank_town_holder").html ("");
      	
            $.getJSON ("query.php?cmd=getTown&town=" + town, function (json) {	
                loading = false;	
                $("#rank_wait").hide ();
	            sizeX = json.sizeX;
	            sizeY = json.sizeY;
	            
	            pxWidth = ((sizeX + sizeY) * tileWidth) / 2
	            pxHeight = ((sizeX + sizeY) * tileHeight) / 2
	            
	            $("#rank_town_holder").css ("top", pxHeight / 2 + 200).css ("left", 400).html ("");

	            baseX = 0, baseY = pxHeight - (sizeY + 1) * tileHeight / 2;
	
	            for (var i = 0; i < sizeX; i++) {
	                for (var j = sizeY - 1; j >= 0; j--) {	
	                
                        var idx = i * sizeY + j;
                        			
                        $("#rank_town_holder").append ("<img id = 'x" + i + "_" + j + "' style = 'left:" + (baseX + (tileWidth / 2) * j) + "px;bottom:" + (baseY + (tileHeight / 2) * j)+ "px;z-index:" + ((sizeY - j + 1) * 100000 + i * 100000) + "'/>");
	                }
		            baseX += tileWidth / 2;
		            baseY -= tileHeight / 2;
	            }
	
	            baseX = 0, baseY = pxHeight - (sizeY + 1) * tileHeight / 2;
		
	            for (var i = 0; i < sizeX; ++i) {
	                for (var j = 0; j < sizeY; ++j) {
                        var idx = i * sizeY + j;
	                     	  
	                    var tile = 0;
	
	                    if (json.map[idx] == 1 || json.map[idx] == 17) {
		                    for (var k = 0; k < 4; ++k) {	
			                    var nx = i + dx[k], ny = j + dy[k], nidx = nx * sizeY + ny;

			                    if (0 <= nx && nx <= sizeX && 0 <= ny && ny <= sizeY) {
				                    if (json.map[nidx] == json.map[idx]) {
					                    tile |= 1 << (7 - k) % 4; 
				                    }
			                    }
		                    }
	                    }
	                    
		                $("#x" + i + "_" + j).attr ("src", "img/tile/" +  json.map[idx] + "_0_" + tile + ".gif");
                    }
	            }
            });
        }
        
        var sx = 0, sy = 0;
        
        $("#rank_town_holder").mousedown (function (evt) {
            $(this).css ("cursor", "move");
            evt.preventDefault ();
            evt.stopPropagation ();
            
            sx = evt.pageX;
            sy = evt.pageY;
            
            $("#rank_town_holder").bind ('mousemove.drag', function (evt) {
                $("#rank_town_holder").css ("left", parseInt ($("#rank_town_holder").css ("left")) + evt.pageX - sx);
                $("#rank_town_holder").css ("top" , parseInt ($("#rank_town_holder").css ("top" )) + evt.pageY - sy);
                sx = evt.pageX;
                sy = evt.pageY;
            })
        }).bind ('mouseup.drag', function () {
            $("#rank_town_holder").unbind ('mousemove.drag');
            $(this).css ("cursor", "default");
        });
        
        $("#town_body").bind ('mouseleave', function () {
            $("#rank_town_holder").unbind ('mousemove.drag');
            $("#rank_town_holder").css ("cursor", "default");
        });
        
        $("#rank_control span").live ('click', function ()
	    {
	        var pos = $(this).attr ("id");
	        
	        loadFrom (pos); 
	    });
	    
			
		<? echo "numItems={$numTowns};\n"; ?>
	    itemsPerPage = 0;
	    loadFrom (0);
	    loadTown (1);
    </script>

    <div id = "rank_title"><?php echo _("Ranking"); ?></div>
    <div id = "rank_body">
		<table id = "rank_table">
        </table>
		<div id = "rank_control">
		</div>
    </div>
    <div id = "town_body">
        <img id = "rank_wait" src = "img/wait.gif" />
        <div id = "rank_town_holder"></div>
    </div>
</div>

