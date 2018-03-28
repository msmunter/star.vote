<?php foreach ($this->surveys as $survey) { ?>
	<a class="ui-btn ui-btn-inline ui-corner-all ui-mini" href="/<?php if ($survey->customSlug) {echo $survey->customSlug;} else echo $survey->surveyID; ?>/"><?php echo $survey->title; ?></a>
<?php } ?>