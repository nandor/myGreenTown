/******************************************
	File: index.js
	Purpose: script for the main page
******************************************/

$(document).keyup (function (evt)
{
	if (evt.which == 13) {
		if ( $('#menu_login_user').val() != "" && $('#menu_login_pass').val() != "" && !loggedIn) {
			login ($("#menu_login_user").val (), $("#menu_login_pass").val (), $("#menu_login_rem:checked").val () != undefined);
		}  
	}
});

/***********************************
	Login
***********************************/

function menu_login ()
{
	canLogin = true;
	$("#menu_login").html (_(16) + _(15) + "<input id = 'menu_login_rem' type = 'checkbox'/>");
	
	$("#menu_tselect"). html (_(17));
				
	$("#menu_login_login").click (function ()
	{
		login ($("#menu_login_user").val (), $("#menu_login_pass").val (), $("#menu_login_rem:checked").val () != undefined);
	});
}

var ctown;

function menu_welcome ()
{
	ctown = 0;
	loggedIn = true;
	$("#menu_login").html ("<p class = 'title'>" + _(12) +", " + user.name + "!</p>\
				<div id = 'login_enter'>\
					<p>" + _(13) + "</p>\
					<input class = 'login_button' type = 'button' value = 'Log out' />\
				</div>");
	
	$("#menu_tselect").html ("<div class = 'menu_title'>" + _(11) + "</div><img id = 'tleft' src = 'img/tleft.png'/><img id='tright'  src = 'img/tright.png' /><div id = 'town_holder'><div id = 'tscroll'></div></div><input type = 'button' id = 'enter' value = '" + _(14) + "'/>");
				  
	for (var i = 0; i < user.numTowns; ++i) {
		$("#tscroll").append ("<div id = 't_" + i + "'>" + user.towns[i].name + "<br /><img src = 'minimap.php?town=" + user.towns[i].id + "'</div>");
	}	
	
	$("#tscroll").css ("width", user.numTowns * 193);
	
	if (ctown > 0) {$("#tleft").show ();} else {$("#tleft").hide ();}
	if (ctown < user.numTowns - 1) {$("#tright").show ();} else {$("#tright").hide ();}
		
	$("#tleft").click (function ()
	{
		if (ctown > 0) {
			ctown --;
			$("#tscroll").animate ({left: "+=190px"}, 'fast', function ()
			{
				if (ctown > 0) {$("#tleft").show ();} else {$("#tleft").hide ();}
				if (ctown < user.numTowns - 1) {$("#tright").show ();} else {$("#tright").hide ();}
			});
		}
	});
	
	$("#tright").click (function ()
	{
		if (ctown < user.numTowns - 1) {
			ctown ++;
			$("#tscroll").animate ({left: "-=190px"}, 'fast', function ()
			{
				if (ctown > 0) {$("#tleft").show ();} else {$("#tleft").hide ();}
				if (ctown < user.numTowns - 1) {$("#tright").show ();} else {$("#tright").hide ();}
			});
		}
	});
	
	$("#enter").click (function ()
	{
		window.location.replace ("game.php?id=" + user.towns[ctown].id);
	});
	
	$("#t_0").show ();
	
	//Log out
	$("#menu_login input:last").click (function()
	{
		$("#menu_login").html ("<br /><br />" + _(10) + "<br /><img src = 'img/wait.gif' />");
		$.ajax ({
			type: "GET",
			url: "query.php",
			data: "cmd=logout",
			success: function (response)
			{
				if (response != "0") {
					loggedIn = false;
					menu_login ();
				}
			}	
		});
	});
}

function menu_login_warning (msg)
{
	$("#menu_login").html ("<div id = 'menu_login_msg'>" + msg + "<input type='button' value = 'Retry'/></div>");
				
	$("#menu_login_msg input").click (function ()
	{
		menu_login ();
	});
}


function login (userName, pass, rem)
{
	if (!canLogin) {
		return;
	}
	canLogin = false;
	$("#menu_login").html ("<br /><br />" + _(9) + "<br /><img src = 'img/wait.gif' />");
	
	if (user == "" || pass == "") {
		menu_login_warning (_(8));
	} else {	
		$.ajax ({
			type: 'POST',
			url:  'login.php',
			data: "rem=" + rem + "&user=" + userName + "&pass=" + SHA256(pass),
			success: function (response)
			{
				switch (response) {
					case '0':
						menu_login_warning (_(6));
						break;
					case '1':
						menu_login_warning (_(7));
						break;
					default:
						if (user = JSON.parse (response).user) {
							menu_welcome ();
						} else {
							menu_login_warning (_(6));
						}
						break;
				}
			}
		});
	}
}


/***********************************
	Register
***********************************/

function register ()
{
	var name     = $("#input_name").val ();
	var pass     = $("#input_pass").val ();
	var confirm  = $("#input_conf").val ();
	var captcha  = $("#captcha").val ();
	
	if (name.length < 5 || 20 < name.length ) {
	    return register_warning (_(1));
	}
	
    if (pass.length < 5)
        return register_warning (_(2));
        
	if (pass != confirm)
	    return register_warning (_(3));
	    
	if (captcha == "")
		return register_warning (_(4));
			
	$.ajax ({
	    type: "POST",
	    url: "accmngr.php",
	    data: "cmd=register&name=" + name + "&pass=" + SHA256 (pass) + "&captcha=" + captcha,
	    success: function (res)
	    {
	        if (res != 'ok') {
	            register_warning (res);
	        } else {
				$("#register_window").html (_(5));
	        }
	    }
	});
}

