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
			$this->query = "SELECT *
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
						ORDER BY `surveyOrder`, `created` ASC;";
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

	public function getVoterfileByID($voterfileID)
	{
		$this->query = "SELECT * FROM `voterfile`
						WHERE `voterfileID` LIKE '$voterfileID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
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
		$this->query = "UPDATE `voters`
						SET `voterfileID` = '$voterfileID',
							`phone` = '$phone',
							`email` = '$email'
						WHERE `voterID` = '$voterID'
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
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterIdent`
						WHERE `verificationState` IN ('verifiedOnce', 'rejectedOnce', 'verifiedTwice', 'rejectedTwice')
						AND `surveyID` LIKE '$surveyID';";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	// public function voterfileExists($surveyID, $voterID)
	// {
	// 	$this->query = "SELECT COUNT(*) AS `count` FROM `voterfile`
	// 					WHERE `surveyID` LIKE '$surveyID'
	// 					AND `voterID` LIKE '$voterID'
	// 					LIMIT 0,1;";
	// 	$this->doSelectQuery();
	// 	if ($this->results[0]->count > 0) {
	// 		return true;
	// 	}
	// 	return false;
	// }

	public function voterAlreadyVerified($surveyID, $voterID)
	{
		$this->query = "SELECT `verificationState` FROM `voterident`
						WHERE `surveyID` LIKE '$surveyID'
						AND `voterID` LIKE '$voterID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			if ($this->results[0]->verificationState == "inResults") {
				return true;
			}
		}
		return false;
	}

	public function validateVoter($surveyID, $voterID, $validateTime)
	{
		$this->query = "UPDATE `voterident`
						SET `verificationState` = 'inResults'
						WHERE `surveyID` LIKE '$surveyID'
						AND `voterID` LIKE '$voterID'
						LIMIT 1;";
		$this->doUpdateQuery();
		return true;
	}

	public function getVoterIdentCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterident`
						WHERE `surveyID` = '$surveyID';";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function getTempVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `tempvotes`
						WHERE `surveyID` LIKE '$surveyID'
						AND `voterID` IS NOT NULL
						AND `voterID` != '';";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function getVerifiedOnceVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterident`
						WHERE `surveyID` = '$surveyID'
						AND `verificationState` IN ('verifiedOnce');";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function getVerifiedTwiceVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterident`
						WHERE `surveyID` = '$surveyID'
						AND `verificationState` IN ('verifiedTwice');";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function getRejectedOnceVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterident`
						WHERE `surveyID` = '$surveyID'
						AND `verificationState` IN ('rejectedOnce');";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function getRejectedTwiceVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterident`
						WHERE `surveyID` = '$surveyID'
						AND `verificationState` IN ('rejectedTwice');";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function getRejectedVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterident`
						WHERE `surveyID` = '$surveyID'
						AND `verificationState` IN ('rejectedOnce', 'rejectedTwice');";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function getFinalizedVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterident`
						WHERE `surveyID` = '$surveyID'
						AND `verificationState` IN ('rejectedTwice', 'verifiedTwice');";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function getToBeFinalizedVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterident`
						WHERE `surveyID` = '$surveyID'
						AND `verificationState` IN ('voted', 'rejectedOnce', 'verifiedOnce');";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function getResultsVoterCount($surveyID)
	{
		$this->query = "SELECT COUNT(*) AS `count` FROM `voterident`
						WHERE `surveyID` = '$surveyID'
						AND `verificationState` IN ('inResults');";
		$this->doSelectQuery();
		return $this->results[0]->count;
	}

	public function getFinalizedVoters($surveyID)
	{
		$this->query = "SELECT * FROM `voterident`
						WHERE `surveyID` = '$surveyID'
						AND `verificationState` IN ('rejectedTwice', 'verifiedTwice');";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results;
		} else return false;
	}

	public function getToBeProcessedVoters($surveyID)
	{
		$this->polls = $this->getPollsBySurveyID($surveyID);
		$pollIdString = '';
		$i = 0;
		foreach ($this->polls as $poll) {
			if ($i > 0) {
				$pollIdString .= ',';
			}
			$pollIdString .= "'".$poll->pollID."'";
			$i++;
		}
		$this->query = "SELECT `voterID` FROM `voterident`
						WHERE `surveyID` = '$surveyID'
						AND `verificationState` IN ('verifiedTwice')
						AND `voterID` IN (SELECT `voterID` FROM `tempvotes`
										  WHERE `surveyID` LIKE '$surveyID')
						AND `voterID` NOT IN (SELECT `voterID` FROM `votes`
											  WHERE `pollID` IN ($pollIdString));";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results;
		} else return false;
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

	public function getVoterByID($voterID)
	{
		$this->query = "SELECT * FROM `voters`
						WHERE `voterID` LIKE '$voterID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
		} else {
			return false;
		}
	}

	public function getVoterByVoterfileID($voterfileID, $email)
	{
		$this->query = "SELECT * FROM `voters`
						WHERE `voterfileID` LIKE '$voterfileID'
						AND `email` LIKE '$email'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
		} else {
			return false;
		}
	}

	public function getVoterfileIDByVoterID($voterID)
	{
		$this->query = "SELECT `voterfileID` FROM `voters`
						WHERE `voterID` LIKE '$voterID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0]->voterfileID;
		} else {
			return false;
		}
	}

	public function getVoterIdentByVoterID($voterID)
	{
		$this->query = "SELECT * FROM `voterident`
						WHERE `voterID` LIKE '$voterID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
		} else {
			return false;
		}
	}

	public function getVoterfileByStateID($stateID)
	{
		$this->query = "SELECT `voterfileID`, `stateVoterID` FROM `voterfile`
						WHERE `stateVoterID` LIKE '".$this->escapeString($stateID)."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
		} else return false;
	}

	public function linkVoterfileToVoter($voterfileID, $voterID)
	{
		$this->query = "UPDATE `voters`
						SET `voterfileID` = '$voterfileID'
						WHERE `voterID` LIKE '$voterID'
						LIMIT 1;";
		$this->doUpdateQuery();
	}

	public function getVerificationStateByVoterID($voterID)
	{
		$this->query = "SELECT `verificationState` FROM `voterident`
						WHERE `voterID` LIKE '".$voterID."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0]->verificationState;
		} else return false;
	}

	public function insertIdentImage($surveyID, $voterID, $cdnHandle, $index)
	{
		if ($index == 2) {
			$cdnField = 'cdnHandle2';
		} else {
			$cdnField = 'cdnHandle1';
		}
		// See if one exists
		$this->query = "SELECT * FROM `voterident`
						WHERE `surveyID` LIKE '$surveyID'
						AND `voterID` LIKE '$voterID' 
						LIMIT 0,1";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			// Exists, update
			$this->query = "UPDATE `voterident`
							SET `$cdnField` = '$cdnHandle'
							WHERE `voteridentID` = '".$this->results[0]->voteridentID."'
							LIMIT 1;";
			$this->doUpdateQuery();
		} else {
			// Does not exist, create
			$this->query = "INSERT INTO `voterident` (`surveyID`, `voterID`, `$cdnField`, `verificationState`)
							VALUES ('".$surveyID."', '".$voterID."', '".$cdnHandle."', 'new')";
			$this->doInsertQuery();
		}
	}

	public function getIdentImage($surveyID, $voterID)
	{
		$this->query = "SELECT * FROM `voterident`
						WHERE `surveyID` LIKE '$surveyID'
						AND `voterID` LIKE '$voterID' 
						LIMIT 0,1";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
		} else {
			return false;
		}
	}

	public function getSameVoters($voterfileID, $voterID, $surveyID)
	{
		$this->query = "SELECT * FROM `voters`
						WHERE `voters`.`voterfileID` = '$voterfileID'
						AND `voters`.`voterID` NOT LIKE '$voterID'
						AND `voters`.`voterID` IN (
							SELECT `voterID` FROM `tempvotes`
							WHERE `surveyID` LIKE '$surveyID'
						);";

		// $this->query = "SELECT `voters`.`voterID` FROM `voters`, `tempvotes`
		// 				WHERE `voters`.`voterfileID` = '$voterfileID'
		// 				AND `voters`.`voterID` = `tempvotes`.`voterID`
		// 				AND `voters`.`voterID` NOT LIKE '$voterID';";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results;
		} else {
			return false;
		}
	}

	public function getVoterToValidate($surveyID, $userID)
	{
		// See if you have one checked out
		$this->query = "SELECT * FROM `voterident`, `tempvotes`
						WHERE `voterident`.`surveyID` LIKE '$surveyID'
						AND `voterident`.`checkoutID` = '$userID'
						AND `voterident`.`voterID` = `tempvotes`.`voterID`
						ORDER BY `tempvotes`.`voteTime` ASC
						LIMIT 0,1";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
		} else {
			// Check out one
			$this->query = "UPDATE `voterident`
							SET `checkoutID` = '$userID', `checkoutTime` = NOW() 
							WHERE `surveyID` LIKE '$surveyID'
							AND `verificationState` IN ('voted', 'verifiedOnce')
							AND (`firstVerifierID` IS NULL
														OR `firstVerifierID` != '$userID')
							LIMIT 1;";
			$this->doUpdateQuery();
			// Read the one you checked out
			$this->query = "SELECT * FROM `voterident`
							WHERE `surveyID` LIKE '$surveyID'
							AND `checkoutID` = '$userID'  
							LIMIT 0,1";
			$this->doSelectQuery();
			if (count($this->results) > 0) {
				return $this->results[0];
			} else {
				return false;
			}
		}
	}

	public function getRemainingVoters($surveyID)
	{
		$this->query = "SELECT `voterident`.`voterID` FROM `voterident`, `tempvotes`
						WHERE `voterident`.`surveyID` LIKE '$surveyID'
						AND `voterident`.`verificationState` LIKE 'rejectedOnce'
						AND `voterident`.`voterID` LIKE `tempvotes`.`voterID`
						ORDER BY `tempvotes`.`voteTime` ASC;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results;
		} else {
			return false;
		}
	}

	public function timeoutValidations($surveyID)
	{
		$this->query = "UPDATE `voterident`
						SET `checkoutID` = NULL, `checkoutTime` = NULL, `verificationState` = 'voted'
						WHERE `surveyID` LIKE '$surveyID'
						AND `checkoutTime` < (NOW() - INTERVAL 5 MINUTE);";
		$this->doUpdateQuery();
	}

	public function getTempVote($surveyID, $voterID)
	{
		$this->query = "SELECT * FROM `tempvotes`
						WHERE `voterID` LIKE '".$this->escapeString($voterID)."'
						AND `surveyID` LIKE '".$this->escapeString($surveyID)."'
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

	public function updateVoterIdentState($surveyID, $voterID, $verificationState, $userID, $reason)
	{
		$set = "SET `verificationState` = '$verificationState', `checkoutID` = NULL, `checkoutTime` = NULL";
		if ($verificationState == 'rejectedOnce') {
			$set .= ", `firstVerifierID` = '$userID', `firstVerifierTime` = NOW(), `rejectedReason` = '$reason'";
		} else if ($verificationState == 'rejectedTwice') {
			$set .= ", `secondVerifierID` = '$userID', `secondVerifierTime` = NOW(), `rejectedReason` = '$reason'";
		} else if ($verificationState == 'verifiedOnce') {
			$set .= ", `firstVerifierID` = '$userID', `firstVerifierTime` = NOW()";
		} else if ($verificationState == 'verifiedTwice') {
			$set .= ", `secondVerifierID` = '$userID', `secondVerifierTime` = NOW()";
		}
		$this->query = "UPDATE `voterident`
						$set
						WHERE `voterID` LIKE '$voterID'
						AND `surveyID` LIKE '$surveyID'
						LIMIT 1;";
		$this->doUpdateQuery();
	}

	public function finalizeVoter($surveyID, $voterID, $verificationState, $userID, $ticketID)
	{
		$set = "SET `verificationState` = '$verificationState', `checkoutID` = NULL, `checkoutTime` = NULL, `secondVerifierID` = '$userID', `secondVerifierTime` = NOW()";
		if ($ticketID) $set .= ", `ticketID` = '".$ticketID."'";
		$this->query = "UPDATE `voterident`
						$set
						WHERE `voterID` LIKE '$voterID'
						AND `surveyID` LIKE '$surveyID'
						LIMIT 1;";
		$this->doUpdateQuery();
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

	public function userCanValidate($userID, $surveyID)
	{
		$this->query = "SELECT `level` FROM `usersurveyvalauth`
						WHERE `userID` LIKE '$userID'
						AND `surveyID` LIKE '$surveyID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results[0]) > 0) {
			return $this->results[0]->level;
		} else {
			return false;
		}
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