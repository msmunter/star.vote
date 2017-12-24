<?php
class PollController extends Controller
{
	// Admin Levels
	/*public $adminLevel = array(
		'index' => 2
	);*/
	public $poll;
	public $polls;
	
	/*public function __construct()
	{
		
	}*/
	
	public function index()
	{
		// Show poll history on this page
		$this->history();
	}
	
	public function create()
	{
		// Display of form for poll creation
	}
	
	public function history()
	{
		// Poll history
		$this->mostRecentPolls = $this->model->getMostRecentPolls(10);
		if (count($this->mostRecentPolls) > 0) {
			foreach ($this->mostRecentPolls as $index => $poll) {
				$this->mostRecentPolls[$index]->totalVoterCount = $this->model->getPollVoterCount($poll->pollID);
			}
			unset($poll);
		}
	}
	
	public function ajaxinsertpoll()
	{
		// Actually saves the poll
		if ($_POST['pollQuestion'] != "") {
			$this->pollQuestion = $_POST['pollQuestion'];
			// Parse form string
			parse_str($_POST['pollAnswers'], $this->pollAnswerSet);
			// Rearrange into a more useful array
			foreach ($this->pollAnswerSet as $pollInputName => $pollAnswer) {
				$inBoom = explode('answer', $pollInputName);
				$this->pollAnswers[$inBoom[1]] = $pollAnswer;
			}
			unset($this->pollAnswerSet);
			// See that we have some answers and they aren't blank
			$answerCount = 0;
			foreach ($this->pollAnswers as $index => $answer) {
				if ($answer == '') {
					// Last question exception
					if (array_key_exists($index+1, $this->pollAnswers)) {
						// Next item exists, this one can't be blank
						$return['error'] = 'Answers cannot be blank';
						break;
					}
				} else {
					$answerCount++;
				}
			}
			if ($answerCount >= 2 && !$return['error']) {
				// ALL SET, let's save this poll
				// Generate ID
				$oUtility = new UtilityController();
				$pollIDIsTaken = true;
				while ($pollIDIsTaken) {
					$newPollID = $oUtility->generateRandomString($type = 'distinctlower', $length = 8);
					$pollIDIsTaken = $this->model->isPollIDTaken($newPollID);
				}
				$return['pollID'] = $newPollID;
				// Insert actual
				$this->model->insertPoll($newPollID, $this->pollQuestion, $this->pollAnswers, $_SERVER['REMOTE_ADDR']);
				$return['html'] = 'Poll saved! Loading results...';
			} else {
				$return['error'] = 'Must provide at least two possible answers';
			}
		} else {
			$return['error'] = 'Must provide a question';
		}
		echo json_encode($return);
	}
	
	public function ajaxaddanswer()
	{
		// Outputs a new input field and associate trimmings
		if ($_POST['nextAnswerID'] > 0) {
			$this->answerID = $_POST['nextAnswerID'];
			$return['nextAnswer'] = $this->ajaxInclude('view/poll/pollinput.view.php');
			$return['nextAnswerID'] = $this->answerID + 1;
		} else $return['error'] = 'No question number given';
		echo json_encode($return);
	}
	
	public function results()
	{
		// Views single poll results
		if ($this->URLdata != '') {
			if (strlen($this->URLdata) < 8 || strlen($this->URLdata) > 8) {
				$this->error = 'Invalid poll ID (length)';
			} else if (!ctype_alnum($this->URLdata)) {
				$this->error = 'Invalid poll ID (characters)';
			} else {
				$this->poll = $this->model->getPollByID($this->URLdata);
				// Determine whether use has voted
				if (!empty($_COOKIE['voterID'])) {
					$this->setVoterID();
					if ($this->model->userHasVoted($this->voterID, $this->URLdata)) {
						$this->hasVoted = true;
						// Get their vote
						$this->yourVote = $this->model->getYourVote($this->voterID, $this->poll->pollID);
					} else $this->hasVoted = false;
				} else {
					$this->hasVoted = false;
					$this->setVoterID();
				}
				// Load the answers
				if (empty($this->poll)) {
					$this->error = "ERROR: Poll not found";
				} else {
					$this->poll->answers = $this->model->getAnswersByPollID($this->URLdata);
					if (count($this->poll->answers) > 0) {
						foreach ($this->poll->answers as $index => $answer) {
							//$this->poll->answers[$index]->voterCount = $this->model->getAnswerVoterCount($answer->answerID);
						}
					}
					$this->poll->totalVoterCount = $this->model->getPollVoterCount($this->poll->pollID);
				}
			}
		} else {
			$this->error = 'Must provide poll ID';
		}
	}
	
	public function ajaxvote()
	{
		$this->voterID = $_POST['voterID'];
		$this->pollID = $_POST['pollID'];
		//$return['html'] .= 'voterID: '.$this->voterID.'<br />'; // DEBUG ONLY!!!
		parse_str($_POST['votes'], $voteArray);
		if (!$this->model->voterExists($this->voterID)) {
			$this->model->insertVoter($this->voterID, $_SERVER['REMOTE_ADDR']);
		}
		foreach ($voteArray as $index => $vote) {
			$indexBoom = explode('|', $index);
			$this->answerID = $indexBoom[1];
			unset($indexBoom);
			$this->votes[] = $vote;
			//$return['html'] .= 'ID: '.$this->answerID.'; Vote: '.$vote.'<br />'; // DEBUG ONLY!!!
			// Insert vote
			$this->model->insertVote($this->pollID, $this->voterID, $this->answerID, $vote);
			$return['html'] .= $this->model->query; // DEBUG ONLY!!!
		}
		$this->poll->answers = $voteArray;
		$return['html'] .= $this->ajaxInclude('view/poll/yourvote.view.php');
		echo json_encode($return);
	}
	
	public function ajaxresults()
	{
		$this->URLdata = $_POST['pollID'];
		$this->results();
		$return['html'] = $this->ajaxInclude('view/poll/resultsactual.view.php');
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
