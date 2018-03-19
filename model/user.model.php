<?php
class UserModel extends Model
{
	public function getUserInfoByID($userID)
	{
		$this->query = 'SELECT * FROM `users`
						WHERE `users`.`userID` = '.$userID.'
						LIMIT 0,1;';
		$this->doSelectQuery();
		if (!empty($this->results)) return $this->results[0];
		return false;
	}
	
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
	
	public function getSettings($userID)
	{
		// Form a select query to get settings from database
		/*$this->query = 'SELECT `item`, `value` FROM `settings` WHERE `userID` = '.$userID.';';
		$this->doSelectQuery();
		// Make a tidy array
		foreach ($this->results as $result) $toReturn[$result->item] = $result->value;
		return $toReturn;*/
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
	
	public function getUserIDByToken($token)
	{
		$timeObject = new DateTime();
		$this->query = "SELECT `tokens`.`userID`
						FROM `tokens`
						WHERE `tokens`.`token` LIKE \"$token\"
						AND `tokens`.`expires` > ".$timeObject->format('U')."
						LIMIT 0,1";
		$this->doSelectQuery();
		//echo 'getUserIDByToken:<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
		if (!empty($this->results)) return $this->results[0]->userID;
		return false;
	}
	
	public function verifyPassword($email, $pass)
	{
		$this->query = "SELECT `users`.* FROM `users`
						WHERE `users`.`email` LIKE \"$email\"
						LIMIT 0,1";
		$this->doSelectQuery();
		// Verify, return userID or false
		if (password_verify($pass, $this->results[0]->pass)) {
			return $this->results[0]->userID;
		} else return false;
	}
	
	public function destroyExpiredTokensByUserID($userID)
	{
		$timeObject = new DateTime();
		$this->query = "DELETE FROM `tokens`
						WHERE `tokens`.`userID` = $userID
						AND `tokens`.`expires` <= ".$timeObject->format('U').";";
		if ($this->doDeleteQuery()) return true;
		return false;
	}
	
	public function destroyTokenByID($tokenID)
	{
		$this->query = "DELETE FROM `tokens`
						WHERE `tokens`.`token` LIKE '$tokenID';";
		if ($this->doDeleteQuery()) return true;
		return false;
	}
	
	public function insertToken($userID, $token, $expires)
	{
		$this->destroyExpiredTokensByUserID($userID);
		$this->query = 'INSERT INTO `tokens` (`userID`, `token`, `expires`) 
						VALUES ("'.$userID.'", "'.$token.'", "'.$expires.'");';
		if ($this->doInsertQuery()) return true;
		return false;
	}
	
	public function checkExistingEmail($email)
	{
		$this->query = 'SELECT * FROM `users`
						WHERE `users`.`email` LIKE \''.$email.'\' 
						LIMIT 0,1;';
		$this->doSelectQuery();
		if (!empty($this->results)) return $this->results[0];
		return false;
	}
}
?>