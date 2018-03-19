$(document).ready(function() {
	// Set focus on email input
	$('#email').focus();
	// Grab enter to click 'login' button
	$(document).bind('keypress', function(e) {
		if(e.keyCode==13){
			login();
		}
	});
});

function disableInputs()
{
	$('#email, #pass, #authLength').prop("disabled", true);
}

function enableInputs()
{
	$('#email, #pass, #authLength').prop("disabled", false);
}

function disableButtons()
{
	$('#loginButton').prop("disabled", true);
}

function enableButtons()
{
	$('#loginButton').prop("disabled", false);
}

function updateStatus(msg)
{
	$('#statusMsg').fadeOut(100, function() {
		$('#statusMsg').html(msg).fadeIn(100);
	});
}

function clearStatus()
{
	$('#statusMsg').fadeOut(100, function() {
		$('#statusMsg').html('');
	});
}

function login()
{
	// Disable inputs, buttons
	disableButtons();
	disableInputs();
	$.post("/", { 
		c: 'user', 
		a: 'ajaxlogin', 
		ajax: '1',
		email: $('#email').val(),
		pass: $('#pass').val(),
		authLength: $('#authLength').val()
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
			enableInputs(function() {
				enableButtons(function() {
					$('#email').focus();
				});
			});
		} else if (jData.landingPage) {
			updateStatus(jData.html);
			window.location = '/'+jData.landingPage+'/';
		} else if (jData.userID) {
			// Success, update status and go to user's page
			updateStatus(jData.html);
			window.location = '/userpolls/'+jData.userID+'/';
		} else {
			window.location = '/';
		}
	});
}