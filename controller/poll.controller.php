<?php
class PollController extends Controller
{
	// Admin Levels
	/*public $adminLevel = array(
		'index' => 2
	);*/
	public $poll;
	public $polls;
	public $verifiedVotingTypes;
	public $currentUserVerified;
	
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
		// Display form for poll creation
		$this->title = 'Create Poll';
	}
	
	public function processPollSet($pollSet)
	{
		if (empty($this->model)) $this->model = new PollModel();
		if (empty($pollSet)) $this->model->getMostRecentPolls(0, $count);
		if (count($pollSet) > 0) {
			foreach ($pollSet as $index => $poll) {
				$this->pollSet[$index]->totalVoterCount = $this->model->getPollVoterCount($poll->pollID);
			}
			unset($poll);
		}
	}
	
	public function history()
	{
		$this->title = 'More Polls';
		// Poll history
		$this->mostRecentPolls = $this->model->getMostRecentPolls(0, 10);
		$this->processPollSet($this->mostRecentPolls);
		$this->mostPopularPolls = $this->model->getMostPopularPolls(0, 10);
		$this->processPollSet($this->mostPopularPolls);
	}
	
	public function ajaxinsertpoll()
	{
		$this->verifiedVotingTypes = array('gkc', 'eml', 'gau');
		// Actually saves the poll
		if ($_POST['pollQuestion'] != "") {
			$this->pollQuestion = $_POST['pollQuestion'];
			// Parse form string
			parse_str($_POST['pollAnswers'], $this->pollAnswerSet);
			// Rearrange into a more useful array
			foreach ($this->pollAnswerSet as $pollInputName => $pollAnswer) {
				$inBoom = explode('answer', $pollInputName);
				if (trim($pollAnswer) != "") $this->pollAnswers[$inBoom[1]] = $pollAnswer;
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
				if (!empty($_POST['fsCustomSlug'])) {
					$return['customSlug'] = $_POST['fsCustomSlug'];
				}
				$return['pollID'] = $newPollID;
				if ($this->user->userID > 0) {
					$userID = $this->user->userID;
				} else $userID = 0;
				// Cleanup type if needed
				if (!in_array($_POST['fsVerifiedVotingType'], $this->verifiedVotingTypes)) $_POST['fsVerifiedVotingType'] = 'gkc';
				// Insert actual
				$this->model->insertPoll($newPollID, $this->pollQuestion, $this->pollAnswers, $_POST['fsRandomOrder'], $_POST['fsPrivate'], $_SERVER['REMOTE_ADDR'], $_POST['fsCustomSlug'], $_POST['fsVerifiedVoting'], $_POST['fsVerifiedVotingType'], $userID);
				$return['html'] .= 'Poll saved! Loading results...';
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
				// Set title
				$this->title = $this->poll->question;
				// Init voter
				$this->initVoter(null);
				// Determine whether user has voted
				if ($this->model->userHasVoted($this->voterID, $this->URLdata)) {
					$this->hasVoted = true;
					// Get their vote
					$this->yourVote = $this->model->getYourVote($this->voterID, $this->poll->pollID);
				} else $this->hasVoted = false;
				// Load the answers
				if (empty($this->poll)) {
					$this->error = "ERROR: Poll not found";
				} else {
					$this->poll->answers = $this->model->getAnswersByPollID($this->URLdata);
					$this->poll->voterCount = $this->model->getPollVoterCount($this->URLdata);
					// If we're supposed to randomize answers let's do that now
					if ($this->poll->randomAnswerOrder && $this->hasVoted == false) shuffle($this->poll->answers);
					// HEY YOU! Figure out how to do a multi-way tie here, taps into resultsactual.view.php
					$this->poll->topAnswers = $this->model->getTopAnswersByPollID($this->URLdata);
					foreach ($this->poll->topAnswers as $index => $answer) {
						$this->poll->topAnswers[$index]->avgVote = $this->model->getAvgVoteByAnswerID($answer->answerID);
					}
					$this->poll->runoffResults = $this->model->getRunoffResultsByAnswerID($this->URLdata, $this->poll->topAnswers[0]->answerID, $this->poll->topAnswers[1]->answerID);
					if ($this->poll->runoffResults['first']['answerID'] == $this->poll->topAnswers[0]->answerID) {
						$this->poll->runoffResults['first']['question'] = $this->poll->topAnswers[0]->text;
						$this->poll->runoffResults['second']['question'] = $this->poll->topAnswers[1]->text;
					} else {
						$this->poll->runoffResults['first']['question'] = $this->poll->topAnswers[1]->text;
						$this->poll->runoffResults['second']['question'] = $this->poll->topAnswers[0]->text;
					}
					// Check for ties beyond this
					if ($this->poll->runoffResults['tie']) {
						$ignoreFirstTwo = 1;
						$this->poll->runoffResults['tieEndsAt'] = 2;
						foreach ($this->poll->topAnswers as $topAnswer) {
							if ($ignoreFirstTwo > 2 && $this->poll->runoffResults['second']['votes'] == $topAnswer->votes) {
								$this->poll->runoffResults['tieEndsAt']++;
							}
							$ignoreFirstTwo++;
						}
					}
					// Runoff matrix
					$this->poll->rawRunoff = $this->model->getRunoffResultsRawByPollID($this->URLdata);
					foreach ($this->poll->topAnswers as $index => $answer) {
						$this->poll->runoffAnswerArray[$answer->answerID] = $answer;
					}
					foreach ($this->poll->rawRunoff as $runoff) {
						$this->poll->orderedRunoff[$runoff->gtID][$runoff->ltID] = $runoff;
					}
					// Voter and point counts
					$this->poll->totalVoterCount = $this->model->getPollVoterCount($this->URLdata);
					$this->poll->totalPointCount = $this->model->getPollPointCount($this->URLdata);
					if ($this->poll->runoffResults['tie']) {
						$this->poll->noPreferenceCount = $this->poll->totalVoterCount - ($this->poll->runoffResults['first']['votes'] * 2);
					} else {
						$this->poll->noPreferenceCount = $this->poll->totalVoterCount - ($this->poll->runoffResults['first']['votes'] + $this->poll->runoffResults['second']['votes']);
					}
					// Condorcet
					$this->poll->condorcet = true;
					foreach ($this->poll->orderedRunoff[$this->poll->runoffResults['first']['answerID']] as $comIndex => $item) {
						$comVotes = $this->poll->orderedRunoff[$comIndex][$this->poll->runoffResults['first']['answerID']]->votes;
						if ($item->votes <= $comVotes) {
							$this->poll->condorcet = false;
						}
					}
				}
			}
		} else {
			$this->error = 'Must provide poll ID';
		}
	}
	
	public function voterkeys()
	{
		$this->pollID = $this->URLdata;
		if (!empty($this->pollID)) {
			$this->poll = $this->model->getPollByID($this->pollID);
			if ($this->poll) {
				$this->title = 'Voter Keys for "'.$this->poll->question.'"';
				$this->voterKeys = $this->model->getVoterKeysByPollID($this->pollID);
				$this->voterKeyCount = count($this->voterKeys);
			} else $this->error = 'Invalid poll ID';
		} else $this->error = 'Must provide a poll ID';
	}
	
	public function ajaxgeneratevoterkeys()
	{
		if (!empty($_POST['pollID'])) {
			// Get poll
			$this->poll = $this->model->getPollByID($_POST['pollID']);
			if ($this->poll) {
				if ($this->user->userID == $this->poll->userID) {
					$keys = $this->generateVoterKeys($this->poll->pollID, 16, $_POST['numKeys']);
					$return['keysGenerated'] = $_POST['numKeys'];
					// Fetch codes fresh from DB in case something didn't make it in
					$this->voterKeys = $this->model->getVoterKeysByPollID($this->poll->pollID);
					$this->voterKeyCount = count($this->voterKeys);
					$return['html'] .= $this->ajaxInclude('view/poll/existingvoterkeys.view.php');
					$return['html'] .= '</p>';
				} else $return['error'] = 'Not admin of requested poll';
			} else $return['error'] = 'Invalid Poll ID';
		} else $return['error'] = 'Invalid Poll ID';
		echo json_encode($return);
	}
	
	private function generateVoterKeys($pollID, $keyLength, $numKeys)
	{
		if (!$keyLength || $keyLength < 8) $keyLength = 8;
		for ($i = 0; $i < $numKeys; $i++) {
			$key = bin2hex(random_bytes($keyLength / 2));
			$keys[] = $key;
			$this->model->insertVoterKey($pollID, $key);
		}
		return $keys;
	}
	
	public function ajaxvote()
	{
		// Initialize voter (will provide $this->voterID)
		$this->initVoter($_POST['voterID']);
		$this->pollID = $_POST['pollID'];
		$this->poll = $this->model->getPollByID($this->pollID);
		$voterKeyResult = $this->model->verifyVoterKey($_POST['voterKey'], $this->pollID);
		// Determine eligibility if necessary
		if (($this->poll->verifiedVoting && $voterKeyResult->pollID) || $this->poll->verifiedVoting == false) {
			// Determine whether this key has voted
			if (empty($voterKeyResult->voteTime)) {
				parse_str($_POST['votes'], $dirtyVoteArray);
				// Cleanup array
				foreach ($dirtyVoteArray as $index => $vote) {
					$indexBoom = explode('|', $index);
					$answerID = $indexBoom[1];
					unset($indexBoom);
					$voteArray[$answerID] = $vote;
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
						$voteTime = date("Y-m-d H:i:s");
						$this->model->insertVote($this->pollID, $this->voterID, $answerID, $vote, $voteTime);
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
					// If a verified vote, write extra db info
					if ($this->poll->verifiedVoting) {
						$this->model->updateVoterKeyEntry($_POST['voterKey'], $this->pollID, $this->voterID, $voteTime);
					}
				} else {
					$return['caution'] = 'Your vote had already been recorded for this poll';
				}
				unset($yourVote);
				$this->poll = $this->model->getPollByID($this->pollID);
				if (empty($this->poll->answers)) $this->poll->answers = $this->model->getAnswersByPollID($this->pollID);
				$this->yourVote = $this->model->getYourVote($this->voterID, $this->pollID);
				$return['html'] .= $this->ajaxInclude('view/poll/yourvote.view.php');
			} else {
				$return['caution'] = 'This key has already been used to record a vote on this poll';
			}
		} else {
			// Failed eligibility
			$return['error'] .= 'Invalid voter key';
		}
		echo json_encode($return);
	}
	
	public function ajaxresults()
	{
		$this->URLdata = $_POST['pollID'];
		$this->results();
		// Is eligible to see the results?
		if (($this->poll->verifiedVoting && $this->user->userID == $this->poll->userID) || ($this->poll->verifiedVoting && $this->hasVoted) || $this->poll->verifiedVoting == false) {
			$return['results'] = $this->ajaxInclude('view/poll/resultsactual.view.php');
			$return['runoffmatrix'] = $this->ajaxInclude('view/poll/runoffmatrix.view.php');
		} else {
			$return['results'] = 'Results cannot be viewed yet';
			$return['runoffmatrix'] = '';
		}
		echo json_encode($return);
	}
	
	public function ajaxrunoffmatrix()
	{
		$pollID = $_POST['pollID'];
		$this->poll = $this->model->getPollByID($pollID);
		$this->initVoter(false);
		// Is eligible to see the results?
		if (($this->poll->verifiedVoting && $this->user->userID == $this->poll->userID) || ($this->poll->verifiedVoting && $this->model->userHasVoted($this->voterID, $pollID)) || $this->poll->verifiedVoting == false) {
			$this->poll->rawRunoff = $this->model->getRunoffResultsRawByPollID($pollID);
			$this->poll->voterCount = $this->model->getPollVoterCount($pollID);
			$this->poll->answers = $this->model->getAnswerByPollIDScoreOrder($pollID);
			foreach ($this->poll->answers as $index => $answer) {
				$this->poll->runoffAnswerArray[$answer->answerID] = $answer;
			}
			foreach ($this->poll->rawRunoff as $runoff) {
				$this->poll->orderedRunoff[$runoff->gtID][$runoff->ltID] = $runoff;
			}
			$return['html'] = $this->ajaxInclude('view/poll/runoffmatrix.view.php');
		} else {
			$return['html'] = 'Results cannot be viewed yet';
		}
		echo json_encode($return);
	}
	
	public function ajaxloadmorepolls()
	{
		if (empty($_POST['index'])) {
			$index = 0;
		} else {
			$index = $_POST['index'];
		}
		if ($_POST['pollType'] == 'r') {
			$this->pollSet = $this->model->getMostRecentPolls($index, 10);
		} else {
			$this->pollSet = $this->model->getMostPopularPolls($index, 10);
		}
		$this->processPollSet($this->pollSet);
		$return['html'] = $this->ajaxInclude('view/poll/pollset.view.php');
		echo json_encode($return);
	}
	
	public function ajaxcheckcustomslug()
	{
		$regexResult = preg_match('/^[a-z0-9]{4,16}$/', $_POST['slug']);
		if ($regexResult === 0 || $regexResult === false) {
			if (strlen($_POST['slug']) < 4) {
				$return['html'] = 'Slug too short; must be 4-16 characters';
				$return['returncode'] = '0';
			} else if (strlen($_POST['slug']) > 16) {
				$return['html'] = 'Slug too long; must be 4-16 characters';
				$return['returncode'] = '0';
			} else {
				$return['html'] = 'Slug may only contain a-z (lower case) and 0-9';
				$return['returncode'] = '0';
			}
		} else {
			// Passes regex
			$pollBySlug = $this->model->getPollByCustomSlug($_POST['slug']);
			$pollByID = $this->model->getPollByID($_POST['slug']);
			if (!empty($pollBySlug)) {
				$return['html'] = 'Slug taken';
				$return['returncode'] = '0';
			} else if (!empty($pollByID)) {
				$return['html'] = 'Slug not available, matches existing poll ID';
				$return['returncode'] = '0';
			} else {
				$return['html'] = 'Slug available';
				$return['returncode'] = '1';
			}
		}
		echo json_encode($return);
	}
	
	public function csv()
	{
		$this->ajax = 1;
		$this->doHeader = 0;
		$this->doFooter = 0;
		$this->poll = $this->model->getPollByID($this->URLdata);
		if (!empty($this->poll)) {
			$this->poll->answers = $this->model->getAnswersByPollID($this->URLdata);
			$this->poll->ballots = $this->model->getBallotsByPollID($this->URLdata);
			// Process ballots into a single, cohesive array
			$this->poll->processedBallots = $this->processBallots($this->poll->ballots);
		} else $this->error = 'Poll not found';
	}
	
	public function voterkeyscsv()
	{
		$this->ajax = 1;
		$this->doHeader = 0;
		$this->doFooter = 0;
		$this->poll = $this->model->getPollByID($this->URLdata); 
		if (!empty($this->poll->pollID)) {
			$this->voterKeys = $this->model->getVoterKeysByPollID($this->poll->pollID);
		} else $this->error = 'Poll not found';
	}
	
	public function ajaxcheckvoterkey()
	{
		$regexResult = preg_match('/^[a-z0-9]{16}$/', $_POST['voterKey']);
		if ($regexResult === 0 || $regexResult === false) {
			if (strlen($_POST['voterKey']) < 4) {
				$return['html'] = 'Key too short; must be 16 characters';
				$return['returncode'] = '0';
			} else if (strlen($_POST['voterKey']) > 16) {
				$return['html'] = 'Key too long; must be 16 characters';
				$return['returncode'] = '0';
			} else {
				$return['html'] = 'Key will only contain a-z (lower case) and 0-9';
				$return['returncode'] = '0';
			}
		} else {
			// Passes regex
			$voterKeyResult = $this->model->verifyVoterKey($_POST['voterKey'], $_POST['pollID']);
			if (!empty($voterKeyResult->pollID)) {
				// Valid key, see if used already
				if (!empty($voterKeyResult->voteTime)) {
					$return['html'] = 'Voter key already used';
					$return['returncode'] = '0';
				} else {
					$return['html'] = 'Voter key valid';
					$return['returncode'] = '1';
				}
			} else {
				$return['html'] = 'Voter key invalid';
				$return['returncode'] = '0';
			}
		}
		echo json_encode($return);
	}
	
	public function ajaxcvr()
	{
		$this->ajax = 1;
		$this->doHeader = 0;
		$this->doFooter = 0;
		$this->poll = $this->model->getPollByID($_POST['pollID']);
		$this->initVoter(false);
		if (($this->poll->verifiedVoting && $this->user->userID == $this->poll->userID) || ($this->poll->verifiedVoting && $this->model->userHasVoted($this->voterID, $this->poll->pollID)) || $this->poll->verifiedVoting == false) {
			if (!empty($this->poll)) {
				$this->poll->answers = $this->model->getAnswersByPollID($_POST['pollID']);
				$this->poll->ballots = $this->model->getBallotsByPollID($_POST['pollID']);
				// Process ballots into a single, cohesive array
				$this->poll->processedBallots = $this->processBallots($this->poll->ballots);
			} else $return['error'] = 'Poll not found';
			$return['html'] = $this->ajaxInclude('view/poll/cvrhtml.view.php');
		} else {
			$return['html'] = 'Results cannot be viewed yet';
		}
		echo json_encode($return);
	}
	
	private function processBallots($ballots)
	{
		foreach ($ballots as $ballot) {
			if (empty($return[$ballot->voterID])) {
				// New, establish a base and populate first vote
				$return[$ballot->voterID]['voteTime'] = $ballot->voteTime;
				$return[$ballot->voterID]['pollID'] = $ballot->pollID;
				$return[$ballot->voterID]['votes'][$ballot->answerID] = $ballot->vote;
			} else {
				// Exists, populate
				$return[$ballot->voterID]['votes'][$ballot->answerID] = $ballot->vote;
			}
		}
		return $return;
	}
	
	private function initVoter($voterID)
	{
		$cookieExpires = strtotime('+5 years');
		// If they passed an ID check it
		if (strlen($voterID) > 0) {
			if ($this->model->voterExists($_COOKIE['voterID'])) {
				// Set voterID in class, cookie, and session
				$this->voterID = $voterID;
				setcookie("voterID", $this->voterID, $cookieExpires);
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
				setcookie("voterID", $this->voterID, $cookieExpires);
			}
		}
		// Generate voter ID if necessary
		if (strlen($this->voterID) < 1) {
			$this->voterID = $this->generateUniqueID(10, "voters", "voterID");
			// Save voter to DB
			$this->model->insertVoter($this->voterID, $_SERVER['REMOTE_ADDR']);
			// Save a cookie with their voter ID
			setcookie("voterID", $this->voterID, $cookieExpires);
			// Save session variable
			$_SESSION['voterID'] = $this->voterID;
			return true;
		} else {
			// Didn't get an ID, something went awry
			return false;
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
