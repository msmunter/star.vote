var voterKeyResult = false;
var pollIndex = 0;
var pollCount = <?php echo count($this->survey->polls); ?>;

$(document).ready(function() {
	$('#shareURLInput').focus(function(){
		$('#shareURLInput').select();
	});
	$('#voterKey').focusout(function() {
		checkVoterKey();
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

function showContainer(container)
{
	$('#'+container+'ShowButton').hide(0, function(){
		$('#'+container+'Container').fadeIn(100);
	});
}

function disableInputs()
{
	$('#voterKey').prop("disabled", true);
	/*$("#pollAnswers :input").prop("disabled", true);*/
}

function enableInputs()
{
	$('#voterKey').prop("disabled", false);
	/*$("#pollAnswers :input").prop("disabled", false);*/
}

function hideButtons()
{
	$('#voteButton, #showResultsButton, #prevNextPollButtons').hide();
}

function showButtons()
{
	$('#voteButton, #showResultsButton, #prevNextPollButtons').show();
}

function disableButtons()
{
	$('#voteButton, #showResultsButton').prop("disabled", true);
}

function enableButtons()
{
	$('#voteButton, #showResultsButton').prop("disabled", false);
}

function changePoll(direction)
{
	var newIndex = 0;
	var visDelay = 80;
	if (direction == 'u') {
		newIndex = pollIndex+1;
		if (newIndex < pollCount) {
			// Not last item yet, go up one
			$('#surveyPollContainer\\|'+pollIndex).fadeOut(visDelay, function(){
				$('#surveyPollContainer\\|'+newIndex).fadeIn(visDelay);
			});
			pollIndex = pollIndex + 1;
			$('#pollIndex').html(pollIndex+1);
			updatePollButtons();
		}
	} else {
		newIndex = pollIndex-1;
		if (newIndex >= 0) {
			// Not at zero, go down one
			$('#surveyPollContainer\\|'+pollIndex).fadeOut(visDelay, function(){
				$('#surveyPollContainer\\|'+newIndex).fadeIn(visDelay);
			});
			pollIndex = pollIndex - 1;
			$('#pollIndex').html(pollIndex+1);
			updatePollButtons();
		}
	}
}

function updatePollButtons()
{
	// Disable/enable buttons accordingly
	if (pollIndex > 0) {
		$('#prevPollButton').prop("disabled", false);
	}
	if ((pollIndex+1) < pollCount) {
		$('#nextPollButton').prop("disabled", false);
	}
	if (pollIndex == pollCount-1) {
		// At top
		$('#nextPollButton').prop("disabled", true);
	} else if (pollIndex == 0) {
		// At bottom
		$('#prevPollButton').prop("disabled", true);
	}
}

function checkVoterKey(callbackFunction)
{
	var voterKeyVal = $('#voterKey').val();
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxcheckvoterkey', 
		ajax: '1',
		voterKey: voterKeyVal,
		surveyID: $('#surveyID').val()
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.returncode == '1') {
			clearStatus();
			voterKeyResult = true;
			$('#voterKey').removeClass('highlightInputRed').addClass('highlightInputGreen');
			enableButtons();
		} else {
			updateStatus(jData.html);
			voterKeyResult = false;
			$('#voterKey').removeClass('highlightInputGreen').addClass('highlightInputRed');
			disableButtons();
		}
		if (typeof callbackFunction === "function") callbackFunction();
	});
}

function vote()
{
	disableButtons();
	// Need to validate key
	checkVoterKey(function(){
		if (voterKeyResult == true) {
			voteActual();
		} else {
			enableButtons();
		}
	});
}

function voteActual()
{
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxvote', 
		ajax: '1',
		voterID: getCookie('voterID'),
		surveyID: $('#surveyID').val(),
		votes: $('.voteForm').serialize(),
		voterKey: $('#voterKey').val()
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
			enableButtons();
		} else {
			// Replace voting mechanism with personal results
			$('#voteInput').html(jData.html);
			// Hide vote, results buttons
			disableButtons();
			hideButtons();
			// View results
			clearStatus();
			showResults();
		}
	});
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function showResults() {
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxresults', 
		ajax: '1',
		surveyID: $('#surveyID').val()
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			$('#statusMsg').html("ERROR: "+jData.error);
		} else {
			// View results
			$('#pollResultsActual').html(jData.results);
			$('#runoffMatrixContainer').html(jData.runoffmatrix);
			$('#pollResults').show();
		}
	});
}

function showRunoffMatrix() {
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxrunoffmatrix', 
		ajax: '1',
		surveyID: $('#surveyID').val()
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			$('#statusMsg').html("ERROR: "+jData.error);
		} else {
			// Show results
			$('#runoffMatrixShowButton').hide(0, function(){
				$('#runoffMatrixContainer').html(jData.html).fadeIn(100);
			});
		}
	});
}

function showCvrHtml() {
	showContainer('ballotRecord');
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxcvr', 
		ajax: '1',
		surveyID: $('#surveyID').val()
	}, function(data) {
		//alert(data);
		var jData = JSON.parse(data);
		if (jData.error) {
			$('#statusMsg').error("ERROR: "+jData.error);
		} else {
			// Show results
			$('#ballotRecordContainer').html(jData.html).fadeIn(100);
		}
	});
}