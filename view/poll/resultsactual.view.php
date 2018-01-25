<div class="bigContainer">
	<div class="bigContainerTitle">Results for "<?php echo $this->poll->question; ?>"</div>
	<div class="bigContainerInner">
		<div id="pollResultsContainer">
		<div>Scores (Top two advance to runoff):</div>
		<?php
		foreach ($this->poll->topAnswers as $answer) {
			$answerNum++;
			if ($answerNum < 3) {
				echo '<div class="answerResults advances">';
			} else {
				echo '<div class="answerResults">';
			}
			echo $answer->text.': '.$answer->points.' points</div><div class="clear"></div>';
		}
		?>
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
	</div>
</div>
<?php
//echo '<pre>Selection Results: ';print_r($this->poll->topAnswers);echo '<br />Runoff Results: ';print_r($this->poll->runoffResults);echo '</pre>';
//echo '<pre>Selection Results: ';print_r($this->poll->topAnswers);echo '</pre>';
?>