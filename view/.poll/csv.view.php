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
	// Process Ballots
	foreach ($this->poll->processedBallots as $voterID => $ballot) {
		$outArray = array($voterID, $ballot['voteTime'], $ballot['pollID']);
		foreach ($this->poll->answers as $answer) {
			array_push($outArray, $ballot['votes'][$answer->answerID]);
		}
		fputcsv($out, $outArray);
	}
	fclose($out);
} else {
	echo 'Error: '.$this->error;
}

?>