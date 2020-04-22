<div id="yourVoterID">Your voter ID: <span id="yourVoterIDActual"><?php echo $this->voter->voterID; ?></span></div>
<div class="clear"></div>
<div id="yourVoteTime">Voted: <?php echo $this->yourVoteTime; ?></div>
<table class="yourVoteTable">
<?php foreach ($this->survey->polls as $poll) { ?>
	<tr><th colspan="3" class="pollHeader"><?php echo $poll->question; ?></th></tr>
	<tr><th>#</th><th>Option</th><th>Vote</th></tr>
	<?php $i=0; ?>
	<?php 
	//foreach ($this->yourVotes[$poll->pollID] as $answer) {
	foreach ($poll->answers as $answer) {
		$i++;
		//echo '<tr><td class="orderCell">'.$i.'</td><td>'.$answer->text.'</td><td class="number">'.$answer->vote.'</td></tr>';
		echo '<tr><td class="orderCell">'.$i.'</td><td>'.$answer->text.'<br /><div class="pollSubtext">'.$answer->subtext.'</div></td><td class="number">'.$this->voteArray[$answer->answerID].'</td></tr>';
	}
	echo '<tr><td colspan="3" class="voterResultsTableSpacer"></td></tr>';
	?>
<?php } ?>
</table>
<img src="https://<?php echo $_SERVER['SERVER_NAME']; ?>/web/images/qr_voterid/<?php echo $this->voter->voterID; ?>.png" alt="<?php echo $this->voter->voterID; ?>"/>