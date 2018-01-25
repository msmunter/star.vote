<?php
/* DEBUG SECTION */
/*if ($this->hasVoted) {
	echo '<div>[DEBUG: Has voted]</div>';
} else echo '<div>[DEBUG: Has NOT voted]</div>';*/
//$this->debug($this->voterID);
//$this->debug($this->poll);
/* END DEBUG */
?>
<div id="pollTitle">
	Poll: <?php echo $this->poll->question; ?>
</div>
<input type="hidden" id="pollID" value="<?php echo $this->poll->pollID; ?>" />
<div id="statusMsg" class="hidden"></div>
<div class="clear"></div>
<div id="voteInput" class="bigContainer">
<?php
if ($this->poll) {
	if ($this->hasVoted) {
		// Have voted
		echo '<div class="bigContainerTitle">How you voted</div>';
		echo '<div class="bigContainerInner">';
		include_once('view/poll/yourvote.view.php');
		echo '<button id="showResultsButton" data-inline="inline" onclick="showResults()">Update Results</button>';
		echo '</div>';
	} else {
		echo '<div class="bigContainerTitle">Place your vote</div>';
		echo '<div class="bigContainerInner">';
		include_once('view/poll/voteinput.view.php');
		echo '<button id="voteButton" data-inline="inline" onclick="vote()">Vote!</button>';
		echo '<button id="showResultsButton" data-inline="inline" onclick="showResults()">Show Results</button>';
		echo '</div>';
	}
	?>
	</div>
	<div id="pollResults" class="<?php if (!$this->hasVoted) echo ' hidden'; ?>">
		<?php include('view/poll/resultsactual.view.php');?>
	</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Share</div>
		<div class="bigContainerInner">
			<input type="text" id="shareURLInput" name="shareURLInput" data-mini="true" data-inline="true" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>/poll/results/<?php echo $this->poll->pollID; ?>/" />
		</div>
	</div>
	<?php
} else {
	echo 'ERROR: '.$this->error;
}
?>