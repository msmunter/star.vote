<?php
//echo '<pre>';print_r($this->poll->processedBallots);echo '</pre>';
if ($this->error == '') {
	$fileName = 'weekly_ballots_'.$this->URLdata.'_'.date('YmdHis');
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.$fileName.'.csv');
	$out = fopen('php://output', 'w');
	$headerArray = array('voterID', 'voteTime', 'pollID', 'ip', 'browserInfo', 'clientHost', 'firstName', 'lastName', 'email', 'mailingList');
	$answerCount = count($this->poll->answers);
	// Push answers onto header array
	foreach ($this->poll->answers as $answer) {
		array_push($headerArray, $answer->text);
	}
	array_merge($headerArray, array());
	fputcsv($out, $headerArray);
	// Process Ballots
	foreach ($this->poll->processedBallots as $voterID => $ballot) {
		$outArray = array($voterID, $ballot['voteTime'], $ballot['pollID'], $ballot['ip'], $ballot['browserInfo'], $ballot['clientHost'], $ballot['fname'], $ballot['lname'], $ballot['email'], $ballot['mailingList']);
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