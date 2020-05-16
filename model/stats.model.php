<?php
class StatsModel extends Model
{
	public function getTempvotes($surveyID)
	{
		$this->query = "SELECT * FROM `tempvotes`
						WHERE `surveyID` LIKE '$surveyID';";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results;
		} else return false;
	}

	public function getSurveyAnswers($surveyID)
	{
		$this->query = "SELECT `pollID` FROM `polls`
						WHERE `surveyID` LIKE '$surveyID';";
		$this->doSelectQuery();
		if (count($this->results > 0)) {
			$polls = $this->results;
			foreach ($polls as $poll) {
				$this->query = "SELECT `answerID`, `text` FROM `answers`
								WHERE `pollID` LIKE '".$poll->pollID."';";
				$this->doSelectQuery();
				foreach ($this->results as $answer) {
					$answers[$answer->answerID] = $answer->text;
				}
			}
		}
		return $answers;
	}

	public function getAllVotersBySurveyID($surveyID)
	{
		$this->query = "SELECT `voterfile`.`stateVoterID` AS `voterID`, `voters`.`voterID` AS `starID`, `voterfile`.`fname` AS `firstName`, `voterfile`.`lname` AS `lastName`, `voters`.`email` AS `email`, `voters`.`phone` AS `phone`, `voters`.`birthdate` AS `birthdate`, `voters`.`added` AS `regDate`, `voterident`.`verificationState` AS `status`
						FROM `voters`, `voterident`, `voterfile`
						WHERE `voters`.`voterID` = `voterident`.`voterID`
						AND `voterident`.`surveyID` LIKE '$surveyID'
						AND `voters`.`voterfileID` = `voterfile`.`voterfileID`;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results;
		} else return false;
	}

	public function getTempvoteByVoterID($surveyID, $voterID)
	{
		$this->query = "SELECT `voteJson`, `voteTime` FROM `tempvotes`
						WHERE `surveyID` LIKE '$surveyID'
						AND `voterID` LIKE '$voterID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
		} else return false;
	}

	// public function addMsg($template, $fieldsJson)
	// {
	// 	$this->query = "INSERT INTO `msgout` (`template`, `fields`)
	// 				VALUES ('".$template."', '".$fieldsJson."')";
	// 	$this->doInsertQuery();
	// }

	// public function completeEmailByID($requestID)
	// {
	// 	$this->query = "UPDATE `msgout`
	// 					SET `requestCompleted` = NOW()
	// 					WHERE `msgID` = '$requestID'
	// 					LIMIT 1;";
	// 	$this->doUpdateQuery();
	// }
}
?>