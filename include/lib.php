<?php
    /**
        @file lib.php
        @author Licker Nandor
    */

    /**
        Checks if a username is valid
        
        @param string $str          Name to check
                
        @return boolean             Returns true if name is valid, othervise false
    */
    
	function valid_name ($str, $checkLen = true)
	{
		$len = strlen ($str);
		
		
		if ($checkLen && ($len <= 5 || MAXNAMELENGTH < $len))
			return false;
		
		for ($i = 0; $i < $len; $i++)
			if (strpos (INVALID_CHAR, $str[$i]) != false || $str[$i] == '"' || $str[$i] == "'") 		
				return false;
		
		$Foul = @file("include/badwords.txt"); 
				
		foreach ($Foul as $FoulWord)
		{
			$FoulWord = trim($FoulWord);
			
			if (preg_match("/".$FoulWord."/i", $str))
			{
				return false;
			} 
			
		}
		return true;	
	}
?>
