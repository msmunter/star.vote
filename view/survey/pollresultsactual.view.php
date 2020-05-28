<?php //$this->debug($this->survey->placedPolls); // DEBUG ONLY!!! ?>
<?php foreach ($this->survey->polls as $mainPoll) { ?>
	<?php
	foreach ($this->survey->placedPolls[$mainPoll->pollID] as $placeNumber => $currentPoll) {
		$rank = 0;
		?>
		<div class="surveyResultsPollTitle"><?php echo $currentPoll->question; ?>
		<?php if (count($this->survey->placedPolls[$mainPoll->pollID]) > 1) {
			echo ' - '.$placeNumber;
			if ($placeNumber == 1) {
				echo 'st';
			} else if ($placeNumber == 2) {
				echo 'nd';
			} else if ($placeNumber == 3) {
				echo 'rd';
			} else echo 'th';
			echo ' Winner';
		} ?>
		</div>
		<div class="clear"></div>
		<div class="floatleft">	
			<div>Selection Phase (Top two advance):</div>
			<div id="pollResultsContainer">
				<div><?php echo $currentPoll->totalVoterCount; ?> voter<?php if ($currentPoll->totalVoterCount != 1) echo 's'; ?></div>
				<table id="resultsTable">
					<tr class="headerRow"><th>Candidate</th><th>Points</th></tr>
					<?php
					foreach ($currentPoll->topAnswers as $answer) {
						$answer->pointsPercent = number_format($answer->points / $currentPoll->totalPointCount * 100, 1);
						$answer->avgPercent = number_format($answer->avgVote / 5 * 100, 1);
						$rank++;
						// if ($answer->answerID == $currentPoll->runoffResults['first']['answerID'] || $answer->answerID == $currentPoll->runoffResults['second']['answerID']) {
						// 	echo '<tr class="answerResults"><td class="rankCell advances">'.$rank.'</td>';
						// } else {
						// 	echo '<tr class="answerResults"><td class="rankCell">'.$rank.'</td>';
						// }
						echo '<td>'.$answer->text.'</td><td class="number">'.$answer->points.'</td></tr>';
						echo '<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="3"><div class="barGraph" style="width: '.$answer->avgPercent.'%;"><div class="barGraphData">'.$answer->avgPercent.'% ('.number_format($answer->avgVote, 1).'/5)</div></div></td></tr>';
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
					if ($currentPoll->runoffResults['tie']) {
						if ($currentPoll->runoffResults['tieEndsAt'] > 2) {
							// Multi-way tie
							echo '<tr class="noTopBorder"><td colspan="3">Tie between '.$currentPoll->runoffResults['tieEndsAt'].' questions, '.$currentPoll->runoffResults['first']['question'].' and '.$currentPoll->runoffResults['second']['question'].' with '.$currentPoll->runoffResults['first']['votes'].' votes each</td></tr>';
						} else {
							// Two-way tie
							?>
							<tr class="headerRow"><th>Candidate</th><th>Voters</th></tr>
							
							<tr><td><?php echo $currentPoll->runoffResults['first']['question']; ?></td><td class="number"><?php echo $currentPoll->runoffResults['first']['votes']; ?></td></tr>
							
							<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="2"><div class="barGraph" style="width: <?php echo number_format(($currentPoll->runoffResults['first']['votes']/$currentPoll->totalVoterCount*100), 2); ?>%;"><div class="barGraphData"><?php echo number_format(($currentPoll->runoffResults['first']['votes']/$currentPoll->totalVoterCount*100), 2); ?>%</div></div></td></tr>
							
							<tr><td><?php echo $currentPoll->runoffResults['second']['question']; ?></td><td class="number"><?php echo $currentPoll->runoffResults['second']['votes']; ?></td></tr>
							
							<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="2"><div class="barGraph" style="width: <?php echo number_format(($currentPoll->runoffResults['second']['votes']/$currentPoll->totalVoterCount*100), 2); ?>%;"><div class="barGraphData"><?php echo number_format(($currentPoll->runoffResults['second']['votes']/$currentPoll->totalVoterCount*100), 2); ?>%</div></div></td></tr>
							<?php
							//echo '<tr class="noTopBorder"><td colspan="3">Tie between '. $currentPoll->runoffResults['first']['question'].' and '.$currentPoll->runoffResults['second']['question'].' with '.$currentPoll->runoffResults['first']['votes'].' votes each</td></tr>';
						}
					} else {
						?>
						<tr class="headerRow"><th>Candidate</th><th>Votes</th></tr>
						
						<tr><td><?php echo $currentPoll->runoffResults['first']['question']; ?></td><td class="number"><?php echo $currentPoll->runoffResults['first']['votes']; ?></td></tr>
						
						<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="2"><div class="barGraph" style="width: <?php echo number_format(($currentPoll->runoffResults['first']['votes']/$currentPoll->totalVoterCount*100), 2); ?>%;"><div class="barGraphData"><?php echo number_format(($currentPoll->runoffResults['first']['votes']/$currentPoll->totalVoterCount*100), 2); ?>%</div></div></td></tr>
						
						<tr><td><?php echo $currentPoll->runoffResults['second']['question']; ?></td><td class="number"><?php echo $currentPoll->runoffResults['second']['votes']; ?></td></tr>
						
						<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="2"><div class="barGraph" style="width: <?php echo number_format(($currentPoll->runoffResults['second']['votes']/$currentPoll->totalVoterCount*100), 2); ?>%;"><div class="barGraphData"><?php echo number_format(($currentPoll->runoffResults['second']['votes']/$currentPoll->totalVoterCount*100), 2); ?>%</div></div></td></tr>
						
						<?php
					}
				?>
					<tr><td>No Preference</td><td class="number"><?php echo $currentPoll->noPreferenceCount; ?></td></tr>
					
					<tr class="answerResults barGraphTr"><td class="barGraphTd" colspan="2"><div class="barGraph" style="width: <?php echo number_format(($currentPoll->noPreferenceCount/$currentPoll->totalVoterCount*100), 2); ?>%;"><div class="barGraphData"><?php echo number_format(($currentPoll->noPreferenceCount/$currentPoll->totalVoterCount*100), 2); ?>%</div></div></td></tr>
					</table>
					<?php
					// The unpreferred
					/*if ($currentPoll->noPreferenceCount > 0) {
						echo '<div id="preferenceText">'.$currentPoll->noPreferenceCount.' expressed no preference</div>';
					} else {
						echo '<div id="preferenceText">All voters expressed a preference</div>';
					}*/
					?>
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
		<div>
		<?php
		if ($currentPoll->runoffResults['tie']) {
			echo 'Tie, therefore no Condorcet winner';
		} else if ($currentPoll->condorcet === false) {
			echo $currentPoll->runoffResults['first']['question'].' did not win all pairings and therefore is not the Condorcet winner.';
		} else {
			echo 'STAR elected the Condorcet winner.';
		}
		?>
		</div>
		<div class="clear"></div>
	<?php } ?>
<?php } ?>