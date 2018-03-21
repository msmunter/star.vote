var voterKeyResult;

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
	$('#voteButton, #showResultsButton').hide();
}

function showButtons()
{
	$('#voteButton, #showResultsButton').show();
}

function disableButtons()
{
	$('#voteButton, #showResultsButton').prop("disabled", true);
}

function enableButtons()
{
	$('#voteButton, #showResultsButton').prop("disabled", false);
}

function checkVoterKey(callBack)
{
	var voterKeyVal = $('#voterKey').val();
	$.post("/", { 
		c: 'poll', 
		a: 'ajaxcheckvoterkey', 
		ajax: '1',
		voterKey: voterKeyVal,
		pollID: $('#pollID').val()
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
	});
	if (typeof callBack === "function") callBack();
}

function vote()
{
	disableButtons();
	// Need to validate slug
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
		c: 'poll', 
		a: 'ajaxvote', 
		ajax: '1',
		voterID: getCookie('voterID'),
		pollID: $('#pollID').val(),
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
		c: 'poll', 
		a: 'ajaxresults', 
		ajax: '1',
		pollID: $('#pollID').val()
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
		c: 'poll', 
		a: 'ajaxrunoffmatrix', 
		ajax: '1',
		pollID: $('#pollID').val()
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
		c: 'poll', 
		a: 'ajaxcvr', 
		ajax: '1',
		pollID: $('#pollID').val()
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

function selectStar(id, vote) {
	$('.radioLabel'+id).removeClass('starNumber');
	$('.radioLabel'+id).removeClass('starText');
	for (i = 0; i <= 6; i++) {
		$("label[for='radioVote|"+id+"|"+i+"']").html(i);
	}
	for (i = 0; i <= vote; i++) { 
		if (i != 0) $("label[for='radioVote|"+id+"|"+i+"']").addClass('starNumber');
		if (i < vote) {
			$("label[for='radioVote|"+id+"|"+i+"']").html('&nbsp;&nbsp;');
			$("label[for='radioVote|"+id+"|"+i+"']").addClass('starText');
		} else {
			$("label[for='radioVote|"+id+"|"+i+"']").html(i);
		}
	}
}