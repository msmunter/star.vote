<?php
class ApiModel extends Model
{
	public function getNextEmail()
	{
		$this->query = "SELECT *
						FROM `msgout`
						WHERE `requestRetrieved` IS NULL
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (!empty($this->results[0])) {
			$return = $this->results[0];
			$this->query = "UPDATE `msgout`
							SET `requestRetrieved` = NOW()
							WHERE `msgID` = '".$return->msgID."'
							LIMIT 1;";
			$this->doUpdateQuery();
			return $return;
		} else return false;
	}

	public function getMsgCompletedStatusByID($requestID)
	{
		$this->query = "SELECT *
						FROM `msgout`
						WHERE `msgID` = '$requestID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		if (count($this->results) > 0) {
			return $this->results[0];
		} else return false;
	}

	public function addMsg($template, $fields)
	{
		$this->query = "INSERT INTO `msgout` (`template`, `fields`)
					VALUES ('".$template."', '".$fields."')";
		$this->doInsertQuery();
	}

	public function completeEmailByID($requestID)
	{
		$this->query = "UPDATE `msgout`
						SET `requestCompleted` = NOW()
						WHERE `msgID` = '$requestID'
						LIMIT 1;";
		$this->doUpdateQuery();
	}
}
?>