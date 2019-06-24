<input type="hidden" id="pollID" value="<?php echo $this->poll->pollID; ?>" />
<?php if ($this->user->userID != '' && $this->user->userID == $this->poll->userID) { ?>
	<div class="yourPollMsg">Your poll: "<?php echo $this->poll->question; ?>"
	<?php
	if (empty($this->poll->surveyID)) {
		if ($this->poll->verifiedVoting) { ?>
			 - <a href="/poll/voterkeys/<?php echo $this->poll->pollID; ?>/">Voter Keys</a>
		<?php }
	} else { ?>
		part of survey "<?php echo $this->survey->title; ?>"
	<?php } ?>
	</div>
<?php } ?>
<div id="voteInstructions">
	<ul>
		<li>You may score as many candidates as you like from 0 (no support) to 5 (max support).</li>
		<li>You may give the same score to multiple candidates.</li>
		<li>The two highest-scoring candidates are finalists.</li>
		<li>The finalist scored higher by more voters wins.</li>
		<?php if ($this->poll->oneVotePerIp) echo '<li>Votes are limited to one per IP address.</li>'; ?>
	</ul>
</div>
<div id="statusMsg" class="hidden"></div>
<div class="clear"></div>
<?php
if ($this->poll) { 
	if (empty($this->poll->surveyID)) {
		if ($this->user->userID == $this->poll->userID && $this->user->userID > 0) {
			// This user's poll ?>
			<div class="bigContainer">
				<div class="bigContainerTitle">What voters see: "<?php echo $this->poll->question; ?>"</div>
				<div class="bigContainerInner">
					<?php if (!empty($this->startEndString)) {?><div class="startEndString"><?php echo $this->startEndString; ?></div><?php } ?>
					<div class="clear"></div>
					<div id="voteInput">
						<?php include_once('view/poll/voteinput.view.php'); ?>
					</div>
					<div class="clear"></div>
					<button id="showResultsButton" data-inline="inline" onclick="showResults()">Show Results</button>
				</div>
			</div>
		<?php } else if ($this->hasVoted) { ?>
			<div class="bigContainer">
				<div class="bigContainerTitle">Your vote for "<?php echo $this->poll->question; ?>"</div>
				<div class="bigContainerInner">
					<?php if (!empty($this->startEndString)) {?><div class="startEndString"><?php echo $this->startEndString; ?></div><?php } ?>
					<div class="clear"></div>
					<div id="voteInput">
						<?php include_once('view/poll/yourvote.view.php'); ?>
					</div>
					<div class="clear"></div>
					<?php if ($this->poll->kioskMode) {?><button id="resetVoterButton" data-inline="inline" onclick="resetVoter()">Reset Voter</button><?php } ?>
				</div>
			</div>
		<?php } else { ?>
			<div class="bigContainer">
				<div class="bigContainerTitle">Vote on "<?php echo $this->poll->question; ?>"</div>
				<div class="bigContainerInner">
					<?php if (!empty($this->startEndString)) {?><div class="startEndString"><?php echo $this->startEndString; ?></div><?php } ?>
					<div class="clear"></div>
					<?php include_once('view/poll/voteinput.view.php'); ?>
					<div id="voteInput">
						<?php if ($this->poll->verifiedVoting && $this->poll->verifiedVotingType == "gkc") { ?>
							<label for="voterKey">Voter Key:</label>
							<input id="voterKey" />
						<?php } ?>
						<?php if ($this->poll->verifiedVotingType == "eml") { ?>
							<div>Must validate vote by email:</div>
							<input type="text" data-clear-btn="true" class="pollAnswer" name="verificationEmail" id="verificationEmail" placeholder="your@email.com" />
							<button id="testEmailButton" data-inline="inline" onclick="emailTest()">Test</button>
							<div class="clear"></div>
						<?php } ?>
					</div>
					<div class="clear"></div>
					<button <?php if (($this->poll->verifiedVoting && $this->poll->verifiedVotingType != "eml") || !$this->poll->inVotingWindow) echo 'disabled="disabled" '; ?>id="voteButton" data-inline="inline" onclick="vote()">Vote!</button>
					<?php if ($this->poll->blind == 0) { ?>
						<button <?php if ($this->poll->verifiedVoting) echo 'disabled="disabled" '; ?>id="showResultsButton" data-inline="inline" onclick="showResults()">Show Results</button>
					<?php } ?>
					<?php if ($this->poll->kioskMode) {?><button class="hidden" id="resetVoterButton" data-inline="inline" onclick="resetVoter()">Reset Voter</button><?php } ?>
				</div>
			</div>
		<?php } ?>
		<?php if ($this->poll->blind == 0 || ($this->user->userID == $this->poll->userID && $this->user->userID > 0)) { ?>
			<div id="pollResults" class="bigContainer<?php if (!$this->hasVoted) echo ' hidden'; ?>">
				<div class="bigContainerTitle">Results for "<?php echo $this->poll->question; ?>"</div>
				<div class="bigContainerInner">
					<div id="pollResultsActual">
						<?php include('view/poll/resultsactual.view.php');?>
					</div>
					<button id="showResultsButton" data-inline="inline" onclick="showResults()">Update Results</button>
				</div>
			</div>
		<?php } ?>
		<div class="bigContainer">
			<div class="bigContainerTitle">Share</div>
			<div class="bigContainerInner">
				<input type="text" id="shareURLInput" name="shareURLInput" data-mini="true" data-inline="true" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>/<?php if ($this->poll->customSlug != "") {echo $this->poll->customSlug;} else echo $this->poll->pollID; ?>/" />
			</div>
		</div>
		<?php if ($this->poll->blind == 0 || ($this->user->userID == $this->poll->userID && $this->user->userID > 0)) { ?>
			<div class="bigContainer">
				<div class="bigContainerTitle">Runoff Matrix</div>
				<div class="bigContainerInner">
					<div id="runoffMatrixContainer">
						<!-- AJAX -->
					</div>
					<button class="ui-btn ui-mini ui-btn-inline ui-corner-all" data-inline="true" id="runoffMatrixShowButton" onclick="showRunoffMatrix()">Show</button>
				</div>
			</div>

			<div class="bigContainer">
				<div class="bigContainerTitle">Ballot Record</div>
				<div class="bigContainerInner">
					<div id="ballotRecordContainer">
						<!-- AJAX -->
					</div>
					<button class="ui-btn ui-mini ui-btn-inline ui-corner-all" data-inline="true" id="ballotRecordShowButton" onclick="showCvrHtml()">Show</button>
				</div>
			</div>
		<?php } ?>
	<?php } else { ?>
		This poll is a part of a survey; it must be viewed there.
	<?php } ?>
<?php } else { ?>
	ERROR: Poll not found
<?php } ?>