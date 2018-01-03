$(document).ready(function() {
	$('#shareURLInput').focus(function(){
		$('#shareURLInput').select();
	});
});

function updateStatus(msg)
{
	$('#statusMsg').fadeOut(100, function() {
		$('#statusMsg').html(msg).fadeIn(100);
	});
}

function vote()
{
	$.post("/", { 
		c: 'poll', 
		a: 'ajaxvote', 
		ajax: '1',
		voterID: getCookie('voterID'),
		pollID: $('#pollID').val(),
		votes: $('.answerForm').serialize()
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			//$('#statusMsg').html("ERROR: "+jData.error);
			updateStatus("ERROR: "+jData.error);
		} else {
			// Replace voting mechanism with personal results
			$('#voteInput').html(jData.html);
			// Hide vote button
			$('#voteButton').hide();
			$('#showResultsButton').html('Update Results');
			// View results
			$('#statusMsg').hide();
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
			$('#pollResults').html(jData.html);
			//$('#statusMsg').html(jData.html);
			$('#pollResults').show();
		}
	});
}

function selectStar(id, vote) {
	$('.radioLabel'+id).removeClass('starNumber');
	for (i = 0; i <= vote; i++) { 
		$("label[for='radioVote|"+id+"|"+i+"']").addClass('starNumber');
	}
	//$("label[for='radioVote|"+id+"|"+vote+"']").addClass('starNumber');
}