function register_warning (msg)
{
	$("#register_warning").text(msg).show ();
}


$("#doc_sidebar div").live ('click', function () 
{
    $("#doc_body").html ("<img id = 'popup_wait_img' src = 'img/wait.gif' />");
    $.ajax ({
        type: "GET",
        url: "textile.php",
        data: "page=" + $(this).attr ("page"),
        success: function (res) 
        {
            $("#doc_body").html (res).hide ().fadeIn ();
        }
    });
});

var pwidth = 10, pheight = 10;
var tileWidth = 120, tileHeight = 60;

var map = 
[
    [ "4_0_0", "2_0_0" ,"1_0_2" , "2_1_0", "2_0_0","1_0_2" , "17_0_15","17_0_15","1_0_2" ,"3_0_0"],
    [ "2_0_0", "2_0_0" ,"1_0_10", "2_2_0", "2_3_0","1_0_10","12_0_0","17_0_15","1_0_10","3_0_0"],
    [ "1_0_4", "1_0_5" ,"1_0_15", "1_0_5", "1_0_5","1_0_15", "1_0_5","1_0_5","1_0_15","1_0_1"],
    ["17_0_15","17_0_15" ,"1_0_10", "8_0_0","14_0_0","1_0_10", "17_0_15","17_0_15","1_0_10","3_0_0"],
    ["17_0_15","17_0_15" ,"1_0_10","11_0_0","15_0_0","1_0_10", "18_0_0","4_0_0","1_0_10","3_0_0"],
    [ "1_0_4", "1_0_5" ,"1_0_15", "1_0_5", "1_0_5","1_0_15", "1_0_5","1_0_5","1_0_15","1_0_1"],
    ["16_0_0","17_0_15" ,"1_0_10", "2_1_0", "2_4_0","1_0_10", "5_0_0","5_0_0","1_0_10","6_0_0"],
    ["16_0_0","17_0_15" ,"1_0_10", "2_3_0", "9_0_0","1_0_10", "5_0_0","5_0_0","1_0_10","6_0_0"],
    [ "1_0_4", "1_0_5" ,"1_0_15", "1_0_5", "1_0_5","1_0_15", "1_0_5","1_0_5","1_0_15","1_0_1"],
    ["21_0_0","22_0_0" ,"1_0_8" , "23_0_0", "24_0_0","1_0_8" , "4_0_0","4_0_0","1_0_8" ,"7_0_0"],
]    

var lastPage = null, pageShown = false;

$(document).ready (function (){
	
	$("#page_ctnt").css ("visibility", "visible");
	
	$("#page_ctnt").hide ();	
	
	$("#gamePreview").css  ({
		width       : (pwidth + pheight) * tileWidth / 2,
		height      : (pwidth + pheight) * tileHeight / 2,
		marginLeft  : -(pwidth + pheight) * tileWidth / 4,
		marginTop   : -(pwidth + pheight) * tileHeight / 4		
	});

	baseX = 0, baseY = (pheight - 1)* tileHeight / 2;
	
	for (var i = 0; i < pwidth; ++i) {
		for (var j = pheight - 1; j >= 0; --j) {
			$("#gamePreview").append ("<img style = 'left:" + (baseX + (tileWidth / 2) * j) + "px;bottom:" + (baseY + (tileHeight / 2) * j) + "px;' src = 'img/tile/" + map[i][j] + ".gif' />");
		}
		
		baseX += tileWidth / 2;
		baseY -= tileHeight / 2;
	}	
	
	$("#gamePreview img").fadeIn (750);
	
	$("#return").hide ();
	
	$("#toolbar img[alt]").click (function ()
	{
		if ($(this).attr ("alt") == "en") {
            window.location.replace ("?lang=en");
            return;
        }
		if ($(this).attr ("alt") == "ro") {
            window.location.replace ("?lang=ro");
            return;
        }
		if ($(this).attr ("alt") == "forum") {
            window.location.replace ("forum");
            return;
		}
		if ($(this).attr ("alt") == "ret") {
			$("#return").hide ();
			$(".lang").show ();
			$("#page_ctnt").hide ();
			pageShown = false;
			return;
		}
		loadPage ($(this).attr ("alt"))
	});
});

function loadPage (page)
{
	if (!pageShown) {
		$("#page_ctnt").html ("<img id = 'popup_wait_img' src = 'img/wait.gif' />").load (lastPage = page);
		$("#page_ctnt").show ();
		$("#return").show ();
		$(".lang").hide ();
		pageShown = true;
	} else {
		if (lastPage == page) {
			$("#return").hide ();
			$(".lang").show ();
			$("#page_ctnt").hide ();
			pageShown = false;
		} else {
			pageShown = false;
			loadPage (page);
		}
	} 
}
