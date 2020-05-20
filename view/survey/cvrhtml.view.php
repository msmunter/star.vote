<?php foreach($this->survey->polls as $zPoll) { ?>
	<div class="surveyResultsPollTitle"><?php echo $zPoll->question; ?></div>
	Download: <a class="ui-btn ui-mini ui-btn-inline ui-corner-all" href="/poll/csv/<?php echo $zPoll->pollID; ?>/">CSV</a>
	<table id="cvrTable">
	<?php
	$headerArray = array('voterID', 'voteTime', 'pollID');
	$answerCount = count($zPoll->answers);
	// Push answers out
	echo '<tr><th>Voter ID</th><th>Timestamp</th><th>Poll ID</th>';
	foreach ($zPoll->answers as $answer) {
		//array_push($headerArray, $answer->text);
		echo '<th>'.$answer->text.'</th>';
	}
	echo '</tr>';
	// Process ballots
	foreach ($zPoll->processedBallots as $voterID => $ballot) {
		echo '<tr';
		if ($_COOKIE['voterID'] == $voterID) echo ' class="yourVoteRow"';
		echo '>';
		echo '<td>'.$voterID.'</td>';
		echo '<td>'.$ballot['voteTime'].'</td>';
		echo '<td>'.$ballot['pollID'].'</td>';
		foreach ($zPoll->answers as $answer) {
			echo '<td>'.$ballot['votes'][$answer->answerID].'</td>';
		}
		echo '</tr>';
	}
	?>
	</table>
<?php } ?>