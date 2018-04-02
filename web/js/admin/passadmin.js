$(document).ready(function() {
	// Set focus on email input
	$('#pass1').focus();
	// Grab enter to click 'login' button
	$(document).bind('keypress', function(e) {
		if(e.keyCode==13){
			changeMyPass();
		}
	});
});

function disableInputs()
{
	$('#pass1, #pass2').prop("disabled", true);
}

function enableInputs()
{
	$('#pass1, #pass2').prop("disabled", false);
}

function disableButtons()
{
	$('#changePassButton').prop("disabled", true);
}

function enableButtons()
{
	$('#changePassButton').prop("disabled", false);
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

function changePassAdmin() {
	// Disable inputs, buttons
	disableButtons();
	disableInputs();
	$.post("/", { 
		c: 'admin', 
		a: 'ajaxchangepassadmin', 
		ajax: '1',
		userToUpdateID: $('#userToUpdateID').val(),
		pass1: $('#pass1').val(),
		pass2: $('#pass2').val()
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
			enableInputs();
			enableButtons(function() {
				$('#pass1').focus();
			});
		} else {
			updateStatus(jData.html);
			window.location = '/admin/userdetails/'+$('#userToUpdateID').val()+'/';
		}
	});
}