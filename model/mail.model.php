<?php
class MailModel extends Model
{
	public function verifyEmail($verifyKey)
	{
		$this->query = 'SELECT `verifydate` FROM `mailverify`
						WHERE `verifykey` = "'.$verifyKey.'" 
						LIMIT 0,1;';
		$this->doSelectQuery();
		if (!empty($this->results)) return $this->results[0];
		return false;
	}

	public function emailStatus($address)
	{
		$this->query = 'SELECT * FROM `mailverify`
						WHERE `address` = "'.$address.'" 
						LIMIT 0,1;';
		$this->doSelectQuery();
		if (!empty($this->results)) return $this->results[0];
		return false;
	}

	public function insertVerifyEmailKey($address, $verifyKey)
	{
		$this->query = 'INSERT INTO `mailverify` (`address`, `verifykey`) VALUES ("'.$address.'", "'.$verifyKey.'");';
		$this->doInsertQuery();
		return $this->insertID;
	}

	public function setVerifyDate($verifyKey, $verifyDate)
	{
		$this->query = 'UPDATE `mailverify` SET `verifydate` = "'.$verifyDate.'"
						WHERE `verifykey` = "'.$verifyKey.'"
						LIMIT 1;';
		$this->doUpdateQuery();
		return $verifyDate;
	}
}