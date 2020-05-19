$(document).ready(function() {
	//$('#voterID').select();
	$('.validateVoterButtons').prop("disabled", true);
	updateStatus('Ready to load...');
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

function loadvalidation()
{
	$('#loadValidationButton').prop("disabled", true);
	$('#voterVerifiedCount, #tempVoterCount, #valOnceCount, #valTwiceCount, #rejOnceCount, #rejTwiceCount, #inResultsCount, #newVoterCount, #finalizedCount').html("--");
	updateStatus('Loading voter...');
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxloadvoterval', 
		ajax: '1',
		surveyID: $('#surveyID').val(),
	}, function(data) {
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
		} else if (!jData.checkoutTime) {
			updateStatus('No voters to process');
			$('#voterVerifiedCount').html(jData.voterVerifiedCount);
			$('#voterCount').html(jData.voterCount);
			$('#checkoutTime').html('--');
			$('#validationComparisonTableName').html('--');
			$('#validationComparisonTableAddress').html('--');
			$('#validationComparisonTableCSZ').html('--');
			$('#validationComparisonTableBirthyear').html('--');
			$('#validationComparisonTableBirthdate').html('--');
			$('#validationComparisonTableValidationStatus').html('--');
			$('#voterID').val('');
			$('#validationImg1, #validationImg2').attr('src', '/web/images/img_placeholder.svg');
			$('#validationImgHref1, #validationImgHref2').attr('href', '/web/images/img_placeholder.svg');
			$('#loadValidationButton').prop("disabled", false);
		} else {
			//console.log(jData);
			updateStatus('Voter '+jData.voterID+' loaded');
			$('#voterVerifiedCount').html(jData.voterVerifiedCount);
			$('#voterCount').html(jData.voterCount);
			$('#checkoutTime').html(jData.checkoutTime);
			$('#validationComparisonTableName').html(jData.voterName);
			$('#validationComparisonTableAddress').html(jData.voterAddress);
			$('#validationComparisonTableCSZ').html(jData.voterCSZ);
			$('#validationComparisonTableBirthyear').html(jData.voterBirthyear);
			$('#validationComparisonTableBirthdate').html(jData.voterBirthdate);
			$('#validationComparisonTableValidationStatus').html(jData.validationStatus);
			$('#orestarLink').attr('href', jData.orestarLink);
			$('#voterID').val(jData.voterID);
			if (jData.cdnHandle1) {
				$('#validationImg1').attr('src', 'https://cdn.filestackcontent.com/'+jData.cdnHandle1);
				$('#validationImgHref1').attr('href', 'https://cdn.filestackcontent.com/'+jData.cdnHandle1);
			} else {
				$('#validationImg1').attr('src', '/web/images/img_placeholder.svg');
				$('#validationImgHref1').attr('href', '/web/images/img_placeholder.svg');
			}
			
			if (jData.cdnHandle2) {
				$('#validationImg2').attr('src', 'https://cdn.filestackcontent.com/'+jData.cdnHandle2);
				$('#validationImgHref2').attr('href', 'https://cdn.filestackcontent.com/'+jData.cdnHandle2);
			} else {
				$('#validationImg2').attr('src', '/web/images/img_placeholder.svg');
				$('#validationImgHref2').attr('href', '/web/images/img_placeholder.svg');
			}
			$('.validateVoterButtons').prop("disabled", false);
		}
		if (!jData.error) {
			//$('#cellVoterVerifiedCount').html(jData.voterVerifiedCount);
			$('#cellTempVoterCount').html(jData.tempVoterCount);
			$('#cellValOnceCount').html(jData.valOnceCount);
			$('#cellValTwiceCount').html(jData.valTwiceCount);
			$('#cellRejOnceCount').html(jData.rejOnceCount);
			$('#cellRejTwiceCount').html(jData.rejTwiceCount);
			$('#cellInResultsCount').html(jData.inResultsCount);
			$('#cellNewVoterCount').html(jData.newVoterCount);
			$('#cellFinalizedCount').html(jData.finalizedCount);
			$('#cellToBeReviewedCount').html(jData.toBeReviewedCount);
		}
	});
}

function validatevoter(accept, reason)
{
	$('.validateVoterButtons').prop("disabled", true);
	$.post("/", { 
		c: 'survey', 
		a: 'ajaxvalidatevoter', 
		ajax: '1',
		surveyID: $('#surveyID').val(),
		voterID: $('#voterID').val(),
		accept: accept,
		reason: reason,
	}, function(data) {
		//console.log(data); // DEBUG ONLY!!!	
		var jData = JSON.parse(data);
		if (jData.error) {
			updateStatus("ERROR: "+jData.error);
		} else {
			updateStatus(jData.msg);
			//updateStatus(jData.msg+'<br />'+jData.query); // DEBUG ONLY!!!
			$('#voterVerifiedCount').html(jData.voterVerifiedCount);
			$('#voterCount').html(jData.voterCount);
			$('#checkoutTime').html('--');
			$('#validationComparisonTableName').html('--');
			$('#validationComparisonTableAddress').html('--');
			$('#validationComparisonTableCSZ').html('--');
			$('#validationComparisonTableBirthyear').html('--');
			$('#validationComparisonTableBirthdate').html('--');
			$('#validationComparisonTableValidationStatus').html('--');
			$('#voterID').val('');
			$('#validationImg1, #validationImg2').attr('src', '/web/images/img_placeholder.svg');
			$('#validationImgHref1, #validationImgHref2').attr('href', '/web/images/img_placeholder.svg');
			$('#loadValidationButton').prop("disabled", false);
		}
	});
}

function lookupvoter()
{
	window.open($('#orestarLink').attr('href'), "orestar");
}