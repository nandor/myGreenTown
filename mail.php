<?php
    include 'include/lang.php';
	include 'include/config.php';
	include 'include/db.php';
	include 'include/usr.class.php';
	include 'include/textile.class.php';
	
	if (!($usr = initUser())) {
	    echo __("<h4>You cannot access this page!");
	    exit (0);
	}    
?>
<script type = "text/javascript">
$(".mail .msg").hide ();
$("#mail_compose").hide ();

$(".mail .title").click (function ()
{
    $(".msg", $(this).parent ()).toggle ();
});
$("#mail_toggle").click (function ()
{
    $("#mail_body").toggle ();
    $("#mail_compose").toggle ();
    
    $(this).attr ("value", $("#mail_compose").is (":visible") ? "<? echo __("View Mail");?>" : "<? echo __("New mail");?>");
});

$("#clear").click (function ()
{
    $("#title").attr ("value", "");
    $("#to").attr ("value", "");
    $("#txt").attr ("value", "");
});

$("#send").click (function ()
{
    if ($("#to").attr ("value") == "") {
        $("#mail_res").html ("<? echo __("You must enter the recipient!");?>");
        return;
    }
    if ($("#title").attr ("value") == "") {
        $("#mail_res").html ("<? echo __("You must enter a title!");?>");
        return;
    }
    $.ajax ({
        url: "query.php",
        type: "POST",
        data: "cmd=sendmail&title=" + $("#title").attr ("value") + "&to=" + $("#to").attr ("value") + "&txt=" + $("#txt").attr ("value"),
        success: function (response)
        {
            $("#mail_res").html (response);
        }
    });
});
</script>
<div id = "mail_title">
    <?php echo __("Mail"); ?>
</div>

<div id = "mail_body">
<?php
$db = mysql_query ("SELECT * FROM mail WHERE `to` = '{$_SESSION['town']}' ORDER BY `date` DESC;");

$textile = new Textile();
while ($msg = mysql_fetch_array ($db)) {
    echo "<div class = 'mail'><div class = 'title'>{$msg['title']} <span class = 'from'> ".__("from")." {$msg['from']}</span></div><div class = 'date'>{$msg['date']}</div><div class = 'msg'>{$textile->TextileThis ($msg['text'])}</div></div>";
}

?>
</div>
<div id = "mail_compose">
    <table>
        <tr>
            <td><? echo __("To");?></td>
            <td><input id = "to" type = "text" /></td>
        </tr>
        <tr>
            <td><? echo __("Title");?></td>
            <td><input id = "title" type = "text" /></td>
        </tr>
        </tr>
            <td colspan = "2">
                <input type = "button" id = "send" value = "<?php echo __("Send");?>"/>
                <input type = "button" id = "clear" value = "<?php echo __("Clear");?>"/>
                <span id = "mail_res"></span>
            </td>
        </tr>
    </table>  
    <div id = "txt_holder">
        <textarea id = "txt"></textarea>
    </div>
</div>

<div id = "mail_options">
    <input id = "mail_toggle" type = "button" value = "<? echo __("New mail");?>" />
</div>
