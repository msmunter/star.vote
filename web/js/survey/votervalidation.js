$(document).ready(function() {
	$('#voterID').select();
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

function validatevoter()
{
	$('#validateVoterButton').prop("disabled", true);
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxvalidatevoter', 
		ajax: '1',
		surveyID: $('#surveyID').val(),
		voterID: $('#voterID').val()
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
		} else {
			updateStatus("SUCCESS: Voter "+jData.voterID+" validated")
			$('#voterVerifiedCount').html(jData.voterVerifiedCount);
			$('#voterCount').html(jData.voterCount);
		}
		$('#validateVoterButton').prop("disabled", false);
		$('#voterID').select();
	});
}