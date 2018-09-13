<?php
class VoterModel extends Model
{	
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
	
	public function insertVoter($voterID, $ip, $browserInfo, $clientHost)
	{
		$this->query = "INSERT INTO `voters` (`voterID`, `ip`, `browserInfo`, `clientHost`)
							VALUES ('".$voterID."', '".$ip."', '".$browserInfo."', '".$clientHost."')";
		// Insert
		$this->doInsertQuery();
	}

	public function getUserInfo($voterID)
	{
		$this->query = "SELECT `fname`, `lname`, `email`, `mailingList`
						FROM `voters`
						WHERE `voterID` LIKE '".$voterID."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results)) {
			return $this->results[0];
		} else return false;
	}

	public function saveUserInfo($voterID, $fname, $lname, $email, $mailingList)
	{
		$this->query = 'UPDATE `voters`
						SET `fname` = "'.$fname.'", 
							`lname` = "'.$lname.'", 
							`email` = "'.$email.'", 
							`mailingList` = '.$mailingList.'
						WHERE `voterID` LIKE "'.$voterID.'" 
						LIMIT 1;';
		$this->doUpdateQuery();
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
	
	public function getYourVote($voterID, $pollID)
	{
		$this->query = "SELECT `votes`.`answerID`, `votes`.`vote`, `answers`.`text`, `votes`.`voteTime`
						FROM `votes`, `answers`
						WHERE `votes`.`pollID` LIKE '$pollID'
						AND `votes`.`voterID` LIKE '$voterID'
						AND `answers`.`answerID` LIKE `votes`.`answerID`
						ORDER BY `votes`.`vote` DESC;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	/*public function getPollByIDExampleUsingEscapeString($pollID)
	{
		$this->query = "SELECT *
						FROM `polls`
						WHERE `polls`.`pollID` LIKE '".$this->escapeString($pollID)."'
						LIMIT 0,1;";
		$this->doSelectQuery();
		return $this->results[0];
	}
	
	public function insertVote($pollID, $voterID, $answerID, $vote)
	{
		// Add vote record
		$this->query = "INSERT INTO `votes` (`voterID`, `pollID`, `answerID`, `vote`)
							VALUES ('".$voterID."', '".$pollID."', '".$answerID."', '".$vote."')";
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
	}*/
}
?>
