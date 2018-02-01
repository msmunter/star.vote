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
		$this->query = "SELECT `answerID`, `text`, `votes`, `points`
						FROM `answers`
						WHERE `answers`.`pollID` LIKE '$pollID'
						ORDER BY `answerID` ASC;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getTopAnswersByPollID($pollID)
	{
		$this->query = "SELECT `answerID`, `text`, `votes`, `points`
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
	
	public function insertPoll($pollID, $question, $answers, $randomOrder, $private, $creatorIP, $customSlug)
	{
		global $return;
		// Poll first
		$this->query = "INSERT INTO `polls` (`pollID`, `question`, `created`, `private`, `allowMultiVoting`, `allowComments`, `randomAnswerOrder`, `creatorIP`, `votes`, `customSlug`)
						VALUES ('".$pollID."', '".$question."', '".date('Y-m-d H:i:s')."', ".$private.", 0, 0, ".$randomOrder.", '".$creatorIP."', 0, '".$customSlug."')";
		// Insert
		//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
		$this->doInsertQuery();
		
		// Now answers
		$this->answerIDs = array();
		foreach ($answers as $answer) {
			$this->query = "INSERT INTO `answers` (`pollID`, `text`, `votes`, `points`)
							VALUES ('".$pollID."', '".$answer."', 0, 0)";
			// Insert
			//$this->debugHTML .= '<pre>'.$this->query.'</pre>'; // DEBUG ONLY!!!
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
					//$this->debugHTML .= '<pre>'.$this->query.'</pre>'; // DEBUG ONLY!!!
					$this->doInsertQuery();
					// Now the reverse
					$this->query = "INSERT INTO `runoff` (`pollID`, `gtID`, `ltID`, `votes`)
							VALUES ('".$pollID."', '".$a2."', '".$a1."', 0)";
					// Insert
					//$this->debugHTML .= '<pre>'.$this->query.'</pre>'; // DEBUG ONLY!!!
					$this->doInsertQuery();
				}
			}
			array_shift($answerIDsToDestroy);
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
		$this->query = "SELECT `answerID`, `vote`
						FROM `votes`
						WHERE `votes`.`pollID` LIKE '$pollID'
						AND `votes`.`voterID` LIKE '$voterID';";
		//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
		$this->doSelectQuery();
		//echo '<pre>';print_r($this->results);echo '</pre>'; // DEBUG ONLY!!!
		foreach ($this->results as $item) {
			$this->returnArray[$item->answerID] = $item->vote;
		}
		return $this->returnArray;
	}
	
	public function insertVoter($voterID, $ip)
	{
		$this->query = "INSERT INTO `voters` (`voterID`, `ip`)
							VALUES ('".$voterID."', '".$ip."')";
		// Insert
		//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
		$this->doInsertQuery();
	}
	
	public function insertVote($pollID, $voterID, $answerID, $vote)
	{
		// Add vote record
		$this->query = "INSERT INTO `votes` (`voterID`, `pollID`, `answerID`, `vote`)
							VALUES ('".$voterID."', '".$pollID."', '".$answerID."', '".$vote."')";
		// Insert
		//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
		//$this->debugHTML .= '<pre>'.$this->query.'</pre>'; // DEBUG ONLY!!!
		$this->doInsertQuery();
		
		// Add to scores/votes
		$this->query = "UPDATE `answers`
						SET `points` = `points` + $vote,
							`votes` = `votes` + 1
						WHERE `answerID` = $answerID
						LIMIT 1;";
		// Insert
		//$this->debugHTML .= '<pre>'.$this->query.'</pre>'; // DEBUG ONLY!!!
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
		//$this->debugHTML .= '<pre>'.$this->query.'</pre>'; // DEBUG ONLY!!!
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
		$this->debugHTML .= '<pre>'.$this->query.'</pre>'; // DEBUG ONLY!!!
		$this->doUpdateQuery();
	}
	
	public function getMostRecentPolls($index, $limit)
	{
		// Be sure you don't grab ones that are marked private
		if (!$limit || $limit < 1) $limit = 10;
		$this->query = "SELECT *
						FROM `polls`
						WHERE `private` = 0
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
						ORDER BY `polls`.`created` DESC
						LIMIT 0,$limit;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getMostPopularPolls($index, $limit)
	{
		/*$this->query = "SELECT `polls`.*, COUNT(DISTINCT `votes`.`voterID`, `votes`.`pollID`) AS `ct`
						FROM `polls`, `votes`
						WHERE `polls`.`private` = 0
						AND `polls`.`pollID` = `votes`.`pollID`
						ORDER BY `ct` DESC, `polls`.`created` DESC
						LIMIT $index,$limit;";*/
		$this->query = "SELECT *
						FROM `polls`
						WHERE `private` = 0
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
