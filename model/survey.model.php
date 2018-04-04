<?php
class SurveyModel extends Model
{
	public function getSurveyByID($surveyID)
	{
		$this->query = "SELECT *
						FROM `surveys`
						WHERE `surveyID` LIKE '".$this->escapeString($surveyID)."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		return $this->results[0];
	}
	
	public function getSurveyByCustomSlug($slug)
	{
		$this->query = "SELECT *
						FROM `surveys`
						WHERE `customSlug` LIKE '".$this->escapeString($slug)."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		return $this->results[0];
	}
	
	public function getAllAnswersBySurveyID($surveyID)
	{
		$polls = $this->getPollsBySurveyID($surveyID);
		foreach ($polls as $poll) {
			$this->query = "SELECT `answerID`, `text`, `votes`, `points`
							FROM `answers`
							WHERE `answers`.`pollID` LIKE '".$poll->pollID."'
							ORDER BY `answerID` ASC;";
			$this->doSelectQuery();
			$return = array_merge($return, $this->results);
		}
		return $return;
	}
	
	public function getPollsBySurveyID($surveyID)
	{
		$this->query = "SELECT *
						FROM `polls`
						WHERE `surveyID` LIKE '".$this->escapeString($surveyID)."'
						ORDER BY `created` ASC;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getMostPopularSurveys($index, $limit)
	{
		$this->query = "SELECT *
						FROM `surveys`
						WHERE `private` = 0
						ORDER BY `votes` DESC, `created` DESC
						LIMIT $index,$limit;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getMostRecentSurveys($index, $limit)
	{
		// Be sure you don't grab ones that are marked private
		if (!$limit || $limit < 1) $limit = 10;
		$this->query = "SELECT *
						FROM `surveys`
						WHERE `private` = 0
						ORDER BY `created` DESC
						LIMIT $index,$limit;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getVoterKeysBySurveyID($surveyID)
	{
		$this->query = "SELECT *
						FROM `surveyVoterKeys`
						WHERE `surveyID` LIKE '".$this->escapeString($surveyID)."'
						ORDER BY `createdTime` ASC;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getUsedVoterKeyCountBySurveyID($surveyID)
	{
		$this->query = "SELECT COUNT(`surveyID`) as `ct`
						FROM `surveyVoterKeys`
						WHERE `surveyID` LIKE '".$this->escapeString($surveyID)."'
						AND `voteTime` IS NOT NULL;";
		$this->doSelectQuery();
		return $this->results[0]->ct;
	}
	
	public function verifyVoterKey($voterKey, $surveyID)
	{
		$this->query = "SELECT `surveyID`, `voterID`, `voteTime`
						FROM `surveyVoterKeys`
						WHERE `surveyID` LIKE '".$this->escapeString($surveyID)."'
						AND `voterKey` LIKE '".$this->escapeString($voterKey)."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results[0])) {
			return $this->results[0];
		} else return false;
	}
	
	public function insertVoterKey($surveyID, $key)
	{
		if (!empty($surveyID) && !empty($key)) {
			$this->query = "INSERT INTO `surveyVoterKeys` (`surveyID`, `voterKey`, `createdTime`, `voteTime`, `voterID`, `invalid`)
						VALUES ('".$surveyID."', '".$key."', '".date('Y-m-d H:i:s')."', null, 0, 0)";
			// Insert
			$this->doInsertQuery();
		}
	}
	
	public function isSlugTaken($slug)
	{
		$this->query = "SELECT *
						FROM `surveys`
						WHERE `customSlug` LIKE '".$this->escapeString($slug)."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results)) {
			return true;
		} else {
			$this->query = "SELECT *
							FROM `polls`
							WHERE `polls`.`customSlug` LIKE '".$this->escapeString($slug)."'
							LIMIT 0,1;";
			$this->doSelectQuery();
			if (!empty($this->results)) {
				return true;
			}
		}
		return false;
	}
	
	public function isSurveyIDTaken($surveyID)
	{
		$this->query = "SELECT `surveyID`
						FROM `surveys`
						WHERE `surveyID` LIKE '".$surveyID."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results[0]->surveyID)) {
			return true;
		} else return false;
	}
	
	public function insertSurvey($surveyID, $title, $randomOrder, $private, $creatorIP, $customSlug, $verifiedVoting, $verifiedVotingType, $userID, $verbage, $startDate, $startTime, $endDate, $endTime)
	{
		$oDateCreated = new DateTime();
		$oDateStart = new DateTime($startDate.' '.$startTime);
		$oDateEnd = new DateTime($endDate.' '.$endTime);
		if ($oDateStart < $oDateCreated) $oDateStart = $oDateCreated;
		if ($oDateEnd <= $oDateStart) {
			$endDateActual = 'NULL';
		} else $endDateActual = "'".$oDateEnd->format('Y-m-d H:i:s')."'";
		$this->query = "INSERT INTO `surveys` (`surveyID`, `title`, `created`, `private`, `verifiedVoting`, `verifiedVotingType`, `randomOrder`, `creatorIP`, `customSlug`, `userID`, `verbage`, `startTime`, `endTime`)
						VALUES ('".$surveyID."', '".$title."', '".$oDateCreated->format('Y-m-d H:i:s')."', ".$private.", ".$verifiedVoting.", '".$verifiedVotingType."', ".$randomOrder.", '".$creatorIP."', '".$customSlug."', '".$userID."', '".$verbage."', '".$oDateStart->format('Y-m-d H:i:s')."', ".$endDateActual.")";
		// Insert
		$this->doInsertQuery();
	}
	
	public function isGeneratedIDTaken($table, $column, $newID)
	{
		$this->query = "SELECT `$table`.`$column`
						FROM `$table`
						WHERE `$column` LIKE '".$newID."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results)) {
			return true;
		} else return false;
	}
	
