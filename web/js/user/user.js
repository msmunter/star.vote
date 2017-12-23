/*function createUser()
{
	pass1 = $('#pass1').attr('value');
	pass2 = $('#pass2').attr('value');
	if (pass1 !== pass2) {
		msg = 'Passwords must match';
	} else if (pass1 == '') {
		msg = 'Password cannot be blank';
	} else {
		$.post("/", { c: 'user', a: 'create', ajax: '1', firstName: $('#firstName').val(), lastName: $('#lastName').val(), email: $('#email').val(), pass: $('#pass1').val() },
			   function(data) {
				   alert("AJAX Returned: " + data);
			   });
	}
	// Display error if necessary
	if (msg != '') {
		alert(msg);
	}
}*/

function submitAddUserForm()
{
	var requiredFormElements = [];
	var formErrors = [];
	var formErrorsMsg = '';
	var msg = '';
	for	(index = 0; index < requiredFormElements.length; index++) {
		if ($('#'+requiredFormElements[index]).val() == '') {
			formErrors.push(requiredFormElements[index]);
		}
	}
	if (formErrors.length > 0) {
		for	(index = 0; index < formErrors.length; index++) {
			if (index > 0) {
				formErrorsMsg += ', ';
			}
			formErrorsMsg += formErrors[index];
		}
		alert('Must fill out the following items: '+formErrorsMsg);
	} else {
		// Required elements filled out, check for valid password info
		pass1 = $('#pass1').val();
		pass2 = $('#pass2').val();
		if (pass1 !== pass2) {
			msg = 'Passwords must match';
		} else if (pass1 == '') {
			msg = 'Password cannot be blank';
		} else if (pass1.length < 8) {
			msg = 'Too short: password must be between 8 and 128 characters in length';
		} else if (pass1.length >= 128) {
			msg = 'Too long: password must be between 8 and 128 characters in length';
		} else {
			// Submit form via POST
			$.post("/", { 
				c: 'user', 
				a: 'insertuser', 
				ajax: '1', 
				form: $('#addUserForm').serialize()
			}, function(data) {
				if (data > 0) {
					window.location = '/user/details/'+data+'/';
				} else {
					msg = 'ERROR: failed to add user.';
				}
			});
			
		}
		// Display error if necessary
		if (msg != '') {
			alert(msg);
		}
	}
}