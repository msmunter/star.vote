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
	
	public function insertSurvey($surveyID, $title, $randomOrder, $private, $creatorIP, $customSlug, $verifiedVoting, $verifiedVotingType, $userID, $verbage, $startDate, $startTime, $endDate, $endTime, $kioskMode, $printVote)
	{
		$oDateCreated = new DateTime();
		$oDateStart = new DateTime($startDate.' '.$startTime);
		$oDateEnd = new DateTime($endDate.' '.$endTime);
		if ($oDateStart < $oDateCreated) $oDateStart = $oDateCreated;
		if ($oDateEnd <= $oDateStart) {
			$endDateActual = 'NULL';
		} else $endDateActual = "'".$oDateEnd->format('Y-m-d H:i:s')."'";
		
		// Cleanup
		$title = htmlentities($title, ENT_QUOTES);

		$this->query = "INSERT INTO `surveys` (`surveyID`, `title`, `created`, `private`, `verifiedVoting`, `verifiedVotingType`, `randomOrder`, `creatorIP`, `customSlug`, `userID`, `verbage`, `startTime`, `endTime`, `kioskMode`, `printVote`)
						VALUES ('".$surveyID."', '".$title."', '".$oDateCreated->format('Y-m-d H:i:s')."', ".$private.", ".$verifiedVoting.", '".$verifiedVotingType."', ".$randomOrder.", '".$creatorIP."', '".$customSlug."', '".$userID."', '".$verbage."', '".$oDateStart->format('Y-m-d H:i:s')."', ".$endDateActual.", ".$kioskMode.", ".$printVote.")";
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

	public function getVoterFileMatch($fname, $lname, $street, $city, $state, $zip, $birthyear)
	{
		$lname = $this->escapeString($lname);
		$street = $this->escapeString($street);
		$city = $this->escapeString($city);
		$state = $this->escapeString($state);
		$zip = $this->escapeString($zip);
		$birthyear = $this->escapeString($birthyear);
		$this->query = "SELECT * FROM `voterfile`
						WHERE `lname` LIKE '$lname'
						AND `street` LIKE '$street'
						AND `city` LIKE '$city'
						AND `state` LIKE '$state'
						AND `zip` = $zip
						AND `birthyear` = $birthyear
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
		} else return false;
	}

	public function associateVoter($voterID, $voterfileID, $phone, $email)
	{
		$this->query = "UPDATE `voterfile`
						SET `voterID` = '$voterID',
							`phone` = '$phone',
							`email` = '$email'
						WHERE `voterfileID` = '$voterfileID'
						LIMIT 1;";
		$this->doUpdateQuery();
	}

	public function getVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterfile`
						WHERE `surveyID` LIKE '$surveyID';";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function getVerifiedVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterfile`
						WHERE `verified` IS NOT NULL
						AND `surveyID` LIKE '$surveyID';";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function voterfileExists($surveyID, $voterID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterfile`
						WHERE `surveyID` LIKE '$surveyID'
						AND `voterID` LIKE '$voterID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if ($this->results[0]->count > 0) {
			return true;
		}
		return false;
	}

	public function voterAlreadyVerified($surveyID, $voterID)
	{
		$this->query = "SELECT `verified` FROM `voterfile`
						WHERE `surveyID` LIKE '$surveyID'
						AND `voterID` LIKE '$voterID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			if ($this->results[0]->verified) {
				return true;
			}
		}
		return false;
	}

	public function validateVoter($surveyID, $voterID, $validateTime)
	{
		$this->query = "UPDATE `voterfile`
						SET `verified` = '$validateTime'
						WHERE `surveyID` LIKE '$surveyID'
						AND `voterID` LIKE '$voterID'
						LIMIT 1;";
		$this->doUpdateQuery();
		return true;
	}

	public function getTempVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `tempvotes`
						WHERE `surveyID` LIKE '$surveyID';";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	// public function getYourVote($voterID, $pollID)
	// {
	// 	$this->query = "SELECT `votes`.`answerID`, `votes`.`vote`, `answers`.`text`, `votes`.`voteTime`
	// 					FROM `votes`, `answers`
	// 					WHERE `votes`.`pollID` LIKE '$pollID'
	// 					AND `votes`.`voterID` LIKE '$voterID'
	// 					AND `answers`.`answerID` LIKE `votes`.`answerID`
	// 					ORDER BY `votes`.`vote` DESC;";
	// 	$this->doSelectQuery();
	// 	return $this->results;
	// }

	public function getPollQuestions($pollID)
	{

	}

	public function getIdentImage($surveyID, $voterID)
	{
		$this->query = "SELECT `cdnHandle`, `state` FROM `voteridentimages`
						WHERE `surveyID` LIKE '$surveyID'
						AND `voterID` LIKE '$voterID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
		} else {
			return false;
		}
	}

	public function insertIdentImage($surveyID, $voterID, $cdnHandle)
	{
		$this->query = "INSERT INTO `voteridentimages` (`surveyID`, `voterID`, `cdnHandle`, `state`)
							VALUES ('".$surveyID."', '".$voterID."', '".$cdnHandle."', 'new')";
		$this->doInsertQuery();
	}

	public function getTempVote($surveyID, $voterID)
	{
		$this->query = "SELECT * FROM `tempvotes`
						WHERE `voterID` LIKE '$voterID'
						AND `surveyID` LIKE '$surveyID'
						LIMIT 0,1";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
		} else {
			return false;
		}
	}

	public function insertTempVote($surveyID, $voterID, $voteJson, $voteTime)
	{
		$this->query = "INSERT INTO `tempvotes` (`surveyID`, `voterID`, `voteJson`, `voteTime`)
							VALUES ('".$surveyID."', '".$voterID."', '".$voteJson."', '".$voteTime."')";
		$this->doInsertQuery();
	}

	// public function deleteTempVote($surveyID, $voterID)
	// {
	// 	$this->query = "DELETE FROM `tempvotes`
	// 					WHERE `voterID` LIKE '$voterID'
	// 					AND `surveyID` LIKE '$surveyID'
	// 					LIMIT 1";
	// 	$this->doSelectQuery();
	// }

	public function validateTempVote($surveyID, $voterID, $validateTime)
	{
		$this->query = "UPDATE `tempvotes`
						SET `validateTime` = '$validateTime'
						WHERE `surveyID` LIKE '$surveyID'
						AND `voterID` LIKE '$voterID'
						LIMIT 1;";
		$this->doUpdateQuery();
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
		$this->doUpdateQuery();
	}
	
	public function incrementSurveyVoteCount($surveyID)
	{
		// Increment vote counter in surveys table
		$this->query = "UPDATE `surveys`
						SET `votes` = `votes` + 1
						WHERE `surveyID` LIKE '$surveyID'
						LIMIT 1;";
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