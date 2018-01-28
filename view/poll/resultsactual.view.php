<div class="floatleft">	
	<div>Selection Phase (Top two advance):</div>
	<div id="pollResultsContainer">
		<div><?php echo $this->poll->totalVoterCount; ?> voter<?php if ($this->poll->totalVoterCount != 1) echo 's'; ?></div>
		<table id="resultsTable">
			<tr class="headerRow"><th>#</th><th>Option</th><th>Points</th><th>Average</th></tr>
			<?php
			foreach ($this->poll->topAnswers as $answer) {
				$answer->pointsPercent = number_format($answer->points / $this->poll->totalPointCount * 100, 1);
				$answer->avgPercent = number_format($answer->avgVote / 5 * 100, 1);
				$rank++;
				if ($answer->answerID == $this->poll->runoffResults['first']['answerID'] || $answer->answerID == $this->poll->runoffResults['second']['answerID']) {
					echo '<tr class="answerResults"><td class="rankCell advances">'.$rank.'</td>';
				} else {
					echo '<tr class="answerResults"><td class="rankCell">'.$rank.'</td>';
				}
				echo '<td>'.$answer->text.'</td><td class="number">'.$answer->points.'</td><td class="number">'.number_format($answer->avgVote, 1).'</td></tr>';
				echo '<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="4"><div class="barGraph" style="width: '.$answer->avgPercent.'%;"><div class="barGraphData">'.$answer->avgPercent.'% ('.number_format($answer->avgVote, 1).'/5)</div></div></td></tr>';
			}
			?>
		</table>
		<div class="clear"></div>
	</div>
</div>
<div id="resultsArrow">&rarr;</div>
<div class="floatleft">	
	<div>Runoff Phase (Single winner):<br /></div>
	<div id="runoffResults">
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
		<div class="clear"></div>
	</div>
</div>
<div class="clear"></div>
<?php
//echo '<pre>Runoff Results: ';print_r($this->poll->runoffResults);echo '</pre>';
//echo '<pre>Selection Results: ';print_r($this->poll->topAnswers);echo '</pre>';
?>