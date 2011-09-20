<?php
	include 'include/lang.php';
	include 'include/lib.php';
	include 'include/config.php';
	include 'include/db.php';
	include 'include/usr.class.php';
	@session_start ();
	
	if (!($usr = initUser())) {
		echo _("<h1>You cannot access this page!</h1>"); exit ();
	}
?>

<div id = "profile_title"><? echo _("Account management"); ?></div>

<div id = "profile_sidebar">
	<div id = "p_user"><? echo _("Change username"); ?></div>
	<div id = "p_pass"><? echo _("Change password"); ?></div>
	<div id = "p_addTown"><? echo _("Add town"); ?></div>
	<div id = "p_delTown"><? echo _("Delete town"); ?></div>
</div>
<div id = "profile_warning">
</div>
<div id = "profile_body">
    <? echo _("Choose an option from the left"); ?>
</div>
<script type = "text/javascript">
	function profile_warning (warning)
	{
			$("#profile_warning").html (warning);
			setTimeout ("$('#profile_bottombar').fadeOut ()" , 3000);
			return 0;
	}
    function query_finish (res)
    {
        if (res == "ok") {
			profile_warning ("<? echo _("Success! You'll be logged out in 2 seconds"); ?>");
            setTimeout ("logout_noConfirm ()", 2000);
        } else {
			profile_warning (res);
        }
    }

    function changeName ()
    {
        var newName = $("#newName").val ();

        if (newName.length >= 5 || 20 >= newName.length)
	        return profile_warning ("<? echo _("Username must have between 5 and 20 characters.");?>");
        
        $.ajax ({
            type: "POST",
            url: "accmngr.php",
            data: "cmd=changeName&newName=" + newName,
            success: query_finish
        });
    }
        
    function changePass()
    {
        var newPass = $("#newPassword").val ();
        var confNewPass = $("#confNewPassword").val ();
        var pass = $("#oldPassword").val ();
        
        if (newPass != confNewPass)
            return profile_warning ("<? echo _("Passwords do not match!");?>");
           
        if (newPass.length <= 5 || 20 < newPass.length)
            return profile_warning ("<? echo _("Password is too short!");?>");
             
             
        $.ajax ({
            type: "POST",
            url: "accmngr.php",
            data: "cmd=changePass&newPass=" + SHA256(newPass) + "&pass=" + SHA256(pass),
            success: query_finish
        });
    }
    
    function addTown ()
    {
	    var newTownName = $("#newTown").val ();
	    
	    if (newTownName.length > 20 && newTownName.length >= 5)
	        return profile_warning ("<? echo _("Town name must have between 6 and 20 characters.");?>");
	    
	    
	    $.ajax ({
	        type: "POST",
	        url: "accmngr.php",
	        data: "cmd=addtown&name=" + newTownName,
            success: query_finish
	    });
    }
    function deleteTown ()
    {
	    if($("#town_delete").val () == '0') {
		    profile_warning ("<? echo _("You should choose a town to delete!");?>");
		    return 0;
	    }
	    
	    $.ajax ({
	        type: "POST",
	        url: "accmngr.php",
	        data: "cmd=deleteTown&town=" + $("#town_delete").val (),
            success: query_finish
	    });
    }
    $("#p_user").click (function ()
    {
        $("#profile_body").html ("<? echo _("</div><span class = 'title'>Change Username:</span>\
				    <table><tr><td width = '200'>New Username:</td>\
				    <td><input type='text' id='newName' class='input_big' style = 'width:250px' size='20'/>\
				    </td></tr><tr><td colspan = '2' style = 'text-align:center'>\
				    <input type = 'button' onclick = 'changeName()' value = 'Change Username'/></td></tr></table>");?>").fadeIn ();
    });
    
    $("#p_pass").click (function ()
    {   
        $("#profile_body").html ("<? echo _("</div><span class = 'title'>Change Password:</span>\
			        <table>\<tr>\
			        <td width = '200'>Old password:</td><td><input type='password' id='oldPassword' class='input_big' style = 'width:250px' size='20'/>\
			        </td></tr><tr><td width = '200'>New Password:</td><td>\
			        <input type='password' id='newPassword' class='input_big' style = 'width:250px' size='20'/>\
			        </td></tr><tr><td width = '200'>Confirm new Password:</td><td>\
			        <input type='password' id='confNewPassword' class='input_big' style = 'width:250px' size='20'/>\
			        </td></tr><tr><td colspan = '2' style = 'text-align:center'>\
			        <input type = 'button' onclick = 'changePass()' value = 'Change Password'/></td></tr></table>");?>").fadeIn ();
    });
        
    $("#p_addTown").click (function ()
    {
        $("#profile_body").html ("<? echo _("<span class = 'title'>Add a Town:</span>\
				    <table><tr><td width = '200'>New Town Name</td><td>\
					<input type='text' id='newTown' class='input_big' style = 'width:250px' size='20'/></td>\
					</tr><tr><td colspan = '2' style = 'text-align:center'>\
					<input type = 'button' onclick = 'addTown()' value = 'Add Town'/>\
					</td></tr></table>");?>").fadeIn ();
    });
    
    $("#p_delTown").click (function ()
    {
        $("#profile_body").html ("<span class = 'title'><? echo _("Delete Town:");?></span>\
				<table style = 'width:100%'><tr><td width = '200'><? echo _("Town to delete:");?></td><td><select id = 'town_delete'>\
				<option value = '0'></option>\
					<?php
						foreach ($usr->towns as $town) {
							echo "<option value = '{$town->id}'>{$town->name}</option>";
						}
					?>\
				</select></td></tr><tr><td colspan = '2' style = 'text-align:center'>\
				<input type = 'button' onclick = 'deleteTown()' value = '<? echo _("Delete Town");?>'/></td></tr></table>").fadeIn ();
    });

</script>
<? @mysql_close (); ?>
