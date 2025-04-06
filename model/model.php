<?php
class Model
{
	// We'll be needing the following from a config file in /srv/www/dbconfig/
	private $host;
	private $database;
	private $user;
	private $pass;
	// Rest of this we acquire/use elsewhere
	private $table = 'voters';
	public $mysqliObject;
	public $query;
	public $insertID;
	private $mysqliResult;
	public $results = array();
	
	public function __construct()
	{
		// Grab db config file
		$dbConfig = parse_ini_file('/srv/www/dbconfig/starvote_db.ini');
		// Use different server for test and live
		if ($_SERVER['SERVER_NAME'] == 'classic.star.vote') {
			// Live
			$this->host = $dbConfig['live_host'];
			$this->database = $dbConfig['live_database'];
			$this->user = $dbConfig['live_user'];
			$this->pass = $dbConfig['live_pass'];
		} else {
			// Test
			$this->host = $dbConfig['test_host'];
			$this->database = $dbConfig['test_database'];
			$this->user = $dbConfig['test_user'];
			$this->pass = $dbConfig['test_pass'];
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
	
	public function escapeString($string)
	{
		return $this->mysqliObject->real_escape_string($string);
	}
}
?>