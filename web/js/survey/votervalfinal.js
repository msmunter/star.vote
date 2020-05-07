$(document).ready(function() {
	//$('#voterID').select();
	// $('.validateVoterButtons').prop("disabled", true);
	// updateStatus('Ready to load...');
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

function finalvalidatevoter(accept, reason)
{
	$('.validateVoterButtons').prop("disabled", true);
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxfinalizevoter', 
		ajax: '1',
		surveyID: $('#surveyID').val(),
		voterID: $('#voterID').val(),
		ticketID: $('#ticketID').val(),
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

function lookupvoter()
{
	window.open($('#orestarLink').attr('href'), "orestar");
}