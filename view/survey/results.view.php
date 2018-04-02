<?php //$this->debug($this->survey); // DEBUG ONLY!!! ?>
<input type="hidden" id="surveyID" value="<?php echo $this->survey->surveyID; ?>" />
<div id="statusMsg" class="hidden"></div>
<div class="clear"></div>
<?php if ($this->user->userID != '' && $this->user->userID == $this->survey->userID) { ?>
	<div class="yourPollMsg">Your survey: "<?php echo $this->survey->title; ?>"
	<?php
	if ($this->survey->verifiedVoting) { ?>
		 - <a href="/survey/voterkeys/<?php echo $this->survey->surveyID; ?>/">Voter Keys</a>
	<?php } ?>
	</div>
<?php } ?>
<?php
if ($this->survey) {
	if ($this->user->userID > 0 && $this->user->userID == $this->survey->userID) {
		// This user's survey ?>
		<div class="bigContainer">
			<div class="bigContainerTitle">Polls in survey: "<?php echo $this->survey->title; ?>" <a class="ui-btn ui-mini ui-btn-inline ui-btn-corner-all" href="/survey/createpoll/<?php echo $this->survey->surveyID; ?>/">Add Poll</a></div>
			<div class="bigContainerInner">
				<?php if (!empty($this->startEndString)) {?><div class="startEndString"><?php echo $this->startEndString; ?></div><?php } ?>
				<div class="clear"></div>
				<div id="voteInput">
					<?php include_once('view/survey/voteinput.view.php'); ?>
				</div>
				<div id="prevNextPollButtons">
					<button id="prevPollButton" disabled="disabled" data-inline="inline" data-mini="mini" onclick="changePoll('d')">&larr;</button>Part <span id="pollIndex">1</span> of <?php echo count($this->survey->polls); ?><button id="nextPollButton" data-inline="inline" data-mini="mini" onclick="changePoll('u')">&rarr;</button>
				</div>
				<button id="showResultsButton" data-inline="inline" onclick="showResults()">Show Results</button>
			</div>
		</div>
	<?php } else { ?>
		<div id="voteInstructions">
			<ul>
				<li>You may score as many candidates as you like from 0 (no support) to 5 (max support).</li>
				<li>You may give the same score to multiple candidates.</li>
				<li>The two highest-scoring candidates are finalists.</li>
				<li>The finalist scored higher by more voters wins.</li>
			</ul>
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
			</div>
		</div>
		<?php } else { ?>
		<div class="bigContainer">
			<div class="bigContainerTitle">Survey "<?php echo $this->survey->title; ?>" (<?php echo count($this->survey->polls); ?>-Part)</div>
			<div class="bigContainerInner">
				<?php if ($this->survey->verifiedVoting) { ?>
					<label for="voterKey">Voter Key:</label>
					<input id="voterKey" />
				<?php } ?>
				<?php if (!empty($this->startEndString)) {?><div class="startEndString"><?php echo $this->startEndString; ?></div><?php } ?>
				<div class="clear"></div>
				<div id="voteInput">
					<?php include_once('view/survey/voteinput.view.php'); ?>
				</div>
				<div id="prevNextPollButtons">
					<button id="prevPollButton" disabled="disabled" data-inline="inline" data-mini="mini" onclick="changePoll('d')">&larr;</button>Part <span id="pollIndex">1</span> of <?php echo count($this->survey->polls); ?><button id="nextPollButton" data-inline="inline" data-mini="mini" onclick="changePoll('u')">&rarr;</button>
				</div>
				<div id="voteShowResultsButtons">
					<button <?php if ($this->survey->verifiedVoting || !$this->survey->inVotingWindow) echo 'disabled="disabled" '; ?>id="voteButton" data-inline="inline" onclick="vote()">Vote!</button>
					<button <?php if ($this->survey->verifiedVoting || ($this->survey->verbage == 'el' && $this->survey->votingWindowDirection != 'after')) echo 'disabled="disabled" '; ?>id="showResultsButton" data-inline="inline" onclick="showResults()">Show Results</button>
				</div>
			</div>
		</div>
		<?php }
	} ?>
	<div id="pollResults" class="bigContainer<?php if (!$this->hasVoted) echo ' hidden'; ?>">
		<div class="bigContainerTitle">Results for "<?php echo $this->survey->title; ?>"</div>
		<div class="bigContainerInner">
			<div id="pollResultsActual">
				<!-- AJAX -->
			</div>
			<button id="showResultsButton" data-inline="inline" onclick="showResults()">Update Results</button>
		</div>
	</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Share</div>
		<div class="bigContainerInner">
			<input type="text" id="shareURLInput" name="shareURLInput" data-mini="true" data-inline="true" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>/<?php if ($this->survey->customSlug != "") {echo $this->survey->customSlug;} else echo $this->survey->surveyID; ?>/" />
		</div>
	</div>
	
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
<?php } else { ?>
	Survey not found
<?php } ?>