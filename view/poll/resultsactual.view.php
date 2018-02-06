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
		<table id="runoffResultsTable">
			<?php 
			// Figure out multi-way tie here, info comes from poll.controller
			if ($this->poll->runoffResults['tie']) {
				if ($this->poll->runoffResults['tieEndsAt'] > 2) {
					// Multi-way tie
					echo '<tr class="noTopBorder"><td colspan="3">Tie between '.$this->poll->runoffResults['tieEndsAt'].' questions, '.$this->poll->runoffResults['first']['question'].' and '.$this->poll->runoffResults['second']['question'].' with '.$this->poll->runoffResults['first']['votes'].' votes each</td></tr>';
				} else {
					// Two-way tie
					?>
					<tr class="headerRow"><th>#</th><th>Option</th><th>Voters</th></tr>
					
					<tr><td class="rankCell winner">1</td><td><?php echo $this->poll->runoffResults['first']['question']; ?></td><td class="number"><?php echo $this->poll->runoffResults['first']['votes']; ?></td></tr>
					
					<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="3"><div class="barGraph" style="width: <?php echo number_format(($this->poll->runoffResults['first']['votes']/$this->poll->totalVoterCount*100), 2); ?>%;"><div class="barGraphData"><?php echo number_format(($this->poll->runoffResults['first']['votes']/$this->poll->totalVoterCount*100), 2); ?>% (<?php echo $this->poll->runoffResults['first']['votes']; ?>/<?php echo $this->poll->totalVoterCount; ?>)</div></div></td></tr>
					
					<tr><td class="rankCell winner">1</td><td><?php echo $this->poll->runoffResults['second']['question']; ?></td><td class="number"><?php echo $this->poll->runoffResults['second']['votes']; ?></td></tr>
					
					<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="3"><div class="barGraph" style="width: <?php echo number_format(($this->poll->runoffResults['second']['votes']/$this->poll->totalVoterCount*100), 2); ?>%;"><div class="barGraphData"><?php echo number_format(($this->poll->runoffResults['second']['votes']/$this->poll->totalVoterCount*100), 2); ?>% (<?php echo $this->poll->runoffResults['second']['votes']; ?>/<?php echo $this->poll->totalVoterCount; ?>)</div></div></td></tr>
					<?php
					//echo '<tr class="noTopBorder"><td colspan="3">Tie between '. $this->poll->runoffResults['first']['question'].' and '.$this->poll->runoffResults['second']['question'].' with '.$this->poll->runoffResults['first']['votes'].' votes each</td></tr>';
				}
			} else {
				?>
				<tr class="headerRow"><th>#</th><th>Option</th><th>Votes</th></tr>
				
				<tr><td class="rankCell winner">1</td><td><?php echo $this->poll->runoffResults['first']['question']; ?></td><td class="number"><?php echo $this->poll->runoffResults['first']['votes']; ?></td></tr>
				
				<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="3"><div class="barGraph" style="width: <?php echo number_format(($this->poll->runoffResults['first']['votes']/$this->poll->totalVoterCount*100), 2); ?>%;"><div class="barGraphData"><?php echo number_format(($this->poll->runoffResults['first']['votes']/$this->poll->totalVoterCount*100), 2); ?>% (<?php echo $this->poll->runoffResults['first']['votes']; ?>/<?php echo $this->poll->totalVoterCount; ?>)</div></div></td></tr>
				
				<tr><td class="rankCell">2</td><td><?php echo $this->poll->runoffResults['second']['question']; ?></td><td class="number"><?php echo $this->poll->runoffResults['second']['votes']; ?></td></tr>
				
				<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="3"><div class="barGraph" style="width: <?php echo number_format(($this->poll->runoffResults['second']['votes']/$this->poll->totalVoterCount*100), 2); ?>%;"><div class="barGraphData"><?php echo number_format(($this->poll->runoffResults['second']['votes']/$this->poll->totalVoterCount*100), 2); ?>% (<?php echo $this->poll->runoffResults['second']['votes']; ?>/<?php echo $this->poll->totalVoterCount; ?>)</div></div></td></tr>
				
				<?php
			}
		?>
			<tr><td class="rankCell"></td><td>No Preference</td><td class="number"><?php echo $this->poll->noPreferenceCount; ?></td></tr>
			
			<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="3"><div class="barGraph" style="width: <?php echo number_format(($this->poll->noPreferenceCount/$this->poll->totalVoterCount*100), 2); ?>%;"><div class="barGraphData"><?php echo number_format(($this->poll->noPreferenceCount/$this->poll->totalVoterCount*100), 2); ?>% (<?php echo $this->poll->noPreferenceCount; ?>/<?php echo $this->poll->totalVoterCount; ?>)</div></div></td></tr>
			</table>
			<?php
			// The unpreferred
			/*if ($this->poll->noPreferenceCount > 0) {
				echo '<div id="preferenceText">'.$this->poll->noPreferenceCount.' expressed no preference</div>';
			} else {
				echo '<div id="preferenceText">All voters expressed a preference</div>';
			}*/
			?>
		<div class="clear"></div>
	</div>
</div>
<div class="clear"></div>
<?php
//echo '<pre>Runoff Results: ';print_r($this->poll->runoffResults);echo '</pre>';
//echo '<pre>Selection Results: ';print_r($this->poll->topAnswers);echo '</pre>';
?>