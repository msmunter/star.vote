<?php
class StatsModel extends Model
{
	public function getAllVotesByVoter()
	{
		$this->query = 'SELECT `voterID`, `pollID`, `answerID`, `vote`
						FROM `votes`
						WHERE `vote` BETWEEN 0 and 5;';
		$this->doSelectQuery();
		return $this->results;
	}

	public function getPolls()
	{
		$this->query = 'SELECT `pollID`, `votes`
						FROM `polls`
						WHERE `votes` > 0;';
		$this->doSelectQuery();
		return $this->results;
	}

	public function getVotersByPollID($pollID)
	{
		$this->query = 'SELECT DISTINCT `voterID`
						FROM `votes`
						WHERE `pollID` = "'.$pollID.'";';
		$this->doSelectQuery();
		return $this->results;
	}

	public function getVoteSet($pollID, $voterID)
	{
		$this->query = 'SELECT `vote`
						FROM `votes`
						WHERE `pollID` = "'.$pollID.'"
						AND `voterID` = "'.$voterID.'";';
		$this->doSelectQuery();
		return $this->results;
	}



	
	// public function getUserCount()
	// {
	// 	$this->query = 'SELECT COUNT(`userID`) as `ct` FROM `users`
	// 					WHERE `userID` > 1;';
	// 	$this->doSelectQuery();
	// 	return $this->results[0]->ct;
	// }
	
	// public function getUsers($limit)
	// {
	// 	if ($limit < 1) $limit = 1000;
	// 	$this->query = 'SELECT * FROM `users`
	// 					WHERE `userID` > 1
	// 					LIMIT 0,'.$limit.';';
	// 	$this->doSelectQuery();
	// 	if (!empty($this->results)) return $this->results;
	// 	return false;
	// }
	
	// public function getUserInfoByID($userID)
	// {
	// 	$this->query = 'SELECT * FROM `users`
	// 					WHERE `users`.`userID` = '.$userID.'
	// 					LIMIT 0,1;';
	// 	$this->doSelectQuery();
	// 	if (!empty($this->results)) return $this->results[0];
	// 	return false;
	// }
	
	/*public function searchUsers($searchText)
	{
		$this->query = 'SELECT `userID`, `email`, `firstname`, `lastname`, `client_id`, `admin_level`
						FROM `users` ';
		if ($searchText) {
			$this->query .= 'WHERE `firstname` LIKE %'.$searchText.'%
							OR `lastname`LIKE %'.$searchText.'%
							OR `email`LIKE %'.$searchText.'% ';
		} else {
			$this->query .= 'WHERE true ';
		}
		$this->query .= 'ORDER BY `lastname`, `firstname`
						LIMIT 0,50;';
		$this->doSelectQuery();
		return $this->results;
	}*/
	
	public function getUsersAlphabetical($search, $index, $limit)
	{
		// Ensure index and limit exists
		if (!$limit) $limit = 100;
		if (!$index) $index = 0;
		if ($search) {
			$where = "WHERE `users`.`firstName` LIKE \"%".$search."%\" 
					OR `users`.`lastName` LIKE \"%".$search."%\"
					OR `users`.`email` LIKE \"%".$search."%\"";
					
		} else {
			$where = "WHERE true";
		}
		$this->query = "SELECT *
						FROM `users`
						$where
						ORDER BY `users`.`lastName`, `users`.`firstName` ASC
						LIMIT $index,$limit;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function addUser($newUser)
	{
		$this->query = 'INSERT INTO `users` (`pass`, `admin_level`, `email`, `added`, `firstName`, `lastName`) VALUES ("'.$newUser['pass'].'", "'.$newUser['adminLevel'].'", "'.$newUser['email'].'", "'.$newUser['added'].'", "'.$newUser['firstName'].'", "'.$newUser['lastName'].'");';
		$this->doInsertQuery();
		return $this->insertID;
	}
	
	public function insertPassAdmin($userID, $pass)
	{
		$this->query = 'UPDATE `users` SET `pass` = "'.$pass.'"
						WHERE `userID` = '.$userID.'
						LIMIT 1;';
		$this->doUpdateQuery();
	}
}
?>