<?php
    include 'include/lang.php';
	include 'include/config.php';
	include 'include/db.php';
	include 'include/usr.class.php';
	include 'include/textile.class.php';
	
	if (!($usr = initUser())) {
	    echo _("<h4>You cannot access this page!");
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
    
    $(this).attr ("value", $("#mail_compose").is (":visible") ? "<? echo _("View Mail");?>" : "<? echo _("New mail");?>");
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
        $("#mail_res").html ("<? echo _("You must enter the recipient!");?>");
        return;
    }
    if ($("#title").attr ("value") == "") {
        $("#mail_res").html ("<? echo _("You must enter a title!");?>");
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
    <?php echo _("Mail"); ?>
</div>

<div id = "mail_body">
<?php
$db = mysql_query ("SELECT * FROM mail WHERE `to` = '{$_SESSION['town']}' ORDER BY `date` DESC;");

$textile = new Textile();
while ($msg = mysql_fetch_array ($db)) {
    echo "<div class = 'mail'><div class = 'title'>{$msg['title']} <span class = 'from'> "._("from")." {$msg['from']}</span></div><div class = 'date'>{$msg['date']}</div><div class = 'msg'>{$textile->TextileThis ($msg['text'])}</div></div>";
}

?>
</div>
<div id = "mail_compose">
    <table>
        <tr>
            <td><? echo _("To");?></td>
            <td><input id = "to" type = "text" /></td>
        </tr>
        <tr>
            <td><? echo _("Title");?></td>
            <td><input id = "title" type = "text" /></td>
        </tr>
        </tr>
            <td colspan = "2">
                <input type = "button" id = "send" value = "<?php echo _("Send");?>"/>
                <input type = "button" id = "clear" value = "<?php echo _("Clear");?>"/>
                <span id = "mail_res"></span>
            </td>
        </tr>
    </table>  
    <div id = "txt_holder">
        <textarea id = "txt"></textarea>
    </div>
</div>

<div id = "mail_options">
    <input id = "mail_toggle" type = "button" value = "<? echo _("New mail");?>" />
</div>
