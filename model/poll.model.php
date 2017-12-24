<?php
class PollModel extends Model
{
	public function getPollByID($pollID)
	{
		$this->query = "SELECT *
						FROM `polls`
						WHERE `polls`.`pollID` LIKE '$pollID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		return $this->results[0];
	}
	
	public function getAnswersByPollID($pollID)
	{
		$this->query = "SELECT `answerID`, `text`, `votes`, `points`
						FROM `answers`
						WHERE `answers`.`pollID` LIKE '$pollID';";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function insertPoll($pollID, $question, $answers, $creatorIP)
	{
		// Poll first
		$this->query = "INSERT INTO `polls` (`pollID`, `question`, `created`, `private`, `allowMultiVoting`, `allowComments`, `creatorIP`)
						VALUES ('".$pollID."', '".$question."', '".date('Y-m-d h:i:s')."', 0, 0, 0, '".$creatorIP."')";
		// Insert
		//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
		$this->doInsertQuery();
		
		// Now answers
		foreach ($answers as $answer) {
			$this->query = "INSERT INTO `answers` (`pollID`, `text`, `votes`, `points`)
							VALUES ('".$pollID."', '".$answer."', 0, 0)";
			// Insert
			//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
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
		$this->doInsertQuery();
		
		// Add votes to matrix
		$this->query = "UPDATE `answers`
						SET `points` = `points` + $vote,
							`votes` = `votes` + 1
						WHERE `answerID` = $answerID
						LIMIT 1;";
		// Insert
		$this->doUpdateQuery();
	}
	
	public function getMostRecentPolls($limit)
	{
		if (!$limit || $limit < 1) $limit = 10;
		$this->query = "SELECT *
						FROM `polls`
						WHERE true
						ORDER BY `polls`.`created`
						LIMIT 0,$limit;";
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
