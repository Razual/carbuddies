

function createNewAccount() {
	/*var accData = new FormData();
	accData.append('action', 'createAccount');
	accData.append('firstname', $('#new_acc_firstname').val());
	accData.append('lastname', $('#new_acc_lastname').val());
	accData.append('email', $('#new_acc_email').val());
	accData.append('password', $('#new_acc_password').val());
	accData.append('repeat_password', $('#new_acc_repeat_password').val());
	*/
	var accData = {'action' : 'createAccount', 
					'firstname' : $('#new_acc_firstname').val(), 
					'lastname' : $('#new_acc_lastname').val(), 
					'email' : $('#new_acc_email').val(), 
					'password' : $('#new_acc_password').val(), 
					'repeat_password' : $('#new_acc_repeat_password').val()
					};
	accData 	= JSON.stringify(accData);

	$.ajax({
		url: "../php/login.php", 
		cache: false,
		data: accData,
		processData: false,
		type: 'POST',
		success: function(data){
			var dataString = data.trim();

	        switch(dataString) {
	        	case "missing_fields":
	        		alert("Ein oder mehrere Felder wurden nicht ausgefüllt.");
	        	break;
	        	case "invalid_mail_format":
	        		alert("Die eingegebene Email hat nicht das richtige Format.");
	        	break;
	        	case "pw_not_match":
	        		alert("Passwort und Wiederholung stimmen nicht überein.");
	        	break;
	        	case "user_exist":
	        		alert("Ein Benutzer mit diesen Daten Existiert bereits.");
	        	break;

	        	default:
	        	break;
	        }
	    }
	});
}