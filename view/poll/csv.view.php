<?php
if ($this->error == '') {
	$fileName = 'starvote_ballots_'.$this->URLdata.'_'.date('YmdHis');
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.$fileName.'.csv');
	$out = fopen('php://output', 'w');
	$headerArray = array('voterID', 'voteTime', 'pollID');
	$answerCount = count($this->poll->answers);
	// Push answers onto header array
	foreach ($this->poll->answers as $answer) {
		array_push($headerArray, $answer->text);
	}
	fputcsv($out, $headerArray);
	// Process ballots
	foreach ($this->poll->ballots as $ballot) {
		if (empty($currentBallot)) {
			// First run through
			$currentBallot = $ballot;
			$outArray = array($currentBallot->voterID, $currentBallot->voteTime, $currentBallot->pollID, $currentBallot->vote);
			$voteCount = 1;
		} else {
			// Subsequent runs through
			if ($currentBallot->voterID == $ballot->voterID) {
				if ($voteCount >= $answerCount) {
					// Duplicate, treat as new voter
					fputcsv($out, $outArray);
					// Start as if first run
					$currentBallot = $ballot;
					$outArray = array($currentBallot->voterID, $currentBallot->voteTime, $currentBallot->pollID, $currentBallot->vote);
					$voteCount = 1;
				} else {
					// Same voter, keep pushing votes onto array
					array_push($outArray, $ballot->vote);
					$voteCount++;
				}
			} else {
				// New voter, write previous line
				fputcsv($out, $outArray);
				// Start as if first run
				$currentBallot = $ballot;
				$outArray = array($currentBallot->voterID, $currentBallot->voteTime, $currentBallot->pollID, $currentBallot->vote);
				$voteCount = 1;
			}
		}
	}
	// Do the last ballot
	fputcsv($out, $outArray);
	fclose($out);
} else {
	echo 'Error: '.$this->error;
}

?>