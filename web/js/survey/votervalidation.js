$(document).ready(function() {
	//$('#voterID').select();
	$('.validateVoterButtons').prop("disabled", true);
	updateStatus('Ready to load...');
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
	updateStatus('Loading voter...');
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxloadvoterval', 
		ajax: '1',
		surveyID: $('#surveyID').val(),
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
		} else if (!jData.checkoutTime) {
			updateStatus('No voters to process');
			$('#loadValidationButton').prop("disabled", false);
		} else {
			//console.log(jData);
			updateStatus('Voter '+jData.voterID+' loaded');
			$('#checkoutTime').html(jData.checkoutTime);
			$('#validationComparisonTableName').html(jData.voterName);
			$('#validationComparisonTableAddress').html(jData.voterAddress);
			$('#validationComparisonTableCSZ').html(jData.voterCSZ);
			$('#validationComparisonTableBirthyear').html(jData.voterBirthyear);
			$('#validationComparisonTableValidationStatus').html(jData.validationStatus);
			$('#voterID').val(jData.voterID);
			$('#voterVerifiedCount').html(jData.voterVerifiedCount);
			$('#voterCount').html(jData.voterCount);
			$('#validationImg1').attr('src', 'https://cdn.filestackcontent.com/'+jData.cdnHandle1);
			$('#validationImgHref1').attr('href', 'https://cdn.filestackcontent.com/'+jData.cdnHandle1);
			$('#validationImg2').attr('src', 'https://cdn.filestackcontent.com/'+jData.cdnHandle2);
			$('#validationImgHref2').attr('href', 'https://cdn.filestackcontent.com/'+jData.cdnHandle2);
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
		//console.log(data); // DEBUG ONLY!!!	
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
		} else {
			updateStatus(jData.msg);
			//updateStatus(jData.msg+'<br />'+jData.query); // DEBUG ONLY!!!
			$('#voterVerifiedCount').html(jData.voterVerifiedCount);
			$('#voterCount').html(jData.voterCount);
			$('#loadValidationButton').prop("disabled", false);
		}
	});
}