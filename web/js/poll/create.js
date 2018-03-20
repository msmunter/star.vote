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
	$('#fsCustomSlugSwitch').change(function() {
		if ($('#fsCustomSlugSwitch').val() == 0) {
			$('#customSlugInputContainer').hide();
		} else {
			$('#customSlugInputContainer').show();
		}
	});
	$('#fsVerifiedVoting').change(function() {
		if ($('#fsVerifiedVoting').val() == 0) {
			$('#verifiedVotingSelectContainer').hide();
		} else {
			$('#verifiedVotingSelectContainer').show();
		}
	});
	$('#fsCustomSlugInput').focusout(function() {
		checkCustomSlug();
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
	// Disable buttons
	disableButtons();
	//alert($('#pollQuestion').val()+' :: '+$('#pollAnswers').serialize()); // DEBUG ONLY!!!
	if ($('#fsCustomSlugSwitch').val() == 1) {
		if (slugResult == true) {
			// Slug already validated
			createPollActual();
		} else {
			// Need to validate slug
			checkCustomSlug(function(){
				if (slugResult == true) {
					createPollActual();
				} else {
					enableButtons();
				}
			});
		}
	} else {
		createPollActual();
	}
}

function createPollActual()
{
	$.post("/", { 
		c: 'poll', 
		a: 'ajaxinsertpoll', 
		ajax: '1',
		pollQuestion: $('#pollQuestion').val(),
		pollAnswers: $('#pollAnswers').serialize(),
		fsPrivate: $('#fsPrivate').val(),
		fsRandomOrder: $('#fsRandomOrder').val(),
		fsCustomSlug: $('#fsCustomSlugInput').val(),
		fsVerifiedVoting: $('#fsVerifiedVoting').val(),
		fsVerifiedVotingType: $('#fsVerifiedVotingType').val()
	}, function(data) {
		// Disable inputs
		disableInputs();
		var jData = JSON.parse(data);
		if (jData.error) {
			//alert(jData.error);
			updateStatus("ERROR: "+jData.error);
			enableInputs();
			enableButtons();
		} else if (jData.customSlug) {
			updateStatus(jData.html);
			window.location = '/'+jData.customSlug+'/';
		} else if (jData.pollID) {
			// Success, update status and go see poll
			updateStatus(jData.html);
			window.location = '/'+jData.pollID+'/';
		} else {
			alert('ERROR: Poll seems to have saved but no ID was returned');
			window.location = '/poll/history/';
		}
	});
}

function checkCustomSlug(callBack)
{
	var slugVal = $('#fsCustomSlugInput').val();
	$.post("/", { 
		c: 'poll', 
		a: 'ajaxcheckcustomslug', 
		ajax: '1',
		slug: slugVal
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.returncode == '1') {
			clearStatus();
			slugResult = true;
			$('#fsCustomSlugInput').removeClass('highlightInputRed').addClass('highlightInputGreen');
		} else {
			updateStatus(jData.html);
			slugResult = false;
			$('#fsCustomSlugInput').removeClass('highlightInputGreen').addClass('highlightInputRed');
		}
	});
	if (typeof callBack === "function") callBack();
}