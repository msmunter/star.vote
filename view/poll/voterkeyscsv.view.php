<?php
if ($this->error == '') {
	$fileName = 'starvoteVoterKeys_pollID_'.$this->poll->pollID.'_date_'.date('YmdHis');
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.$fileName.'.csv');
	$out = fopen('php://output', 'w');
	$headerArray = array('pollID', 'voterKey', 'createdTime', 'votedTime');
	fputcsv($out, $headerArray);
	// Process Ballots
	foreach ($this->voterKeys as $voterKey) {
		$outArray = array($this->poll->pollID, $voterKey->voterKey, $voterKey->createdTime, $voterKey->votedTime);
		fputcsv($out, $outArray);
	}
	fclose($out);
} else {
	echo 'Error: '.$this->error;
}

?>