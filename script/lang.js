/**
    Translation module
    This script is part of myGreenTown
    
    Author: Licker Nandor
*/

var strings = {
    "ro" : {
        "1": "Numele trebuie sa fie între 6 si 20 de caractere!",
        "2": "Parola trebuie sa aibă o lungime de cel puţin 6 caractere!",
        "3": "Parolele nu se potrivesc",
        "4": "Trebuie să introduceți codul de verificare!",
        "5": "<h1>Întregistrare reuşită</h1>",
        "6": "Autentificare eşuată!",
        "7": "Numele de utilizator şi parola nu se potrivesc!",
        "8": "Trebuie să introduceţi un nume şi o parolă!",
        "9": "Autentificare în curs...",
        "10": "Delogare în curs...",
        "11": "Selectarea oraşului",
        "12": "Bine ai venit",
        "13": "Selectaţi oraşul din dreapta sau delogaţi-vă",
        "14": "Selectează",
        "15": "Rămâneţi autentificat",
        "16":  "<div class = 'menu_title'>Autentificare</div>\
				<div class = 'menu_login_subtitle'>Nume</div>\
				<input id  = 'menu_login_user' 	type = 'text'/>\
				<div class = 'menu_login_subtitle'>Parolă</div>\
				<input id  = 'menu_login_pass' 	type = 'password'/><br />\
				<input id  = 'menu_login_login' type = 'button' value = 'Login'/><br />",
		"17": "<p>Pentru a vă juca, trebuie să vă creaţi un cont şi să vă autentificaţi!<br /><br />Înainte de a vă juca, puteți consulta ghidul începătorului!</p>"
    },
    "en" : {
        "1": "Nickname must be between 6 and 20 characters!",
        "2": "Password must be at least 6 characters long!",
        "3": "Passwords do not match!",
        "4": "You must enter the verification code!",
        "5": "<h1>Registration successful!</h1>",
        "6": "Login error!",
        "7": "Username and password do not match!",
        "8": "You must provide a username and a password!",
        "9": "Logging in...",
        "10": "Logging out...",
        "11": "Town selection",
        "12": "Welcome",
        "13": "Select your town from the right or log out",
        "14": "Select town",
        "15": "Remain logged in",
        "16": "<div class = 'menu_title'>Login</div>\
				<div class = 'menu_login_subtitle'>Username</div>\
				<input id  = 'menu_login_user' 	type = 'text'/>\
				<div class = 'menu_login_subtitle'>Password</div>\
				<input id  = 'menu_login_pass' 	type = 'password'/><br />\
				<input id  = 'menu_login_login' type = 'button' value = 'Login'/><br />",
	    "17": "<p>In order to play the game, you must create an account and log in!<br /><br />Before you start playing, check out the Tutorial and the Documentation!</p>"
    }
}

function _(id)
{
    return strings[language][id];
}
