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
					$this->voterID = $_COOKIE['voterID'];
					if ($this->model->userHasVoted($this->voterID)) {
						$this->hasVoted = true;
					} else $this->hasVoted = false;
				} else $this->hasVoted = false;
				// Load the answers
				if (empty($this->poll)) {
					$this->error = "ERROR: Poll not found";
				} else {
					$this->poll->answers = $this->model->getAnswersByPollID($this->URLdata);
					if (count($this->poll->answers) > 0) {
						foreach ($this->poll->answers as $index => $answer) {
							$this->poll->answers[$index]->voterCount = $this->model->getAnswerVoterCount($answer->answerID);
						}
					}
					$this->poll->totalVoterCount = $this->model->getPollVoterCount($this->poll->pollID);
				}
			}
		} else {
			$this->error = 'Must provide poll ID';
		}
	}
	
	public function vote()
	{
		if (!empty($_COOKIE['voterID'])) {
			$this->voterID = $_COOKIE['voterID'];
		}
		
		// Be sure to save a cookie with this poll so they can't vote on it again immediately.
		$cookieExpires = strtotime('now +10 years');
		//setcookie("voterID", $voterID, $cookieExpires, "/");
	}
	
	private function generatePollID()
	{
		
	}
}
?>
