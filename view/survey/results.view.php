<input type="hidden" id="surveyID" value="<?php echo $this->survey->surveyID; ?>" />
<div id="statusMsg" class="hidden"></div>
<div class="clear"></div>
<?php if ($this->user->userID != '' && $this->user->userID == $this->survey->userID) { ?>
	<div class="yourPollMsg">Your survey: "<?php echo $this->survey->title; ?>"
	<?php
	if ($this->survey->verifiedVoting) { ?>
		 - <a href="/survey/voterkeys/<?php echo $this->survey->surveyID; ?>/">Voter Keys</a>
	<?php } ?>
	- <a href="/survey/votervalidation/<?php echo $this->survey->surveyID; ?>/">Validate Voters</a>
	</div>
<?php } ?>
<?php
if ($this->survey) {
	if ($this->user->userID > 0 && $this->user->userID == $this->survey->userID) {
		// This user's survey ?>
		<div class="bigContainer">
			<div class="bigContainerTitle">Polls in survey: "<?php echo $this->survey->title; ?>" <?php if ($this->survey->votes < 1) { ?><a class="ui-btn ui-mini ui-btn-inline ui-btn-corner-all" href="/survey/createpoll/<?php echo $this->survey->surveyID; ?>/">Add Poll</a><?php } ?></div>
			<div class="bigContainerInner">
				<?php if (!empty($this->startEndString)) {?><div class="startEndString"><?php echo $this->startEndString; ?></div><?php } ?>
				<div class="clear"></div>
				<div id="voteInput">
					<?php include_once('view/survey/voteinput.view.php'); ?>
				</div>
				<div class="clear"></div>
				<div id="prevNextPollButtons">
					<button id="prevPollButton" disabled="disabled" data-inline="inline" data-mini="mini" onclick="changePoll('d')">&larr;</button>Part <span id="pollIndex">1</span> of <?php echo count($this->survey->polls); ?><button id="nextPollButton" data-inline="inline" data-mini="mini" onclick="changePoll('u')">&rarr;</button>
				</div>
				<button id="showResultsButton" data-inline="inline" onclick="showResults()">Show Results</button>
			</div>
		</div>
	<?php } else { ?>
		<div id="voteInstructions">
			<ul>
				<li>You may score as many candidates as you like from 0 (worst) to 5 (best).</li>
				<li>If you don't have a preference you may give the same score to multiple candidates.</li>
				<li>The two highest-scoring candidates are finalists.</li>
				<li>The finalist preferred by the majority wins.</li>
			</ul>
			<a href="/docs/how/" target="_blank">How Does STAR Voting Work?</a>
		</div>
		<?php if ($this->hasVoted) { ?>
		<div class="bigContainer">
			<div class="bigContainerTitle">Your vote for "<?php echo $this->survey->title; ?>"</div>
			<div class="bigContainerInner">
				<?php if (!empty($this->startEndString)) {?><div class="startEndString"><?php echo $this->startEndString; ?></div><?php } ?>
				<div class="clear"></div>
				<div id="voteInput">
					<?php include_once('view/survey/yourvote.view.php'); ?>
				</div>
				<div class="clear"></div>
				<?php if ($this->survey->kioskMode) { ?>
					<button id="resetVoterButton" data-inline="inline" onclick="resetVoter()">Reset Voter</button>
				<?php } ?>
				<?php //if ($this->survey->printVote) { ?>
					<button id="reprintVoteButton" data-inline="inline">Print/Save Vote</button>
				<?php //} ?>
				<div>
					Thank you for voting!
				</div>
			</div>
		</div>
		<?php } else { ?>
		<div class="bigContainer">
			<div class="bigContainerTitle">Election: "<?php echo $this->survey->title; ?>" (<?php echo count($this->survey->polls); ?>-Part)</div>
			<div class="bigContainerInner">
				<?php if ($this->survey->verifiedVoting) { ?>
					<label for="voterKey">Voter Key:</label>
					<input id="voterKey" />
				<?php } ?>
				<?php if (!empty($this->startEndString)) {?><div class="startEndString"><?php echo $this->startEndString; ?></div><?php } ?>
				<div class="clear"></div>
				<?php if ($this->voter->voterfileID) { ?>
					<div id="voterInfoInput">
						<?php if (!$this->voter->voterfileID) include_once('view/survey/voterinfoinput.view.php'); ?>
						<?php include_once('view/survey/votervalimginput.view.php'); ?>
					</div>
					<div id="statusMsg2" class="hidden"></div>
					<div class="clear"></div>
					<div id="voteInput">
						<?php include_once('view/survey/voteinput.view.php'); ?>
					</div>
					<div class="clear"></div>
					<div id="prevNextPollButtons">
						<button id="prevPollButton" disabled="disabled" data-inline="inline" data-mini="mini" onclick="changePoll('d')">&larr;</button>Part <span id="pollIndex">1</span> of <?php echo count($this->survey->polls); ?><button id="nextPollButton" data-inline="inline" data-mini="mini" onclick="changePoll('u')">&rarr;</button>
					</div>
					<div id="voteShowResultsButtons">
						<button disabled="disabled" id="voteButton" data-inline="inline" onclick="vote()">Vote!</button>
						<!-- <button <?php //if ($this->survey->verifiedVoting || ($this->survey->verbage == 'el' && $this->survey->votingWindowDirection != 'after')) echo 'disabled="disabled" '; ?>id="showResultsButton" data-inline="inline" onclick="showResults()">Show Results</button> -->
					</div>
					<?php if ($this->survey->kioskMode) { ?>
						<button class="hidden" id="resetVoterButton" data-inline="inline" onclick="resetVoter()">Reset Voter</button>
						
					<?php } ?>
					<?php //if ($this->survey->printVote) { ?>
						<button class="hidden" id="reprintVoteButton" data-inline="inline">Print/Save Vote</button>
					<?php //} ?>
					<div id="youVoted">
						Thank you for voting! A confirmation email has been sent to you.
					</div>
				<?php } else { ?>
					Invalid or missing voter ID; please visit <a href="https://register.ipo.vote/">register.ipo.vote</a>.<br />Once registered you will receive an email containing a link to your ballot.
				<?php } ?>
			</div>
		</div>
		<?php }
	} ?>
	<?php //if ($this->survey->verbage == 'el' && $this->survey->kioskMode == false || $this->survey->verbage != 'el' || ($this->user->userID > 0 && $this->survey->userID == $this->user->userID) || ($this->survey->verbage == 'el' && $this->survey->votingWindowDirection == 'after')) { 
	if ($this->user->userID > 0 && $this->survey->userID == $this->user->userID) {
	?>
		<div id="pollResults" class="bigContainer<?php if (!$this->hasVoted) echo ' hidden'; ?>">
			<div class="bigContainerTitle">Results for "<?php echo $this->survey->title; ?>"</div>
			<div class="bigContainerInner">
				<div id="pollResultsActual">
					<!-- AJAX -->
				</div>
				<button id="showResultsButton" data-inline="inline" onclick="showResults()">Update Results</button>
			</div>
		</div>
	<?php } ?>
	
	<?php //if ($this->survey->kioskMode == false || $this->user->userID != 0 && $this->user->userID == $this->survey->userID) { 
	if ($this->user->userID > 0 && $this->survey->userID == $this->user->userID) {
	?>
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
	Survey not found
<?php } ?>