var slugResult;

$(document).ready(function() {
	$('#pollQuestion').focus();
	$('#pollAnswers').on('focus','.pollAnswer:last',function(){
		addAnswer();
	});
	// Grab enter to click create poll button
	$(document).bind('keypress', function(e) {
		if(e.keyCode==13){
			createPoll();
		}
	});
	$('#fsNumWinners').focusout(function() {
		var maxWinners = $('#pollAnswers input').length - 1;
		if ($('#fsNumWinners').val() < 1) {
			$('#fsNumWinners').val(1);
			updateStatus('Number of winners set to current minimum');
		} else if ($('#fsNumWinners').val() > maxWinners) {
			$('#fsNumWinners').val(maxWinners);
			updateStatus('Number of winners set to current maximum');
		} else {
			clearStatus();
		}
	});
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
	$('#pollQuestion').prop("disabled", true);
	$("#pollAnswers :input").prop("disabled", true);
}

function disableButtons()
{
	$('#addAnswerButton, #createPollButton').prop("disabled", true);
}

function enableInputs()
{
	$('#pollQuestion').prop("disabled", false);
	$("#pollAnswers :input").prop("disabled", false);
}

function enableButtons()
{
	$('#addAnswerButton, #createPollButton').prop("disabled", false);
}

function addAnswer()
{
	var nextAnswerID = $('#pollAnswers input').length + 1;
	$.post("/", { 
		c: 'poll', 
		a: 'ajaxaddanswer', 
		ajax: '1', 
		nextAnswerID: nextAnswerID
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
		} else if (jData.nextAnswerID) {
			// Success, add the line
			$('#pollAnswers').append(jData.nextAnswer).enhanceWithin();
		} else {
			alert('ERROR: failed to add answer');
		}
	});
}

function createPoll()
{
	disableButtons();
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxinsertpoll', 
		ajax: '1',
		pollQuestion: $('#pollQuestion').val(),
		pollAnswers: $('#pollAnswers').serialize(),
		surveyID: $('#surveyID').val(),
		numWinners: $('#numWinners').val()
	}, function(data) {
		// Disable inputs
		disableInputs();
		var jData = JSON.parse(data);
		if (jData.error) {
			//alert(jData.error);
			updateStatus("ERROR: "+jData.error);
			enableInputs();
			enableButtons();
		} else if ($('#surveySlug').val() != '') {
			updateStatus(jData.html);
			window.location = '/'+$('#surveySlug').val()+'/';
		} else if ($('#surveyID').val() != '') {
			// Success, update status and go see poll
			updateStatus(jData.html);
			window.location = '/'+$('#surveyID').val()+'/';
		} else {
			alert('ERROR: Poll seems to have saved but no ID was returned');
			window.location = '/user/';
		}
	});
}