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
	//alert($('#pollQuestion').val()+' :: '+$('#pollAnswers').serialize());
	if ($('#fsCustomSlugSwitch').val() == 0) {
		createPollActual();
	} else {
		if (checkCustomSlug() == 1) {
			createPollActual();
		} else {
			// Custom slug didn't work, enable inputs and try again
			enableButtons();
		}
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
		fsCustomSlug: $('#fsCustomSlugInput').val()
	}, function(data) {
		// Disable inputs
		disableInputs();
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
			enableInputs();
			enableButtons();
		} else if (jData.html) {
			// Success, update status and go see poll
			updateStatus(jData.html);
			window.location = '/'+jData.pollID+'/';
		} else {
			alert('ERROR: Poll seems to have saved but no ID was returned');
			window.location = '/poll/history/';
		}
	});
}

function checkCustomSlug()
{
	var slugVal = $('#fsCustomSlugInput').val();
	if (slugVal.length > 16) {
		updateStatus("ERROR: custom slug too long; must be 4-16 characters");
		return 0;
	} else if (slugVal.length < 4) {
		updateStatus("ERROR: custom slug too short; must be 4-16 characters.");
		return 0;
	} else {
		$.post("/", { 
			c: 'poll', 
			a: 'ajaxcheckcustomslug', 
			ajax: '1',
			slug: slugVal
		}, function(data) {
			var jData = JSON.parse(data);
			updateStatus(jData.html);
			if (jData.returncode == '0') {
				return 0;
			} else {
				return 1;
			}
		});
	}
	
}