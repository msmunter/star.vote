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
	$('#loadValidationButton').prop("disabled", true);
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxloadvoterval', 
		ajax: '1',
		surveyID: $('#surveyID').val(),
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
		} else if (!jData.cdnHandle) {
			updateStatus('No voters to process');
			$('#loadValidationButton').prop("disabled", false);
		} else {
			console.log(jData);
			//updateStatus("SUCCESS: Voter "+jData.voterID+" validated")
			$('#validationComparisonTableName').html(jData.voterName);
			$('#validationComparisonTableAddress').html(jData.voterAddress);
			$('#validationComparisonTableCSZ').html(jData.voterCSZ);
			$('#voterVerifiedCount').html(jData.voterVerifiedCount);
			$('#voterCount').html(jData.voterCount);
			$('#validationImg').attr('src', 'https://cdn.filestackcontent.com/'+jData.cdnHandle);
			$('#validationImgHref').attr('href', 'https://cdn.filestackcontent.com/'+jData.cdnHandle);
			$('.validateVoterButtons').prop("disabled", false);
		}
	});
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
		console.log(data); // DEBUG ONLY!!!	
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