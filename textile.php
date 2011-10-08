<?php

include 'include/textile.class.php';

function processPage ($page)
{      
    $textile = new Textile();

    $handle = @fopen($page, "r");
    
    if ($handle == False) {
        return __("<center><h1>Error:This item is not documented!</h1></center>");   
    }
    
    $contents = fread($handle, filesize($page));
    fclose($handle);
    return "<div class = 'textile'>{$textile->TextileThis($contents)}</div>";
    
}


if (isset ($_GET['page'])) {
    echo processPage ("docs/{$_GET['page']}");
}
?>
