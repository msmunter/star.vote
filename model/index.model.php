<?php
class IndexModel extends Model
{
	private function getMinerIDs()
	{
		// Form a select query to get settings from database
		$this->query = "SELECT `miners`.`miner_id` FROM `miners`
						WHERE true;";
		$this->doSelectQuery();
		// Make a tidy array
		foreach ($this->results as $resultIndex => $result) {
			$itemID = $result->miner_id;
			$toReturn[$resultIndex] = $itemID;
		}
		return $toReturn;
	}
	
	public function getMinerStatuses()
	{
		$minerIDs = $this->getMinerIDs();
		foreach ($minerIDs as $minerID) {
			$this->query = "SELECT `miners`.`hostname`, `miners`.`title`, `miner_status`.`miner_status_id`, `miner_status`.`server_timestamp`, `miner_status`.`miner_timestamp`, `miner_status`.`status`, `miner_status`.`summary`
							 FROM `miner_status`, `miners`
							 WHERE `miner_status`.`miner_id` = `miners`.`miner_id`
							 AND `miners`.`miner_id` = $minerID
							 ORDER BY `miner_status`.`server_timestamp` DESC
							 LIMIT 0,1;";
			$this->doSelectQuery();
			$result = $this->results[0];
			$result->status = json_decode($result->status);
			$result->summary = json_decode($result->summary);
			if (!empty($result->status) && !empty($result->summary)) {
				// Convert pesky spaces in variable names to something more underscore-y
				foreach (array('status', 'summary') as $toChange) {
					unset($changed);
					foreach ($result->$toChange as $resultIndex => $resultObject) {
						// Update item index
						$newIndex = str_replace(' ', '_', $resultIndex);
						// Set new
						$changed->$toChange->$newIndex = $resultObject;
					}
					// Update status/summary
					$result->$toChange = $changed->$toChange;
				}
				$minerStatuses[$minerID] = $result;
			}
			
		}
		//echo '<pre>';print_r($minerStatuses);echo '</pre>'; // DEBUG ONLY!!!
		return $minerStatuses;
	}
}
?>