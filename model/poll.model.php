<?php
class PollModel extends Model
{
	public function getPollByID($pollID)
	{
		$this->query = "SELECT *
						FROM `polls`
						WHERE `polls`.`pollID` LIKE '".$this->escapeString($pollID)."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		return $this->results[0];
	}
	
	public function getPollByCustomSlug($slug)
	{
		$this->query = "SELECT *
						FROM `polls`
						WHERE `polls`.`customSlug` LIKE '".$this->escapeString($slug)."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		return $this->results[0];
	}
	
	public function getAnswersByPollID($pollID)
	{
		$this->query = "SELECT `answerID`, `text`, `votes`, `points`, `imgur`
						FROM `answers`
						WHERE `answers`.`pollID` LIKE '$pollID'
						ORDER BY `answerID` ASC;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getAnswersByPollIDScoreOrder($pollID)
	{
		$this->query = "SELECT `answerID`, `text`, `votes`, `points`, `imgur`
						FROM `answers`
						WHERE `answers`.`pollID` LIKE '$pollID'
						ORDER BY `points` DESC, `votes` DESC;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getTopAnswersByPollID($pollID)
	{
		$this->query = "SELECT `answerID`, `text`, `votes`, `points`, `imgur`
						FROM `answers`
						WHERE `answers`.`pollID` LIKE '$pollID'
						ORDER BY `points` DESC;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getAvgVoteByAnswerID($answerID)
	{
		$this->query = "SELECT AVG(`vote`) as `avg`
						FROM `votes`
						WHERE `answerID` = $answerID;";
		$this->doSelectQuery();
		return $this->results[0]->avg;
	}
	
	public function getRunoffResultsByAnswerID($pollID, $answerID1, $answerID2)
	{
		$this->query = "SELECT `votes`
						FROM `runoff`
						WHERE `gtID` LIKE '$answerID1'
						AND `ltID` LIKE '$answerID2'
						LIMIT 0,1;";
		$this->doSelectQuery();
		$prefID1 = $this->results[0]->votes;
		$this->query = "SELECT `votes`
						FROM `runoff`
						WHERE `gtID` LIKE '$answerID2'
						AND `ltID` LIKE '$answerID1'
						LIMIT 0,1;";
		$this->doSelectQuery();
		$prefID2 = $this->results[0]->votes;
		if ($prefID1 == $prefID2) {
			$return['tie'] = true;
			$return['first']['answerID'] = $answerID1;
			$return['first']['votes'] = $prefID1;
			$return['second']['answerID'] = $answerID2;
			$return['second']['votes'] = $prefID2;
		} else if ($prefID1 > $prefID2) {
			$return['first']['answerID'] = $answerID1;
			$return['first']['votes'] = $prefID1;
			$return['second']['answerID'] = $answerID2;
			$return['second']['votes'] = $prefID2;
		} else {
			$return['first']['answerID'] = $answerID2;
			$return['first']['votes'] = $prefID2;
			$return['second']['answerID'] = $answerID1;
			$return['second']['votes'] = $prefID1;
		}
		return $return;
	}
	
	public function getRunoffResultsRawByPollID($pollID)
	{
		$this->query = "SELECT `runoff`.*
						FROM `runoff`
						WHERE `runoff`.`pollID` LIKE '$pollID'
						ORDER BY `votes`;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getBallotsByPollID($pollID)
	{
		$this->query = "SELECT *
						FROM `votes`
						WHERE `votes`.`pollID` LIKE '$pollID'
						ORDER BY `voteTime` ASC, `voterID` ASC;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getVoterKeysByPollID($pollID)
	{
		$this->query = "SELECT *
						FROM `voterKeys`
						WHERE `pollID` LIKE '".$this->escapeString($pollID)."'
						ORDER BY `createdTime` ASC;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getUsedVoterKeyCountByPollID($pollID)
	{
		$this->query = "SELECT COUNT(`pollID`) as `ct`
						FROM `voterKeys`
						WHERE `pollID` LIKE '".$this->escapeString($pollID)."'
						AND `voteTime` IS NOT NULL;";
		$this->doSelectQuery();
		return $this->results[0]->ct;
	}
	
	public function verifyVoterKey($voterKey, $pollID)
	{
		$this->query = "SELECT `pollID`, `voterID`, `voteTime`
						FROM `voterKeys`
						WHERE `pollID` LIKE '".$this->escapeString($pollID)."'
						AND `voterKey` LIKE '".$this->escapeString($voterKey)."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results[0])) {
			return $this->results[0];
		} else return false;
	}
	
	public function insertPoll($pollID, $question, $answers, $randomOrder, $private, $creatorIP, $customSlug, $verifiedVoting, $verifiedVotingType, $userID, $surveyID, $oDateCreated, $startDate, $startTime, $endDate, $endTime, $numWinners)
	{
		if ($verifiedVoting == '') $verifiedVoting = 0;
		$oDateStart = new DateTime($startDate.' '.$startTime);
		$oDateEnd = new DateTime($endDate.' '.$endTime);
		if ($oDateStart < $oDateCreated) $oDateStart = $oDateCreated;
		if ($oDateEnd <= $oDateStart) {
			$endDateActual = 'NULL';
		} else $endDateActual = "'".$oDateEnd->format('Y-m-d H:i:s')."'";
		// Poll first
		$this->query = "INSERT INTO `polls` (`pollID`, `question`, `created`, `private`, `verifiedVoting`, `verifiedVotingType`, `allowComments`, `randomAnswerOrder`, `creatorIP`, `votes`, `customSlug`, `userID`, `surveyID`, `startTime`, `endTime`, `numWinners`)
						VALUES ('".$pollID."', '".$question."', '".$oDateCreated->format('Y-m-d H:i:s')."', ".$private.", ".$verifiedVoting.", '".$verifiedVotingType."', 0, ".$randomOrder.", '".$creatorIP."', 0, '".$customSlug."', '".$userID."', '".$surveyID."', '".$oDateStart->format('Y-m-d H:i:s')."', ".$endDateActual.", ".$numWinners.")";
		//$this->displayQuery = $this->query; // DEBUG ONLY!!!
		// Insert
		$this->doInsertQuery();
		unset($oDate);
		
		// Now answers
		$this->answerIDs = array();
		foreach ($answers as $answer) {
			$this->query = "INSERT INTO `answers` (`pollID`, `text`, `votes`, `points`)
							VALUES ('".$pollID."', '".$answer."', 0, 0)";
			// Insert
			$this->doInsertQuery();
			$this->answerIDs[] = $this->insertID;
		}
		
		// Now the runoff matrix
		$answerIDsToDestroy = $this->answerIDs;
		foreach ($this->answerIDs as $a1Index => $a1) {
			foreach ($answerIDsToDestroy as $a2Index => $a2) {
				if ($a1 != $a2) {
					$this->query = "INSERT INTO `runoff` (`pollID`, `gtID`, `ltID`, `votes`)
							VALUES ('".$pollID."', '".$a1."', '".$a2."', 0)";
					// Insert
					$this->doInsertQuery();
					// Now the reverse
					$this->query = "INSERT INTO `runoff` (`pollID`, `gtID`, `ltID`, `votes`)
							VALUES ('".$pollID."', '".$a2."', '".$a1."', 0)";
					// Insert
					$this->doInsertQuery();
				}
			}
			array_shift($answerIDsToDestroy);
		}
	}
	
	public function insertVoterKey($pollID, $key)
	{
		if (!empty($pollID) && !empty($key)) {
			$this->query = "INSERT INTO `voterKeys` (`pollID`, `voterKey`, `createdTime`, `voteTime`, `invalid`)
						VALUES ('".$pollID."', '".$key."', '".date('Y-m-d H:i:s')."', null, 0)";
			// Insert
			$this->doInsertQuery();
		}
	}
	
	public function voterExists($voterID)
	{
		$this->query = "SELECT `voters`.`voterID`
						FROM `voters`
						WHERE `voterID` LIKE '".$voterID."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results)) {
			return true;
		} else return false;
	}
	
	public function getYourVote($voterID, $pollID)
	{
		$this->query = "SELECT `votes`.`answerID`, `votes`.`vote`, `answers`.`text`, `answers`.`imgur`
						FROM `votes`, `answers`
						WHERE `votes`.`pollID` LIKE '$pollID'
						AND `votes`.`voterID` LIKE '$voterID'
						AND `answers`.`answerID` LIKE `votes`.`answerID`
						ORDER BY `votes`.`vote` DESC;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function insertVoter($voterID, $ip)
	{
		$this->query = "INSERT INTO `voters` (`voterID`, `ip`)
							VALUES ('".$voterID."', '".$ip."')";
		// Insert
		$this->doInsertQuery();
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
	
	public function incrementPollVoteCount($pollID)
	{
		// Increment vote counter in polls table
		$this->query = "UPDATE `polls`
						SET `votes` = `votes` + 1
						WHERE `pollID` LIKE '$pollID'
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
	
	public function updateVoterKeyEntry($voterKey, $pollID, $voterID, $voteTime)
	{
		// Update
		$this->query = "UPDATE `voterKeys`
					SET `voterID` = '$voterID', `voteTime` = '$voteTime'
					WHERE `voterKey` LIKE '$voterKey'
					AND `pollID` LIKE '$pollID'
					LIMIT 1;";
		// Insert
		$this->doUpdateQuery();
	}
	
	public function getMostRecentPolls($index, $limit)
	{
		// Be sure you don't grab ones that are marked private
		if (!$limit || $limit < 1) $limit = 10;
		$this->query = "SELECT *
						FROM `polls`
						WHERE `private` = 0
						AND `surveyID` LIKE '0'
						OR `private` = 0
						AND `surveyID` LIKE ''
						ORDER BY `polls`.`created` DESC
						LIMIT $index,$limit;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getTrendingPolls($limit)
	{
		// Be sure you don't grab ones that are marked private
		if (!$limit || $limit < 1) $limit = 10;
		$this->query = "SELECT *
						FROM `polls`
						WHERE `private` = 0
						AND `surveyID` LIKE '0'
						OR `private` = 0
						AND `surveyID` LIKE ''
						ORDER BY `polls`.`created` DESC
						LIMIT 0,$limit;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getMostPopularPolls($index, $limit)
	{
		$this->query = "SELECT *
						FROM `polls`
						WHERE `private` = 0
						AND `surveyID` LIKE '0'
						OR `private` = 0
						AND `surveyID` LIKE ''
						ORDER BY `votes` DESC, `created` DESC
						LIMIT $index,$limit;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getPollVoterCount($pollID)
	{
		$this->query = "SELECT COUNT(DISTINCT(`votes`.`voterID`)) as `ct`
						FROM `votes`
						WHERE `votes`.`pollID` LIKE '$pollID';";
		$this->doSelectQuery();
		return $this->results[0]->ct;
	}
	
	public function getPollPointCount($pollID)
	{
		$this->query = "SELECT SUM(`vote`) as `votes`
						FROM `votes`
						WHERE `votes`.`pollID` LIKE '$pollID';";
		$this->doSelectQuery();
		return $this->results[0]->votes;
	}
	
	public function getAnswerVoterCount($answerID)
	{
		$this->query = "SELECT COUNT(*) as `ct`
						FROM `answers`
						WHERE `answers`.`answerID` LIKE '$answerID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		return $this->results[0]->ct;
	}
	
	public function isPollIDTaken($pollID)
	{
		$this->query = "SELECT `polls`.`pollID`
						FROM `polls`
						WHERE `pollID` LIKE '".$pollID."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results)) {
			return true;
		} else return false;
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
	
	public function userHasVoted($voterID, $pollID)
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
