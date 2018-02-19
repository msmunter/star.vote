<table id="cvrTable">
<?php
$headerArray = array('voterID', 'voteTime', 'pollID');
$answerCount = count($this->poll->answers);
// Push answers onto header array
$outText .= '<tr><th>Voter ID</th><th>Timestamp</th><th>Poll ID</th>';
foreach ($this->poll->answers as $answer) {
	//array_push($headerArray, $answer->text);
	$outText .= '<th>'.$answer->text.'</th>';
}
$outText .= '</tr>';
// Process ballots
foreach ($this->poll->ballots as $ballot) {
	if (empty($currentBallot)) {
		// First run through
		$currentBallot = $ballot;
		//$outArray = array($currentBallot->voterID, $currentBallot->voteTime, $currentBallot->pollID, $currentBallot->vote);
		$outText .= '<tr';
		if ($_COOKIE['voterID'] == $currentBallot->voterID) $outText .= ' class="yourVoteRow"';
		$outText .= '><td>'.$currentBallot->voterID.'</td><td>'.$currentBallot->voteTime.'</td><td>'.$currentBallot->pollID.'</td><td>'.$currentBallot->vote.'</td>';
		$voteCount = 1;
	} else {
		// Subsequent runs through
		if ($currentBallot->voterID == $ballot->voterID) {
			if ($voteCount >= $answerCount) {
				// Duplicate, treat as new voter
				//$out = array_merge($out, $outArray);
				$outText .= '</tr>';
				// Start as if first run
				$currentBallot = $ballot;
				//$outArray = array($currentBallot->voterID, $currentBallot->voteTime, $currentBallot->pollID, $currentBallot->vote);
				$outText .= '<tr';
				if ($_COOKIE['voterID'] == $currentBallot->voterID) $outText .= ' class="yourVoteRow"';
				$outText .= '><td>'.$currentBallot->voterID.'</td><td>'.$currentBallot->voteTime.'</td><td>'.$currentBallot->pollID.'</td><td>'.$currentBallot->vote.'</td>';
				$voteCount = 1;
			} else {
				// Same voter, keep pushing votes onto array
				//array_push($outArray, $ballot->vote);
				$outText .= '<td>'.$ballot->vote.'</td>';
				$voteCount++;
			}
		} else {
			// New voter, write previous line
			//$out = array_merge($out, $outArray);
			$outText .= '</tr>';
			// Start as if first run
			$currentBallot = $ballot;
			//$outArray = array($currentBallot->voterID, $currentBallot->voteTime, $currentBallot->pollID, $currentBallot->vote);
			$outText .= '<tr';
			if ($_COOKIE['voterID'] == $currentBallot->voterID) $outText .= ' class="yourVoteRow"';
			$outText .= '><td>'.$currentBallot->voterID.'</td><td>'.$currentBallot->voteTime.'</td><td>'.$currentBallot->pollID.'</td><td>'.$currentBallot->vote.'</td>';
			$voteCount = 1;
		}
	}
}
// Do the last ballot
//$outText .= '</tr><tr><td>'.$currentBallot->voterID.'</td><td>'.$currentBallot->voteTime.'</td><td>'.$currentBallot->pollID.'</td><td>'.$currentBallot->vote.'</td></tr>';
echo $outText;
?>
</table>