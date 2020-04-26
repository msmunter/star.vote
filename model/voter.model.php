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
	
	public function insertVoter($voterID, $ip)
	{
		$this->query = "INSERT INTO `voters` (`voterID`, `ip`)
							VALUES ('".$voterID."', '".$ip."')";
		// Insert
		$this->doInsertQuery();
	}

	public function insertVoterWithEmail($voterID, $phone, $ipoOptIn, $starOptIn, $birthdate, $ip, $email, $platform, $browser, $browserVersion)
	{
		$this->query = "INSERT INTO `voters` (`voterID`, `phone`, `ipoOptIn`, `starOptIn`, `birthdate`, `ip`, `email`, `platform`, `browser`, `browserVersion`)
							VALUES ('".$this->escapeString($voterID)."', '".$this->escapeString($phone)."', '".$this->escapeString($ipoOptIn)."', '".$this->escapeString($starOptIn)."', '".$this->escapeString($birthdate)."', '".$ip."', '".$this->escapeString($email)."', '".$this->escapeString($platform)."', '".$this->escapeString($browser)."', '".$this->escapeString($browserVersion)."')";
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
