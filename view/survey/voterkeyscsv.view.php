<?php
if ($this->error == '') {
	$fileName = 'starvoteVoterKeys_surveyID_'.$this->survey->surveyID.'_date_'.date('YmdHis');
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.$fileName.'.csv');
	$out = fopen('php://output', 'w');
	$headerArray = array('surveyID', 'voterKey', 'createdTime', 'voteTime');
	fputcsv($out, $headerArray);
	// Process Ballots
	foreach ($this->voterKeys as $voterKey) {
		$outArray = array($this->survey->surveyID, $voterKey->voterKey, $voterKey->createdTime, $voterKey->voteTime);
		fputcsv($out, $outArray);
	}
	fclose($out);
} else {
	echo 'Error: '.$this->error;
}

?>