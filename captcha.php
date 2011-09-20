<?php

	function randStr ()
	{
		$str = "1234567";
		
		for ($i = 0; $i < 7; $i++) {
			switch (rand (1, 3)) {
				case 1:
					$str[$i] = chr(rand (ord('a'), ord('z')));
					break;
				case 2:					
					$str[$i] = chr(rand (ord('A'), ord('Z')));
					break;
				case 3:					
					$str[$i] = chr(rand (ord('0'), ord('9')));
					break;
			}
		}
		return $str;
	}

	define ('NUM_LINE', 5);

	header ("Content-type: image/png");
	@session_start();
	
	$img = imagecreatetruecolor (150, 60);
	
	$black = imagecolorallocate($img, 0, 0, 0);
	$white = imagecolorallocate($img, 255, 255, 255);
	$red   = imagecolorallocate($img, 255, 0, 0);
	$green = imagecolorallocate($img, 0, 0, 255);
	$blue  = imagecolorallocate($img, 0, 255, 0);
	
	$color = array($black, $red, $green, $white, $blue);
		
	imagefill ($img, 0, 0, $white);	
		
	imagefttext ($img, 20, 0, 20, 40, $black, "./FreeSans.ttf", $str = randStr());
	
	for ($i = 0; $i < NUM_LINE; $i++) {
		imageline ($img, rand (1, 40), rand(1, 60), rand (110, 150), rand (1, 60), $color[rand(0, 4)]);
	}
	
	$_SESSION['captcha'] = $str;
	
	imagepng ($img);
	imagedestroy ($img);
?>

