<div id="pollResultsContainer">
	<div>Scores for <?php echo $this->poll->totalVoterCount; ?> voter<?php if ($this->poll->totalVoterCount != 1) echo 's'; ?> (Top two advance):</div>
	<table id="resultsTable">
		<tr><th>Option</th><th>Points</th></tr>
		<?php
		foreach ($this->poll->topAnswers as $answer) {
			if ($answer->answerID == $this->poll->runoffResults['first']['answerID'] || $answer->answerID == $this->poll->runoffResults['second']['answerID']) {
				echo '<tr class="answerResults advances"><td>';
			} else {
				echo '<tr class="answerResults"><td>';
			}
			echo $answer->text.'</td><td class="alignright">'.$answer->points.'</td></tr>';
		}
		?>
	</table>
</div>
<div id="resultsArrow">&rarr;</div>

<div id="runoffResults">
	Runoff:<br />
	<?php 
	// Figure out multi-way tie here, info comes from poll.controller
	if ($this->poll->runoffResults['tie']) {
		if ($this->poll->runoffResults['tieEndsAt'] > 2) {
			// Multi-way tie
			echo 'Tie between '.$this->poll->runoffResults['tieEndsAt'].' questions, '.$this->poll->runoffResults['first']['question'].' and '.$this->poll->runoffResults['second']['question'].' with '.$this->poll->runoffResults['first']['votes'].' votes each';
		} else {
			// Two-way tie
			echo 'Tie between '. $this->poll->runoffResults['first']['question'].' and '.$this->poll->runoffResults['second']['question'].' with '.$this->poll->runoffResults['first']['votes'].' votes each';
		}
	} else {
		?>
		1st: <?php echo $this->poll->runoffResults['first']['question']; ?>, preferred by <?php echo $this->poll->runoffResults['first']['votes']; ?><br />
		<?php if ($this->poll->runoffResults['second']['votes'] == 0) { ?>
			No others preferred
		<?php } else { ?>
			2nd: <?php echo $this->poll->runoffResults['second']['question']; ?>, preferred by <?php echo $this->poll->runoffResults['second']['votes']; ?>
		<?php } ?>
		<?php
	}
	?>
</div>
<div class="clear"></div>
<?php
//echo '<pre>Runoff Results: ';print_r($this->poll->runoffResults);echo '</pre>';
//echo '<pre>Selection Results: ';print_r($this->poll->topAnswers);echo '</pre>';
?>