/*
	File: lib.js
	Purpose: common functions used all over the game
*/

$(document).keyup (function (evt)
{
	if (evt.which == 13) {		
		if (confirm_being_displayed) {
			$("#confirm_frame").remove ();
			if (onCContinue != null) {
				onCContinue ();
			}
			confirm_being_displayed = false;
		}
	}
});


function min (a, b) {return (a < b) ? a : b;}
function max (a, b) {return (a > b) ? a : b;}


function getAjax ()
{
	return new XMLHttpRequest ();
}
/***************************************************************************************************
	Warning and wait boxes
***************************************************************************************************/

var warning_being_displayed = false, wait_being_displayed = false, confirm_being_displayed = false, onWContinue = null, onCContinue;

function confirm (msg, onContinue) 
{
	if (!confirm_being_displayed) {
		confirm_being_displayed = true;
		onCContinue = onContinue;
		$(document.body)
			.append ("<div id = 'confirm_frame'>\
					<div class = 'msg_msgBox'>\
						<div class = 'msg_message'>\
							<br /><br />" + msg + "\
						</div>\
						<input type = 'button' value = 'Continue' class = 'msg_btn' style = 'left:40%;'/>\
						<input type = 'button' value = 'Return'   class = 'msg_btn' style = 'left:70%;'/>\
					</div>\
				  </div>");
			  
		$("#confirm_frame input:first")
			.click (function ()
			{
				$("#confirm_frame").remove ();
				if (onCContinue != null) {
					onCContinue ();
				}
				confirm_being_displayed = false;
			});
		$("#confirm_frame input:last")
			.click (function ()
			{
				$("#confirm_frame").remove ();
				confirm_being_displayed = false;
			});
		
	}
}


function warning (msg, onContinue) 
{
	wait_end ();
	if (!warning_being_displayed) {
		warning_being_displayed = true;
		onWContinue = onContinue;
		$(document.body)
			.append ("<div id = 'warning_frame'>\
					<div class = 'msg_msgBox'>\
						<div class = 'msg_message'>\
							<br /><br />" + msg + "\
						</div>\
						<input type = 'button' value = 'Continue' class = 'msg_btn'/>\
					</div>\
				  </div>");
		$("#warning_frame input")
			.click (function ()
			{
				$("#warning_frame").remove ();
				if (onWContinue != null) {
					onWContinue ();
				}
				warning_being_displayed = false;
			});
		
	}
	return 0;
}

var wait_message = new Array ();
wait_message[0] = "You need energy to sustain your buildings, make sure you have enough power plants!";
wait_message[1] = "Buildings produce waste, make sure you recicle or use it efficiently!";
wait_message[2] = "The ammount of waste produced is deducted from your score.";
wait_message[3] = "You can improve the buildings by upgrading them.";
wait_message[4] = "Try to complete tasks in order to get started!";
wait_message[5] = "If you don't have enough workers, you loose income. If you have too many, your score is lower.";
wait_message[6] = "Try to find the perfect balance between ecology and efficiency!";
wait_message[7] = "You can start a new town using the profile interface.";

var numMessage = 8;

function wait_begin () 
{	
	if (!wait_being_displayed) {
		wait_being_displayed = true;
		$(document.body)
			.append ("<div id = 'msg_frame'><div class = 'msg_msgBox'><div class = 'msg_message'>\
							<span><b>Hint:</b>" + wait_message [max(0, parseInt (Math.random () * 100) % numMessage)]+ "</span><br />\
							<img src = 'img/wait.gif' /><br /></div></div></div>");

	}
}

function wait_end () 
{
	if (wait_being_displayed) {
		$("#msg_frame").remove ();
		wait_being_displayed = false;
	}
}

/***************************************************************************************************
	ajaxQuery - send a query to the server
	
	Parameters:
		page: the page you want to load
		callback: a function to be called when the query is finished
		rnd: append a &rnd to the end if it's false or null
***************************************************************************************************/

function ajaxQuery (page, callback, rnd) 
{
	var req = getAjax ();
	
	req._callback = callback;
	req.open ("GET", page + ((!rnd) ? "&rnd=" + Math.floor(Math.random() * 1000) : ""), true);
	wait_begin ();
	
	req.onreadystatechange = function ()
	{	
		if (this.readyState == "4" && this.status == 200) {
			wait_end ();
			req._callback ();
		}
	}
	
	req.send ();
	return req;
}

/***************************************************************************************************
	Logging out
***************************************************************************************************/

function logout () {
	confirm ("Do you really want to log out?", function () {
		ajaxQuery ("query.php?cmd=logout", function ()
		{
			if (this.responseText != "0") {
				window.location.replace ('/eco');
			}	
		});
	});
}

function logout_noConfirm() {
    $.ajax ({
        type: "GET",
        url: "query.php",
        data: "cmd=logout",
        success: function (res)
        {
            if (res != "0") {
                window.location.replace (domain);
            }   
        }
    });
}


function initTooltips () {
    $("[tooltip]")
        .live ('mouseover', function (evt)
        {
            $("body").append ("<div id = 'tooltip'><img src = 'img/tt.png'/>" + $(this).attr ("tooltip") + "</div>");
                        
            $("#tooltip").css ({right: $(window).width () - evt.pageX - 90, top: evt.pageY + 5})            
            $(this)
                .bind ('mousemove.tooltip', function (evt)
                {
                    $("#tooltip").css ({right: $(window).width () - evt.pageX , top: evt.pageY + 5})
                })
                .bind ('mousedown', function (evt) {
                    $("#tooltip").remove ();
                })
                .css ("cursor", "pointer");
                
        })
        .live ('mouseout mouseleave', function ()
        {  
            $("#tooltip").remove ();
        });
}
