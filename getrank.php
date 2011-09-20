<?php
    /**
        getrank.php - Retrieve a list of towns for the ranking
        
        This file is part of myGreenTown
        (c) 2011 Licker Nandor
    */
    include ('include/config.php');
    include ('include/db.php');
    
    header ("Content-type: application/json");
    
    if (!isset ($_GET['pos']) || !isset ($_GET['cnt'])) {
        echo "[]"; exit (0);
    }
    
    $pos = $_GET['pos'];
    $cnt = $_GET['cnt'];
    
    // Retrieve the number of existing towns
	$db_res   = mysql_get ("SELECT COUNT(`id`) FROM `town`");
	$numTowns = intval($db_res['COUNT(`id`)']);
	
	if ($pos >= $numTowns) {
	    echo "[]"; exit (0);
    }
    
    // Retrieve the towns from the db
    $db = mysql_query ("SELECT `id`,`name`,`score` FROM `town` ORDER BY `score` DESC LIMIT  {$pos}, {$cnt};");
    
    if (!$db) {
        echo "[]"; exit (0);   
    } 
    
    // Form the result JSON   
    $result = array ();
    $i = 0;
    while ($town = mysql_fetch_array ($db)) {
        $result[$i]['id']    = $town['id'];
        $result[$i]['name']  = $town['name'];
        $result[$i]['score'] = $town['score'];
        $i++;
    }
    
    echo json_encode ($result);
?>
