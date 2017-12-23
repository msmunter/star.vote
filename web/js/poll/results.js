$(document).ready(function() {
	
});

function vote()
{
	alert("Not working yet...soon!\nSerialized vote: "+$('.answerForm').serialize());
	/*var nextAnswerID = $('#pollAnswers input').length + 1;
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
			$('#pollAnswers').append(jData.nextAnswer);
		} else {
			alert('ERROR: failed to add answer');
		}
	});*/
}