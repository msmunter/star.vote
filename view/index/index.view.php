<div class="bigContainer">
	<div class="bigContainerTitle">IPO 2020 Primary</div>
	<div id="indexCreatePollContainer">
		<!-- <a class="ui-btn ui-btn-inline ui-mini ui-corner-all" href="/ipo2020/">Vote!</a> -->
		<?php if ($this->user->userID == 1 || $this->userCanValidate) { ?>
			Hello. Perhaps you'd like to <a href="/survey/votervalidation/<?php echo $this->survey->surveyID; ?>/">validate some voters</a>?
		<?php } else { ?>
			Please visit <a href="https://register.ipo.vote/">register.ipo.vote</a> to acquire a ballot.
		<?php } ?>
	</div>
</div>