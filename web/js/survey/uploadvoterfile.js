$(document).ready(function() {

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
	$('#voterFileInput').prop("disabled", true);
	/*$("#pollAnswers :input").prop("disabled", true);*/
}

function enableInputs()
{
	$('#voterFileInput').prop("disabled", false);
	/*$("#pollAnswers :input").prop("disabled", false);*/
}

function hideButtons()
{
	$('#uploadButton').hide();
}

function showButtons()
{
	$('#uploadButton').show();
}

function disableButtons()
{
	$('#uploadButton').prop("disabled", true);
}

function enableButtons()
{
	$('#uploadButton').prop("disabled", false);
}

function uploadVoterFile()
{
	updateStatus('Uploading...');
	disableButtons();
	var fd = new FormData();
	fd.append('file', $('input[type=file]')[0].files[0]);
	fd.append('surveyID', $('#surveyID').val());
	$.ajax({ 
		url: '/view/survey/uploadvoterfileactual.view.php',
		type: 'post',
		data: fd,
		success:function(data){
			var jData = JSON.parse(data);
			if (jData.error) {
				updateStatus("ERROR: "+jData.error);
			} else {
				$('#voterFileInput').val('');
				if (jData.status) {
					updateStatus(jData.status);
				} else {
					clearStatus();
				}
			}
			enableButtons();
		},
		cache: false,
		contentType: false,
		processData: false
	});
}