Poll Results
<?php
/* DEBUG SECTION */
if ($this->hasVoted) {
	echo '<div>[DEBUG: Has voted]</div>';
} else echo '<div>[DEBUG: Has NOT voted]</div>';
//$this->debug($this->voterID);
//$this->debug($this->poll);
/* END DEBUG */
?>
<p>
	<?php
	if ($this->poll) {
		if (!$this->hasVoted) { ?>
			<div id="voteInput">
				<?php include_once('view/poll/voteinput.view.php'); ?>
			</div>
			<button id="voteButton" data-inline="inline" onclick="vote()">Vote!</button>
		<?php } ?>
		
		<div id="pollResults">
			<div id="pollResultsTitle">Results for "<?php echo $this->poll->question; ?>"</div>
			<?php foreach ($this->poll->answers as $answer) { ?>
				<div><?php echo $answer->text; ?>: <?php echo $answer->points; ?> points, <?php echo $answer->votes; ?> votes</div>
			<?php } ?>
		</div>
		<?php
	} else {
		echo 'ERROR: '.$this->error;
	}
	?>
</p>