<div>Poll Results</div>
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
<p>
	<?php
	if ($this->poll) {
		if ($this->hasVoted) {
			// Have voted
			echo '<div id="voteInput">';
			include_once('view/poll/yourvote.view.php');
			echo '</div>';
			echo '<button id="showResultsButton" data-inline="inline" onclick="showResults()">Update Results</button>';
		} else {
			echo '<div id="voteInput">';
			include_once('view/poll/voteinput.view.php');
			echo '</div><button id="voteButton" data-inline="inline" onclick="vote()">Vote!</button>';
			echo '<button id="showResultsButton" data-inline="inline" onclick="showResults()">Show Results</button>';
		}
		?>
		<div id="pollResults" class="<?php if (!$this->hasVoted) echo ' hidden'; ?>">
			<?php include('view/poll/resultsactual.view.php');?>
		</div>
		<?php
	} else {
		echo 'ERROR: '.$this->error;
	}
	?>
</p>