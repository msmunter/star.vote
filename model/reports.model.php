<?php
class ReportsModel extends Model
{
	public function getSalesDatasetCount()
	{
		$this->query = "SELECT COUNT(*) as `ct`
						FROM `salesDatasets`
						WHERE true;";
		$this->doSelectQuery();
		return $this->results[0]->ct;
	}
	
	public function getItemCount($table)
	{
		$this->query = "SELECT COUNT(*) as `ct`
						FROM `$table`
						WHERE true;";
		$this->doSelectQuery();
		return $this->results[0]->ct;
	}
	
	/* Sales */
	
	public function getSalesDataset($itemID)
	{
		$this->query = "SELECT *
						FROM `salesDatasets`
						WHERE `salesDatasets`.`salesDatasetID` = '$itemID'
						LIMIT 0,1;";
		$this->doSelectQuery();
		return $this->results[0];
	}
	
	public function getSalesItems($datasetID)
	{
		$this->query = "SELECT `salesItems`.*
						FROM `salesItems`, `salesItemToSalesDataset`
						WHERE `salesItemToSalesDataset`.`salesDatasetID` = '$datasetID'
						AND `salesItems`.`salesItemID` = `salesItemToSalesDataset`.`salesItemID`
						ORDER BY UNIX_TIMESTAMP(STR_TO_DATE(CONCAT(transactionDate, ' ', transactionTime),'%m/%d/%Y %h:%i:%s %p')) ASC;";
		//print_r($this->query); // DEBUG ONLY!!!
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getSalesItemsByDate($startDate, $endDate, $index, $limit)
	{
		$startDateTs = strtotime($startDate);
		$endDateTs = strtotime($endDate);
		if ($index < 1) $index = 0;
		$this->query = "SELECT UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) as `utd`, `salesItems`.*
						FROM `salesItems`
						WHERE UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) >= $startDateTs
						AND UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) <= $endDateTs
						ORDER BY UNIX_TIMESTAMP(STR_TO_DATE(CONCAT(transactionDate, ' ', transactionTime),'%m/%d/%Y %h:%i:%s %p')) ASC";
		if ($index > 0 || $limit > 0) $this->query .= " LIMIT $index,$limit;";
		//print_r($this->query); // DEBUG ONLY!!!
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getSalesItemCount($startDate, $endDate)
	{
		$startDateTs = strtotime($startDate);
		$endDateTs = strtotime($endDate);
		$this->query = "SELECT COUNT(*) as `ct`
						FROM `salesItems`
						WHERE UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) >= $startDateTs
						AND UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) <= $endDateTs;";
		$this->doSelectQuery();
		return $this->results[0]->ct;
	}
	
	public function getSalesReceiptCount($startDate, $endDate)
	{
		$startDateTs = strtotime($startDate);
		$endDateTs = strtotime($endDate);
		$this->query = "SELECT COUNT(DISTINCT(`saleID`)) as `ct`
						FROM `salesItems`
						WHERE UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) >= $startDateTs
						AND UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) <= $endDateTs;";
		$this->doSelectQuery();
		return $this->results[0]->ct;
	}
	
	public function getSalesTotals($datasetID)
	{
		$this->query = "SELECT *
						FROM `salesReportsBudtender`
						WHERE `salesDatasetID` = '$datasetID'
						ORDER BY `netSales` DESC;";
		$this->doSelectQuery();
		$returnArray['budtender'] = $this->results;
		
		$this->query = "SELECT *
						FROM `salesReportsHourly`
						WHERE `salesDatasetID` = '$datasetID'
						ORDER BY `hour` ASC;";
		$this->doSelectQuery();
		$returnArray['hourly'] = $this->results;
		
		$this->query = "SELECT *
						FROM `salesReportsTotals`
						WHERE `salesDatasetID` = '$datasetID';";
		$this->doSelectQuery();
		$returnArray['totals'] = $this->results;
		return $returnArray;
	}
	
	public function getSalesItemsProductBreakdown($startDate, $endDate, $sku)
	{
		$startDateTs = strtotime($startDate);
		$endDateTs = strtotime($endDate);
		if ($index < 1) $index = 0;
		$this->query = "SELECT UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) as `utd`, `salesItems`.*
						FROM `salesItems`
						WHERE UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) >= $startDateTs
						AND UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) <= $endDateTs
						AND `sku` LIKE '".$sku."'
						ORDER BY UNIX_TIMESTAMP(STR_TO_DATE(CONCAT(transactionDate, ' ', transactionTime),'%m/%d/%Y %h:%i:%s %p')) ASC";
		//if ($index > 0 || $limit > 0) $this->query .= " LIMIT $index,$limit;";
		//print_r($this->query); // DEBUG ONLY!!!
		$this->doSelectQuery();
		return $this->results;
	}
	
	/* Datasets / Reports */ 
	
	public function getRecentSalesDatasets($limit)
	{
		if ($limit < 1) $limit = 10;
		$this->query = "SELECT *
						FROM `salesDatasets`
						WHERE true
						ORDER BY `startDate` DESC
						LIMIT 0,$limit;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getSalesDatasets($index, $limit, $ascDesc)
	{
		$this->query = "SELECT *
						FROM `salesDatasets`
						WHERE true
						ORDER BY `startDate` $ascDesc
						LIMIT $index,$limit;";
		$this->doSelectQuery();
		return $this->results;
	}
	
	public function getSalesPerDay($dayTimestamp)
	{
		$date = date('m/d/Y', $dayTimestamp);
		$this->query = "SELECT COUNT(`salesItemID`) as `ct`
						FROM `salesItems`
						WHERE `transactionDate` = '$date';";
		$this->doSelectQuery();
		return $this->results[0]->ct;
	}
	
	/*public function truncateInventory()
	{
		$this->query = "TRUNCATE `inventoryItems`";
		$this->doDeleteQuery();
	}*/
	
	public function insertSalesDataset($userID, $startDate, $endDate)
	{
		$insertDate = date("Y-m-d H:i:s");
		$this->query = "INSERT INTO `salesDatasets` (`added`, `user`, `startDate`, `endDate`)
						VALUES ('".$insertDate."', '".$userID."', '".$startDate."', '".$endDate."')";
		// Insert
		//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
		$this->doInsertQuery();
		return $this->insertID;
	}
	
	public function insertSalesDatasetItem($item, $datasetID)
	{
		// Sanitize
		/*foreach (array("Brand", "Product Name", "Strain Name") as $itemIndex) {
			$item[$itemIndex] = $this->mysqliObject->real_escape_string($item[$itemIndex]);
			$item[$itemIndex] = addcslashes($item[$itemIndex], '%_');
		}*/
		// Cleanup of null entities
		if ($item["Joint Weight"] == "") $item["Joint Weight"] = 0;
		if ($item["Weight Sold"] == "") $item["Weight Sold"] = 0;
		//if ($item["Calculated Weight (Grams)"] == "") $item["Calculated Weight (Grams)"] = 0;
		
		// See if entry exists (check Sale ID and SKU), if so, just link to it in the linking table, otherwise make a new sales item entry
		/*$this->query = "SELECT `salesItemID`
						FROM `salesItems`
						WHERE `saleID` LIKE '".$item["Sale Id"]."'
						AND `sku` LIKE '".$item["SKU"]."'
						AND `discountName` LIKE '".mysqli_real_escape_string($this->mysqliObject, $item["Discount Name"])."'
						LIMIT 1;";*/
		$this->query = "SELECT `salesItemID`
						FROM `salesItems`
						WHERE `saleID` LIKE '".$item["Sale Id"]."'
						AND `sku` LIKE '".$item["SKU"]."'
						LIMIT 1;";
		$this->doSelectQuery();
		if ($this->results[0]->salesItemID != "") {
			// Sales item exists
			//echo '<pre>Duplicate:<br />';print_r($item);echo '</pre>';// DEBUG ONLY!!!
		} else {
			// New sales item, clean up and store
			foreach ($item as $index => $subItem) {
				$item[$index] = mysqli_real_escape_string($this->mysqliObject, $subItem);
			}
			// Query
			$this->query = "INSERT INTO `salesItems` (`transactionDate`, `transactionTime`, `saleID`, `category`, `brand`, `productName`, `strainName`, `packageID`, `weightUnit`, `jointWeight`, `sku`, `type`, `isRefund`, `quantity`, `customerType`, `customerMedID`, `finalSale`, `unitOfMeasure`, `discountAmount`, `discountName`, `employeeName`, `taxInDollars`, `form`, `sourceLicense`, `batchID`, `batchDate`, `isTested`, `cost`, `packageQuantity`, `totalQuantity`, `weightSold`) VALUES ('".$item["Transaction Date"]."', '".$item["Transaction Time"]."', '".$item["Sale Id"]."', '".$item["Category"]."', '".$item["Brand"]."', '".$item["Product Name"]."', '".$item["Strain Name"]."', '".$item["Package Id"]."', '".$item["Weight Unit"]."', '".$item["Joint Weight"]."', '".$item["SKU"]."', '".$item["Type"]."', '".$item["Is Refund"]."', '".$item["Quantity"]."', '".$item["Customer Type"]."', '".$item["Customer Med Id"]."', '".$item["Final Sale"]."', '".$item["Unit Of Measure"]."', '".$item["Discount Amount"]."', '".$item["Discount Name"]."', '".$item["Employee Name"]."', '".$item["Tax In Dollars"]."', '".$item["Form"]."', '".$item["Source License"]."', '".$item["Batch Id"]."', '".$item["Batch Date"]."', '".$item["Is Tested"]."', '".$item["Cost"]."', '".$item["Package Quantity"]."', '".$item["Total Quantity"]."', '".$item["Weight Sold"]."')";
			// Insert item
			//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
			$this->doInsertQuery();
		}
		
		//echo '<pre>';print_r($item);echo '</pre>'; // DEBUG ONLY!!!
		//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
	}
	
	public function getDistinctSalesDays($startDate, $endDate)
	{
		$startDateTs = strtotime($startDate);
		$endDateTs = strtotime($endDate);
		$this->query = "SELECT DISTINCT `transactionDate`
						FROM `salesItems`
						WHERE UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) >= $startDateTs
						AND UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) <= $endDateTs
						ORDER BY UNIX_TIMESTAMP(STR_TO_DATE(transactionDate,'%m/%d/%Y %h:%i:%s %p')) ASC;";
		//print_r($this->query); // DEBUG ONLY!!!
		$this->doSelectQuery();
		//echo '<pre>Distinct Sales Days<br />';print_r($this->results);echo '</pre>'; // DEBUG ONLY!!!
		foreach ($this->results as $result) {
			$return[] = $result->transactionDate;
		}
		return $return;
	}
	
	public function insertSalesTotals($salesDatasetID, $budtenderTotals, $grandTotals, $netTotals, $grossSales, $salesCost, $taxCharged, $netSales, $hourlySales)
	{
		$insertDate = date("Y-m-d H:i:s");
		// Budtender
		foreach ($budtenderTotals as $btName => $btReport) {
			$this->query = "INSERT INTO `salesReportsBudtender` (`salesDatasetID`, `budtender`, `tx`, `netSales`)
							VALUES ('".$salesDatasetID."', '".$btName."', '".$btReport["transactions"]."', '".$btReport["total"]."')";
			// Insert
			//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
			$this->doInsertQuery();
		}
		
		// Grand Totals
		foreach ($grandTotals as $tItem => $tValue) {
			$this->query = "INSERT INTO `salesReportsTotals` (`salesDatasetID`, `item`, `category`, `sales`)
							VALUES ('".$salesDatasetID."', '".$tItem."', 'grand', '".$tValue."')";
			// Insert
			//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
			$this->doInsertQuery();
		}
		
		// Net Totals
		$totalsArray = array('netTotals' => $netTotals, 
							'grossSales' => $grossSales, 
							'salesCost' => $salesCost, 
							'taxCharged' => $taxCharged, 
							'netSales' => $netSales);
		foreach ($totalsArray as $tItemName => $tArray) {
			foreach ($tArray as $tCat => $tValue) {
				$this->query = "INSERT INTO `salesReportsTotals` (`salesDatasetID`, `item`, `category`, `sales`)
								VALUES ('".$salesDatasetID."', '".$tItemName."', '".$tCat."', '".$tValue."')";
				// Insert
				//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
				$this->doInsertQuery();
			}
		}
		
		// Hourly
		foreach ($hourlySales as $hsHour => $hsReport) {
			$this->query = "INSERT INTO `salesReportsHourly` (`salesDatasetID`, `hour`, `tx`, `netSales`)
							VALUES ('".$salesDatasetID."', '".$hsHour."', '".$hsReport["transactions"]."', '".$hsReport["netSales"]."')";
			// Insert
			//echo '<pre>';print_r($this->query);echo '</pre>'; // DEBUG ONLY!!!
			$this->doInsertQuery();
		}
	}
}
?>
