<?php
    include 'include/lang.php';
	include 'include/config.php';
	include 'include/db.php';
	include 'include/usr.class.php';
?>
<center id ="register_window">
	<h1><?php echo __("Register"); ?></h1>
	<div id = "register_warning"><?php echo __("hello"); ?></div>
	<table>
		<tr>
			<td><?php echo __("Nickname:"); ?></td>
			<td><input type = "text" id = "input_name" class = "input_big" /></td>
		</tr>
		<tr>
			<td><?php echo __("Password:"); ?></td>
			<td><input type = "password" id = "input_pass" class = "input_big" /></td>
		</tr>
		<tr>
			<td><?php echo __("Confirm Password:"); ?></td>
			<td><input type = "password" id = "input_conf" class = "input_big" /></td>
		</tr>
		<tr>
			<td>
				<img src = "captcha.php" style = 'cursor:pointer' id = "captcha_img" onclick="$('#captcha_img').attr('src', 'captcha.php?' + Math.random());"/>
			</td>
			<td><?php echo __("Enter the code from the image"); ?><br /><input type = "text" id = "captcha" class = "input_big" /></td>
		</tr>
	</table>
	<input type = 'button' value = '<?php echo __("Register");?>' class = "button_register" onclick = "register()"/>
	<script type = "text/javascript">
		$("#register_warning").hide ();
	</script>
</center>
