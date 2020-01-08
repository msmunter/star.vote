<?php if ($this->user->userID == 1 || $this->user->userID == 2 || ($this->user->userID == $this->survey->userID && $this->user->userID != "")) { ?>
	<?php if ($this->survey) { ?>
		<input type="hidden" id="surveyID" value="<?php echo $this->survey->surveyID; ?>" />
		<div class="bigContainer">
			<div class="bigContainerTitle">
				Voter validation for <a href="/
				<?php
				if ($this->survey->customSlug) {
					echo $this->survey->customSlug;
				} else {
					echo 'survey/'.$this->survey->surveyID;
				}
				?>
				/"><?php echo $this->survey->title; ?></a>
			</div>
			<div class="bigContainerInner">
				<div>
					You have verified <span id="voterVerifiedCount"><?php echo $this->voterVerifiedCount; ?></span> out of <span id="voterCount"><?php echo $this->voterCount; ?></span> voters.
				</div>
				<p>
					<div class="ui-field-contain">
						<label for="voterID">Voter ID:</label>
						<input id="voterID" />
					</div>
					<div id="statusMsg" class="hidden"></div>
					<div class="clear"></div>
					<button id="validateVoterButton" data-inline="inline" onclick="validatevoter()">Validate</button>
				</p>
			</div>
		</div>
	<?php } else { ?>
		No survey specified
	<?php } ?>
	<!-- <div>Users 1, 2 and the election's creator can view this page.</div> -->
<?php } else { ?>
	Not authorized to view this page
<?php } ?>