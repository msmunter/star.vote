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
		$this->voterKeyLength = 12;
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
	
	private function invalidateVoterKey($key)
	{
		// 
	}
	
	private function generateVoterKey()
	{
		$this->voterID = $this->generateUniqueID($this->voterKeyLength, "voterKeys", "voterKey");
	}
	
	/*public function ajaxvote()
	{
		$this->voterID = $_POST['voterID'];
		$this->pollID = $_POST['pollID'];
		parse_str($_POST['votes'], $dirtyVoteArray);
		// Cleanup array
		foreach ($dirtyVoteArray as $index => $vote) {
			$indexBoom = explode('|', $index);
			$answerID = $indexBoom[1];
			unset($indexBoom);
			$voteArray[$answerID] = $vote;
		}
		if (!$this->model->voterExists($this->voterID)) {
			$this->model->insertVoter($this->voterID, $_SERVER['REMOTE_ADDR']);
		}
		$voteArrayToDestroy = $voteArray;
		// Verify no vote has been entered for this voter on this poll
		$yourVote = $this->model->getYourVote($this->voterID, $this->pollID);
		if (empty($yourVote)) {
			// No vote, get the answers to make sure we have a score for each
			$this->poll->answers = $this->model->getAnswersByPollID($this->pollID);
			foreach ($this->poll->answers as $answer) {
				if (!array_key_exists($answer->answerID, $voteArray)) {
					$voteArray[$answer->answerID] = 0;
				}
			}
			foreach ($voteArray as $answerID => $vote) {
				$this->votes[] = $vote;
				// Insert vote
				$this->model->insertVote($this->pollID, $this->voterID, $answerID, $vote);
				// Update the matrix. Maybe replace the windows with bricks?
				foreach ($voteArrayToDestroy as $answerID2 => $vote2) {
					if ($answerID != $answerID2) {
						if ($vote > $vote2) {
							$this->model->updateVoteMatrix($this->pollID, $answerID, $answerID2);
						} else if ($vote < $vote2) {
							$this->model->updateVoteMatrix($this->pollID, $answerID2, $answerID);
						} // and do nothing if they're equal
					}
				}
				unset($voteArrayToDestroy[$answerID]);
			}
			$this->model->incrementPollVoteCount($this->pollID);
		} else {
			$return['caution'] = 'Your vote had already been recorded for this poll';
		}
		unset($yourVote);
		$this->poll = $this->model->getPollByID($this->pollID);
		if (empty($this->poll->answers)) $this->poll->answers = $this->model->getAnswersByPollID($this->pollID);
		$this->yourVote = $this->model->getYourVote($this->voterID, $this->pollID);
		$return['html'] .= $this->ajaxInclude('view/poll/yourvote.view.php');
		echo json_encode($return);
	}
	
	private function setVoterID()
	{
		// Check cookie for voter ID
		if (strlen($_COOKIE['voterID']) > 0) {
			$this->voterID = $_COOKIE['voterID'];
		}
		// Generate voter ID if necessary
		if (strlen($this->voterID) < 1) {
			$this->voterID = $this->generateUniqueID(10, "voters", "voterID");
			// Save a cookie with their voter ID
			$cookieExpires = strtotime('+5 years');
			setcookie("voterID", $this->voterID, $cookieExpires);
		}
	}*/
	
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
