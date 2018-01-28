<?php
/* DEBUG SECTION */
/*if ($this->hasVoted) {
	echo '<div>[DEBUG: Has voted]</div>';
} else echo '<div>[DEBUG: Has NOT voted]</div>';*/
//$this->debug($this->voterID);
//$this->debug($this->poll);
/* END DEBUG */
?>
<input type="hidden" id="pollID" value="<?php echo $this->poll->pollID; ?>" />
<div id="statusMsg" class="hidden"></div>
<div class="clear"></div>
<?php
if ($this->poll) {
	if ($this->hasVoted) { ?>
		<div class="bigContainer">
			<div class="bigContainerTitle">Your vote for "<?php echo $this->poll->question; ?>"</div>
			<div class="bigContainerInner">
				<div id="voteInput">
					<?php include_once('view/poll/yourvote.view.php'); ?>
				</div>
				<button id="showResultsButton" data-inline="inline" onclick="showResults()">Update Results</button>
				
			</div>
		</div>
	<?php } else { ?>
		<div class="bigContainer">
			<div class="bigContainerTitle">Vote on "<?php echo $this->poll->question; ?>"</div>
			<div class="bigContainerInner">
				<div id="voteInput">
					<?php include_once('view/poll/voteinput.view.php'); ?>
				</div>
				<button id="voteButton" data-inline="inline" onclick="vote()">Vote!</button>
				<button id="showResultsButton" data-inline="inline" onclick="showResults()">Show Results</button>
			</div>
		</div>
	<?php } ?>
	<div id="pollResults" class="bigContainer<?php if (!$this->hasVoted) echo ' hidden'; ?>">
		<div class="bigContainerTitle">Results for "<?php echo $this->poll->question; ?>"</div>
		<div id="pollResultsActual" class="bigContainerInner">
			<?php include('view/poll/resultsactual.view.php');?>
		</div>
	</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Share</div>
		<div class="bigContainerInner">
			<input type="text" id="shareURLInput" name="shareURLInput" data-mini="true" data-inline="true" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>/<?php echo $this->poll->pollID; ?>/" />
		</div>
	</div>
	<?php
} else {
	echo 'ERROR: '.$this->error;
}
?>