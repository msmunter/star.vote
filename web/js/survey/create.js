$(document).ready(function() {
	$('#surveyTitle').focus();
	// Grab enter to click create button
	$(document).bind('keypress', function(e) {
		if(e.keyCode==13){
			createSurvey();
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
		checkCustomSurveySlug();
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

function enableInputs()
{
	$('.bigContainerInner input, .bigContainerInner select').prop("disabled", false);
	/*$("#pollAnswers :input").prop("disabled", false);*/
}

function disableInputs()
{
	$('.bigContainerInner input, .bigContainerInner select').prop("disabled", true);
	/*$("#pollAnswers :input").prop("disabled", true);*/
}

function enableButtons()
{
	$('#createSurveyButton').prop("disabled", false);
}

function disableButtons()
{
	$('#createSurveyButton').prop("disabled", true);
}

function createSurvey()
{
	// Disable buttons
	disableButtons();
	//alert($('#pollQuestion').val()+' :: '+$('#pollAnswers').serialize()); // DEBUG ONLY!!!
	if ($('#fsCustomSlugSwitch').val() == 1) {
		if (slugResult == true) {
			// Slug already validated
			createSurveyActual();
		} else {
			// Need to validate slug
			checkCustomSurveySlug(function(){
				if (slugResult == true) {
					createSurveyActual();
				} else {
					enableButtons();
				}
			});
		}
	} else {
		createSurveyActual();
	}
}

function createSurveyActual()
{
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxinsertsurvey',
		ajax: '1',
		surveyTitle: $('#surveyTitle').val(),
		fsPrivate: $('#fsPrivate').val(),
		fsRandomOrder: $('#fsRandomOrder').val(),
		fsCustomSlug: $('#fsCustomSlugInput').val(),
		fsVerifiedVoting: $('#fsVerifiedVoting').val(),
		fsVerifiedVotingType: $('#fsVerifiedVotingType').val(),
		fsVerbage: $('#fsVerbage').val()
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
		} else if (jData.surveyID) {
			// Success, update status and go see poll
			updateStatus(jData.html);
			window.location = '/'+jData.pollID+'/';
		} else {
			alert('ERROR: seems to have saved but no ID was returned');
			window.location = '/user/';
		}
	});
}

function checkCustomSurveySlug(callBack)
{
	var slugVal = $('#fsCustomSlugInput').val();
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxcheckcustomsurveyslug', 
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