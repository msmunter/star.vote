<?php
class ApiModel extends Model
{
	public function getNextEmail()
	{
		$this->query = "SELECT *
						FROM `msgout`
						WHERE `requestRetrieved` IS NULL
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results[0])) {
			$return = $this->results[0];
			$this->query = "UPDATE `msgout`
							SET `requestRetrieved` = NOW()
							WHERE `msgID` = '".$return['msgID']."'
							LIMIT 1;";
			$this->doUpdateQuery();
			return $return;
		} else return false;
	}

	// public function insertVoterKey($surveyID, $key)
	// {
	// 	$this->query = "INSERT INTO `surveyVoterKeys` (`surveyID`, `voterKey`, `createdTime`, `voteTime`, `voterID`, `invalid`)
	// 				VALUES ('".$surveyID."', '".$key."', '".date('Y-m-d H:i:s')."', null, 0, 0)";
	// 	$this->doInsertQuery();
	// }

	// public function timeoutValidations($surveyID)
	// {
	// 	$this->query = "UPDATE `voterident`
	// 					SET `checkoutID` = NULL, `checkoutTime` = NULL, `verificationState` = 'voted'
	// 					WHERE `surveyID` LIKE '$surveyID'
	// 					AND `verificationState` LIKE 'checkedOut'
	// 					AND `checkoutTime` < (NOW() - INTERVAL 5 MINUTE);";
	// 	$this->doUpdateQuery();
	// }
}
?>