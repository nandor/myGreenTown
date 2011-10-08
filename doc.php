<?php
    include 'include/lang.php';
	include 'include/config.php';
	include 'include/db.php';
	include 'include/usr.class.php';
?>
<div>
    <div id = "doc_title"><?php echo __("Documentation"); ?> </div>
    <div id = "doc_sidebar">
		<div page = 'about.tex'><?php echo __("About the game"); ?></div>
		<div page = 'tutorial.tex'><?php echo __("Tutorial"); ?></div>
		<div page = 'stats.tex'><?php echo __("Building stats"); ?></div>
	</div>
    <div id = "doc_body">
    <?php
        include 'textile.php';
      
        echo processPage ('docs/about.tex');
    ?>    
    </div>
</div>
