<?php if ($this->user->userID != '' && $this->user->userID == $this->survey->userID) { ?>
	<input type="hidden" id="surveyID" value="<?php echo $this->surveyID; ?>" />
	<div id="statusMsg"></div>
	<div class="clear"></div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Generate Voter Keys for "<a href="/<?php
			if ($this->survey->customSlug) {
				echo $this->survey->customSlug;
			} else echo 'survey/'.$this->survey->surveyID;
		?>/"><?php echo $this->survey->title; ?></a>"</div>
		<div class="bigContainerInner">
			<input type="number" id="numKeys" value="1" min="1" max="999999" />
			<button id="generateKeysButton" data-inline="inline" data-mini="mini" onclick="generateVoterKeys()">Generate Keys</button>
		</div>
	</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Existing Voter Keys for "<a href="/<?php
			if ($this->survey->customSlug) {
				echo $this->survey->customSlug;
			} else echo 'survey/'.$this->survey->surveyID;
		?>/"><?php echo $this->survey->title; ?></a>"</div>
		<div class="bigContainerInner" id="existingVoterKeys">
			<?php include('view/survey/existingvoterkeys.view.php'); ?>
		</div>
	</div>
<?php } else { ?>
	ERROR: not authorized to edit survey "<a href="/
	<?php
	if ($this->survey->customSlug) {
		echo $this->survey->customSlug;
	} else {
		echo 'survey/'.$this->survey->surveyID;
	}
	?>
	/"><?php echo $this->survey->title; ?></a>"
<?php } ?>