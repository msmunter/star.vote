<?php
class VoterController extends Controller
{
	// Admin Levels
	/*public $adminLevel = array(
		'index' => 2
	);*/
	public $voterID;
	public $voterIDLength;
	public $voterKey;
	public $voterKeyLength;
	
	public function __construct()
	{
		$this->voterIDLength = 10;
		$this->voterKeyLength = 16;
	}
	
	public function initVoter($voterID)
	{
		$cookieExpires = strtotime('+5 years');
		// If they passed an ID check it
		if (strlen($voterID) > 0) {
			if ($this->model->voterExists($_COOKIE['voterID'])) {
				// Set voterID in class, cookie, and session
				$this->voterID = $voterID;
				setcookie("voterID", $this->voterID, $cookieExpires, '/');
				$_SESSION['voterID'] = $this->voterID;
			}
		} else if (strlen($_COOKIE['voterID']) > 0) {
			// Determine if valid voter ID exists in cookie
			if ($this->model->voterExists($_COOKIE['voterID'])) {
				$this->voterID = $_COOKIE['voterID'];
				// Set session to cookie
				$_SESSION['voterID'] = $_COOKIE['voterID'];
			}
		} else if (strlen($_SESSION['voterID']) > 0) {
			// Determine if valid voter ID exists in session.
			if ($this->model->voterExists($_SESSION['voterID'])) {
				$this->voterID = $_SESSION['voterID'];
				// Set cookie to session
				setcookie("voterID", $this->voterID, $cookieExpires, '/');
			}
		}
		// Generate voter ID if necessary
		if (strlen($this->voterID) < 1) {
			$this->voterID = $this->generateUniqueID(10, "voters", "voterID");
			// Save voter to DB
			$this->model->insertVoter($this->voterID, $_SERVER['REMOTE_ADDR']);
			// Save a cookie with their voter ID
			setcookie("voterID", $this->voterID, $cookieExpires, '/');
			// Save session variable
			$_SESSION['voterID'] = $this->voterID;
			return true;
		} else {
			// Didn't get an ID, something went awry
			return false;
		}
		
	}
	
	private function verifyVoterKey($pollID, $voterKey)
	{
		if (strlen($voterID) < 1) {
			// voterID too short
			$this->error = 'Invalid voter ID (short)';
		} else if (strlen($voterID) > $this->voterIDLength) {
			// voterID too long
			$this->error = 'Invalid voter ID (long)';
		} else if (strlen($voterKey) < 1) {
			// voterKey too short
			$this->error = 'Invalid voter key (short)';
		} else if (strlen($voterKey) > $this->voterKeyLength) {
			// voterKey too long
			$this->error = 'Invalid voter key (long)';
		} else {
			// voterID and voterKey are alright lengths
			if ($this->model->verifyVoterKey($pollID, $voterKey)) {
				return true;
			} else return false;
		}
	}
	
	private function generateVoterKey()
	{
		$this->voterID = $this->generateUniqueID($this->voterKeyLength, "voterKeys", "voterKey");
	}
	
	private function generateUniqueID($length, $table, $column)
	{
		if ($length < 1) $length = 8;
		if (strlen($table) > 0 && strlen($column) > 0) {
			// Generate ID
			$oUtility = new UtilityController();
			$generatedIDIsTaken = true;
			while ($generatedIDIsTaken) {
				$newID = $oUtility->generateRandomString($type = 'distinctlower', $length);
				$generatedIDIsTaken = $this->model->isGeneratedIDTaken($table, $column, $newID);
			}
			return $newID;
		} else return false;
	}
}
?>
