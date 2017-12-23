<?php
class Model
{
	// SET THE FOLLOWING DATABASE INFO
	private $host = 'localhost';
	private $database = 'starvote';
	private $table = 'users';
	private $user = 'starvote';
	private $pass = '';
	// THANKS! NOW STOP SETTING THINGS, PLEASE
	public $mysqliObject;
	public $query;
	public $insertID;
	private $mysqliResult;
	public $results = array();
	
	public function __construct()
	{
		// Use different server for test and live
		if ($_SERVER['SERVER_NAME'] == 'starvote.msmunter.com') {
			$this->database = 'starvoteTest';
			$this->user = 'starvoteTest';
			$this->pass = 'EQeTRxunXHWzAUnG';
		}
		// Instantiate! (Host, username, password, database)
	    $this->mysqliObject = new mysqli($this->host, $this->user, $this->pass, $this->database);
	    if (mysqli_connect_errno()) {
	        echo 'Could not connect to database: ' . mysqli_connect_error();
	    }
	}
	
	public function __destruct()
	{
		// Close connection
	    $this->mysqliObject->close();
	}
	
	private function doQuery()
	{
	    // Query if empty
	    if ($this->query == '' && $this->table != '') $this->query = 'SELECT * FROM `'.$this->table.'`';
	    // Run query, save result or error
	    if (!$this->mysqliResult = $this->mysqliObject->query($this->query)) echo 'Query failed: ' . $this->mysqliObject->error;
	}
	
	public function doSelectQuery()
	{
		$this->doQuery();
		// Reset the results array so we don't have old data hanging around
		$this->results = array();
		// Make usable object out of result set
        while ($obj = $this->mysqliResult->fetch_object()) {
            // Save each as a result (will be objects)
            $this->results[] = $obj;
        }
        // Free result set
        $this->mysqliResult->close();
	}
	
	public function doInsertQuery()
	{
	    // Sanitize query/run
		$this->doQuery();
	    // Save the insert ID
	    $this->insertID = $this->mysqliObject->insert_id;
	}
	
	public function doUpdateQuery()
	{
		// Sanitize query/run
	    $this->doQuery();
	}
	
	public function doDeleteQuery()
	{
		// Sanitize query/run
	    $this->doQuery();
	}
}
?>