	public function insertVote($pollID, $voterID, $answerID, $vote, $voteTime)
	{
		// Add vote record
		$this->query = "INSERT INTO `votes` (`voterID`, `pollID`, `answerID`, `vote`, `voteTime`)
							VALUES ('".$voterID."', '".$pollID."', '".$answerID."', '".$vote."', '".$voteTime."')";
		// Insert
		$this->doInsertQuery();
		
		// Add to scores/votes
		$this->query = "UPDATE `answers`
						SET `points` = `points` + $vote,
							`votes` = `votes` + 1
						WHERE `answerID` = $answerID
						LIMIT 1;";
		// Insert
		$this->doUpdateQuery();
	}
	
	public function incrementSurveyVoteCount($surveyID)
	{
		// Increment vote counter in surveys table
		$this->query = "UPDATE `surveys`
						SET `votes` = `votes` + 1
						WHERE `surveyID` LIKE '$surveyID'
						LIMIT 1;";
		// Insert
		$this->doUpdateQuery();
	}
	
	public function updateVoteMatrix($pollID, $gtID, $ltID)
	{
		// Update
		$this->query = "UPDATE `runoff`
					SET `votes` = `votes` + 1
					WHERE `pollID` LIKE '$pollID'
					AND `gtID` = $gtID
					AND `ltID` = $ltID
					LIMIT 1;";
		// Insert
		$this->doUpdateQuery();
	}
	
	public function updateVoterKeyEntry($voterKey, $surveyID, $voterID, $voteTime)
	{
		// Update
		$this->query = "UPDATE `surveyVoterKeys`
					SET `voterID` = '$voterID', `voteTime` = '$voteTime'
					WHERE `voterKey` LIKE '$voterKey'
					AND `surveyID` LIKE '$surveyID'
					LIMIT 1;";
		// Insert
		$this->doUpdateQuery();
	}
	
	/*public function userHasVotedInSurvey($voterID, $surveyID)
	{
		$this->query = "SELECT `votes`.`pollID`
						FROM `votes`
						WHERE `voterID` LIKE '".$voterID."'
						AND `pollID` LIKE '".$pollID."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results)) {
			return true;
		} else return false;
	}*/
	
	public function userHasVotedInPoll($voterID, $pollID)
	{
		$this->query = "SELECT `votes`.`pollID`
						FROM `votes`
						WHERE `voterID` LIKE '".$voterID."'
						AND `pollID` LIKE '".$pollID."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results)) {
			return true;
		} else return false;
	}
}
?>