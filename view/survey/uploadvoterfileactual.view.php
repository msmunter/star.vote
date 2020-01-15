<?php
if ($_FILES['file']) {
	include_once('../../model/model.php');
	include_once('../../model/survey.model.php');
	$model = new SurveyModel;
	$survey = $model->getSurveyByID($_POST['surveyID']);
	if ($survey) {
		$fh = fopen($_FILES['file']['tmp_name'],"r");
		$lineCount = 0;
		$queryCount = 0;
		$voter = [];
		$firstQuery = false;
		while (!feof($fh)) {
			$line = fgets($fh);
			$boom = explode("\t", $line);
			$voter['voter_id'] = $boom[0];
			if ($voter['voter_id'] != 'VOTER_ID') {
				$voter['first_name'] = $boom[1];
				$voter['middle_name'] = $boom[2];
				$voter['last_name'] = $boom[3];
				$voter['name_suffix'] = $boom[4];
				$voter['birth_date'] = $boom[5];
				$voter['confidential'] = $boom[6];
				if ($voter['confidential'] != 'Confidential' && $voter['voter_id'] != 'ACP' && $voter['voter_id'] != '') {
					$voter['res_address_1'] = $boom[13];
					$voter['res_address_2'] = $boom[14];
					$voter['city'] = $boom[24];
					$voter['state'] = $boom[25];
					$voter['zip_code'] = $boom[26];
					if ($voter['zip_code'] == 'XXXXXXXX') {
						$voter['zip_code'] = false;
					} else if ($voter['zip_code'] == '') {
						$voter['zip_code'] = false;
					}
					$query = 'INSERT INTO `voterfile` (`surveyID`, `stateVoterID`, `fname`, `lname`, `street`, `street2`, `city`, `state`, `zip`, `birthyear`) VALUES ("'.$survey->surveyID.'", "'.$voter['voter_id'].'", "'.$voter['first_name'].'", "'.$voter['last_name'].'", "'.$voter['res_address_1'].'", "'.$voter['res_address_2'].'", "'.$voter['city'].'", "'.$voter['state'].'", "'.$voter['zip_code'].'", "'.$voter['birth_date'].'");';
					if ($queryCount == 0) {
						$firstQuery = $query;
					}
					$model->query = $query;
					$model->doInsertQuery();
					$queryCount++;
				}
			}
			$lineCount++;
		}
		fclose($fh);
		$return['firstQuery'] = $firstQuery;
		$return['lineCount'] = $lineCount;
		$return['queryCount'] = $queryCount;
		$return['status'] = 'Processed '.$lineCount.' lines, '.$queryCount.' queries';
	} else {
		$return['error'] = 'Survey not found';
	}
} else {
	$return['error'] = 'No file';
}
echo json_encode($return);
?>