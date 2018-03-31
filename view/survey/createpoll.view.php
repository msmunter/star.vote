<?php if ($this->user->userID > 0 && $this->user->userID == $this->survey->userID) { ?>
	<?php if ($this->survey->votes < 1) { ?>
		<div class="bigContainer">
			<div class="bigContainerTitle">Add Poll To Survey "<?php echo $this->survey->title; ?>"</div>
			<div class="bigContainerInner">
				<div id="statusMsg" class="hidden"></div>
				<div class="clear"></div>
				<input type="hidden" id="surveyID" value="<?php echo $this->survey->surveyID; ?>" />
				<input type="hidden" id="surveySlug" value="<?php echo $this->survey->customSlug; ?>" />
				<div class="ui-field-contain">
					<label for="pollQuestion">Poll Question:</label>
					<input type="text" data-clear-btn="true" id="pollQuestion" name="pollQuestion" placeholder="What is this poll about?"></input>
				</div>
				<form id="pollAnswers">
					<?php 
						for ($qNum = 1;$qNum <= 3;$qNum++) {
							$this->answerID = $qNum;
							include('view/poll/pollinput.view.php');
						}
					?>
				</form>
				<div id="pollButtonContainer">
					<button id="createPollButton" data-inline="inline" onclick="createPoll()">Create Poll</button>
				</div>
			</div>
		</div>
	<?php } else { ?>
		Voting has already started, no more polls can be added.
	<?php } ?>
<?php } else { ?>
	You are not authorized to edit this poll.
<?php } ?>