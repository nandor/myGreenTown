<?php
	include 'include/lang.php';
	include 'include/lib.php';
	include 'include/config.php';
	include 'include/building.php';
	include 'include/town.class.php';
	include 'include/db.php';
		
	header("Content-type: image/png");
	header("Expires: Mon, 01 Jul 2003 00:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	
	$town = new Town ($_GET['town']);
	
	
	$mapRot = (isset ($_GET['rot'])) ? $_GET['rot'] : 0;
		
	$tileWidth = 20;
	$tileHeight = 10;
	
	if ($mapRot & 1) {
		$x = $town->sizeY; $y = $town->sizeX;
	} else {
		$x = $town->sizeX; $y = $town->sizeY;
	}
		
	$width  = ($x + $y) * $tileWidth / 2;
	$height = ($x + $y) * $tileHeight / 2;
		
	$img = imagecreatetruecolor($width, $height);
	imagesavealpha($img, true);
	imagefill($img, 0, 0, imagecolorallocatealpha ($img, 0, 0, 0, 127));
	
	
	$black 	= imagecolorallocate($img, 0, 0, 0);
	$white 	= imagecolorallocate($img, 255, 255, 255);
	$red   	= imagecolorallocate($img, 255, 0, 0);
	$green 	= imagecolorallocate($img, 0, 255, 0);
	$purple = imagecolorallocate($img, 255, 0, 255);
	$gray	= imagecolorallocate($img, 200, 200, 200);
	$yellow = imagecolorallocate($img, 255, 255, 102);
	$orange = imagecolorallocate($img, 255, 165, 0);
	$white  = imagecolorallocate($img, 255, 255, 255);
	$dgreen = imagecolorallocate($img, 0, 200, 0);
	$blue	= imagecolorallocate($img, 0, 0, 255);
	$color = array ($green, $gray, $purple, $yellow, $red, $orange, $blue, $dgreen);
	
	$baseX = 0;
	$baseY = $y * $tileHeight / 2;
				
	for ($ii = 0; $ii < $x; $ii++) {
		for ($jj = 0; $jj < $y; $jj++) {
		
			switch ($mapRot) {
				case 0: $i = $ii;	   $j = $jj		;break;
				case 1: $i = $y - $jj - 1; $j = $ii		;break;
				case 2: $i = $x - $ii - 1; $j = $y - $jj - 1	;break;
				case 3: $i = $jj;	   $j = $x - $ii - 1	;break;
			}
						
			$bld = $buildings[$town->map[$i * $town->sizeY + $j]];
			
						
			$pts = array(	$baseX + $tileWidth / 2 * $jj 			, $baseY - $tileHeight / 2 * $jj, 
					$baseX + $tileWidth / 2 * $jj + $tileWidth / 2	, $baseY - $tileHeight / 2 * $jj - $tileHeight / 2, 
					$baseX + $tileWidth / 2 * $jj + $tileWidth		, $baseY - $tileHeight / 2 * $jj,
					$baseX + $tileWidth / 2 * $jj + $tileWidth / 2	, $baseY - $tileHeight / 2 * $jj + $tileHeight /2);
					
			imagefilledpolygon($img, $pts, 4, $color[$bld->type]);
		}
		
		$baseX += $tileWidth / 2;
		$baseY += $tileHeight / 2;
	}

	imagepng($img);
	imagedestroy($img);
?>
