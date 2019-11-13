function sendTestMail() {
	// Disable inputs, buttons
	//disableButtons();
	//disableInputs();
	$('#mailResponse').html("Sending...");
    $.post("/", { 
		c: 'mail', 
		a: 'ajaxsendemailverification', 
		ajax: '1',
		// currentPass: $('#currentPass').val(),
		// pass1: $('#pass1').val(),
		// pass2: $('#pass2').val()
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			$('#mailResponse').html("ERROR: "+jData.error);
			// enableInputs();
			// enableButtons(function() {
			// 	$('#pass1').focus();
			// });
		} else {
			//window.location = '/user/';
			$('#mailResponse').html('<pre>'+JSON.stringify(jData,null,4)+'</pre>');
		}
	});
}