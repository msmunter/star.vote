$(document).ready(function() {
	//$('#voterID').select();
	$('.validateVoterButtons').prop("disabled", true);
});

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

function loadvalidation()
{
	alert('Soon.');
}

function validatevoter(accept, reason)
{
	$('.validateVoterButtons').prop("disabled", true);
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxvalidatevoter', 
		ajax: '1',
		surveyID: $('#surveyID').val(),
		voterID: $('#voterID').val(),
		accept: accept,
		reason: reason,
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
		} else {
			updateStatus("SUCCESS: Voter "+jData.voterID+" validated")
			$('#voterVerifiedCount').html(jData.voterVerifiedCount);
			$('#voterCount').html(jData.voterCount);
		}
		$('.validateVoterButtons').prop("disabled", false);
		$('#voterID').select();
	});
}