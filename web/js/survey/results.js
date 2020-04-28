var voterKeyResult = false;
var pollIndex = 0;
var pollCount = <?php echo count($this->survey->polls); ?>;
var pollsLeftToView = [<?php for ($i = 1; $i <= (count($this->survey->polls)-1); $i++) {if ($i > 1) echo ', '; echo $i;} ?>];
var ballotText = '';
var voterID;
var newVoterError = 'ERROR: Another voter has logged in using this browser. Please close all other voting tabs/windows and refresh this page to enable voting.';

$(document).ready(function() {
	$('#shareURLInput').focus(function(){
		$('#shareURLInput').select();
	});
	
	$('#voterKey').focusout(function() {
		checkVoterKey();
	});
	
	<?php if ($this->survey->votingWindowDirection == 'after') echo 'showResults();'; ?>
	
	// Enable voting button if only one poll in this survey
	if (pollsLeftToView.length == 0) $('#voteButton').prop("disabled", false);
	
	$('#reprintVoteButton').click(function(){
		/*if (ballotText.length > 0) {
			popMsg(ballotText, 0)
		} else */
		popMsg($('#voteInput').html(), 1);
	});

	voterID="<?php if ($this->voter->voterID) echo $this->voter->voterID; ?>";
});

function updateStatus(msg)
{
	$('#statusMsg, #statusMsg2').fadeOut(100, function() {
		$('#statusMsg, #statusMsg2').html(msg).fadeIn(100);
	});
}

function clearStatus()
{
	$('#statusMsg, #statusMsg2').fadeOut(100, function() {
		$('#statusMsg, #statusMsg2').html('');
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
	$('#voteButton, .uploadIdentImageButton, #showResultsButton, #prevNextPollButtons').hide();
}

function showButtons()
{
	$('#voteButton, .uploadIdentImageButton, #showResultsButton, #prevNextPollButtons').show();
}

function disableButtons()
{
	$('#voteButton, #showResultsButton').prop("disabled", true);
}

function enableButtons()
{
	$('#voteButton, #showResultsButton, #prevNextPollButtons').prop("disabled", false);
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
			pollsLeftToView = pollsLeftToView.filter(item => item !== pollIndex)
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
			pollsLeftToView = pollsLeftToView.filter(item => item !== pollIndex)
			updatePollButtons();
		}
	}
	//console.debug(pollsLeftToView); // DEBUG ONLY!!!
	// Enable voting button when ready
	if (pollsLeftToView.length == 0) $('#voteButton').prop("disabled", false);
	location.hash = 'doesNotExist';
	location.hash = '#voteInput';
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

function idMatch()
{
	cookieVoterID = getCookie('voterID');
	if (voterID == cookieVoterID) {
		return true;
	} else return false;
}

function vote()
{
	resultsButtonHtml = $('#voteShowResultsButtons').html();
	$('#voteShowResultsButtons').html('Recording Vote...').promise().done(function(){
		// Need to validate key
		// checkVoterKey(function(){
		// 	if (voterKeyResult == true) {
		// 		voteActual();
		// 	} else {
		// 		enableButtons();
		// 	}
		// });
		if ($('#cdnHandle1').val() != '' || $('#cdnHandle2').val() != '') {
			voteActual();
		} else {
			msg = 'You must upload at least one identifying image';
			$('#voteShowResultsButtons').html(resultsButtonHtml);
			enableButtons();
			updateStatus(msg);
		}
	});
}

function voteActual()
{
	if (idMatch()) {
		$.post("/", { 
			c: 'survey', 
			a: 'ajaxvote', 
			ajax: '1',
			voterID: getCookie('voterID'),
			surveyID: $('#surveyID').val(),
			votes: $('.voteForm').serialize(),
			voterKey: $('#voterKey').val(),
			// viFname: $('#fname').val(),
			// viLname: $('#lname').val(),
			// viStreet: $('#street').val(),
			// viCity: $('#city').val(),
			// viState: $('#state').val(),
			// viZip: $('#zip').val(),
			// viBirthyear: $('#birthyear').val(),
			// viPhone: $('#phone').val(),
			// viEmail: $('#email').val()
		}, function(data) {
			var jData = JSON.parse(data);
			if (jData.error) {
				updateStatus("ERROR: "+jData.error);
				$('#voteShowResultsButtons').html(resultsButtonHtml);
				enableButtons();
			} else {
				ballotText = jData.html;
				// Replace voting mechanism with personal results
				$('#voteInput').html(jData.html);
				// Hide buttons
				hideButtons();
				$('#voteShowResultsButtons').html('');
				// View results
				clearStatus();
				showResults();
				// Show reset voter button
				if ($('#resetVoterButton').length > 0) $('#resetVoterButton').show();
				if ($('#reprintVoteButton').length > 0) $('#reprintVoteButton').show();
				$('#youVoted').show();
				// Print results
				//popMsg(ballotText, 1);
			}
		});
	} else {
		updateStatus(newVoterError);
	}
}

function disableUploadButton()
{
	$('#uploadIdentImageButton').prop("disabled", true);
}

function enableUploadButton()
{
	$('#uploadIdentImageButton').prop("disabled", false);
}

function uploadIdentImage(imageIndex)
{
	if (idMatch()) {
		$.getScript('https://static.filestackapi.com/filestack-js/3.x.x/filestack.min.js', function() {
			var voterID = getCookie('voterID');
			const fsClient = filestack.init('<?php echo $this->cdnConfig['apikey'];?>');
			const options = {
				onUploadDone: (res) => {
					$.post("/", { 
						c: 'survey', 
						a: 'ajaxuploadidentimage', 
						ajax: '1',
						voterID: voterID,
						surveyID: $('#surveyID').val(),
						cdnHandle: res.filesUploaded[0].handle,
						imageIndex: imageIndex,
					}, function(data) {
						var jData = JSON.parse(data);
						if (jData.error) {
							updateStatus("ERROR: "+jData.error);
							enableUploadButton();
						} else {
							disableUploadButton();
							clearStatus();
							$('#cdnHandle'+imageIndex).val(res.filesUploaded[0].handle);
							$('#identImagePreview'+imageIndex).attr('src', res.filesUploaded[0].url);
							$('#identImageLink'+imageIndex).attr('href', res.filesUploaded[0].url);
							$('#uploadButtonCell'+imageIndex).html('Image '+imageIndex+' Saved');
							$('#identImagePreview').removeClass('small').addClass('large');
						}
					});
				},
				accept: ["image/*"],
				// imageMin: [600, 800],
			};
			fsClient.picker(options).open();
		});
	} else {
		updateStatus(newVoterError);
	}
}

function popMsg(html, print)
{
	/*if ($('#voteInput').width() > 0) {
		wWidth = $('#voteInput').width();
	} else wWidth = 300;
	if ($('#voteInput').height() > 0) {
		wHeight = $('#voteInput').height();
	} else wHeight = 600;*/
	wWidth = 800;
	wHeight = 600;
	$.post("/", { 
		c: 'survey', 
		//a: 'printtext', 
		a: 'printmailer', 
		ajax: '1',
		print: print,
		html: html,
		title: "<?php echo $this->survey->title; ?>"
	}, function(data) {
		//var jData = JSON.parse(data);
		receiptWindow=window.open('','','width='+wWidth+',height='+wHeight);
		receiptWindow.document.write(data);
		receiptWindow.focus();
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

function resetVoter() {
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxresetvoter', 
		ajax: '1',
		surveyID: $('#surveyID').val()
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			$('#statusMsg').html("ERROR: "+jData.error);
		} else {
			location.reload();
		}
	});
}