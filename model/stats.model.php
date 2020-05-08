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