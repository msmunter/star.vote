$(document).ready(function() {
	$('#pollQuestion').focus();
	$('#pollAnswers').on('focus','.pollAnswer:last',function(){
		addAnswer();
	});
});