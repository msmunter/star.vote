$(document).ready(function() {
	
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

function disableInputs()
{
	$('#numKeys').prop("disabled", true);
}

function disableButtons()
{
	$('#generateKeysButton').prop("disabled", true);
}

function enableInputs()
{
	$('#numKeys').prop("disabled", false);
}

function enableButtons()
{
	$('#generateKeysButton').prop("disabled", false);
}

function generateVoterKeys()
{
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxgeneratevoterkeys', 
		ajax: '1',
		surveyID: $('#surveyID').val(),
		numKeys: $('#numKeys').val(),
	}, function(data) {
		// Disable inputs
		disableInputs();
		var jData = JSON.parse(data);
		if (jData.error) {
			//alert(jData.error);
			updateStatus("ERROR: "+jData.error);
			enableInputs();
			enableButtons();
		} else {
			updateStatus("Generated "+jData.keysGenerated+" keys");
			$('#existingVoterKeys').html(jData.html);
			enableInputs();
			enableButtons();
		}
	});
	
}