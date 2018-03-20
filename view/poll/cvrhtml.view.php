Download: <a class="ui-btn ui-mini ui-btn-inline ui-corner-all" href="/poll/csv/<?php echo $this->poll->pollID; ?>/">CSV</a>
<table id="cvrTable">
<?php
$headerArray = array('voterID', 'voteTime', 'pollID');
$answerCount = count($this->poll->answers);
// Push answers out
echo '<tr><th>Voter ID</th><th>Timestamp</th><th>Poll ID</th>';
foreach ($this->poll->answers as $answer) {
	//array_push($headerArray, $answer->text);
	echo '<th>'.$answer->text.'</th>';
}
echo '</tr>';
// Process ballots
foreach ($this->poll->processedBallots as $voterID => $ballot) {
	echo '<tr';
	if ($_COOKIE['voterID'] == $voterID) echo ' class="yourVoteRow"';
	echo '>';
	echo '<td>'.$voterID.'</td>';
	echo '<td>'.$ballot['voteTime'].'</td>';
	echo '<td>'.$ballot['pollID'].'</td>';
	foreach ($this->poll->answers as $answer) {
		echo '<td>'.$ballot['votes'][$answer->answerID].'</td>';
	}
	echo '</tr>';
}
?>
</table>