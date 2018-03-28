<?php
class SurveyController extends Controller
{
	// Admin Levels
	/*public $adminLevel = array(
		'index' => 2
	);*/
	public $survey;
	
	/*public function __construct()
	{
		
	}*/
	
	public function index()
	{
		$this->title = 'Your Surveys';
	}
	
	public function create()
	{
		$this->title = 'Create Survey';
	}
	
	public function results()
	{
		// Views single survey results
		if ($this->URLdata != '') {
			if (strlen($this->URLdata) < 8 || strlen($this->URLdata) > 8) {
				$this->error = 'Invalid survey ID (length)';
			} else if (!ctype_alnum($this->URLdata)) {
				$this->error = 'Invalid survey ID (characters)';
			} else {
				$this->survey = $this->model->getSurveyByID($this->URLdata);
				if ($this->survey) {
					// Set title
					$this->title = $this->survey->question;
					// Get polls
					$this->survey->polls = $this->model->getPollsBySurveyID($this->survey->surveyID);
					// Populate polls
					$mPoll = new PollModel();
					foreach ($this->survey->polls as $poll) {
						$poll->answers = $mPoll->getAnswersByPollID($poll->pollID);
					}
					unset($mPoll);
					// Init voter
					/*$this->initVoter(null);
					// Determine whether user has voted
					if ($this->model->userHasVoted($this->voterID, $this->URLdata)) {
						$this->hasVoted = true;
						// Get their vote
						$this->yourVote = $this->model->getYourVote($this->voterID, $this->poll->pollID);
					} else $this->hasVoted = false;*/
					// Randomize answers if necessary
					if ($this->survey->randomOrder && $this->hasVoted == false) {
						foreach ($this->survey->polls as $poll) {
							shuffle($poll->answers);
						}
					}
				} else {
					$this->error = "Survey does not exist";
				}
			}
		} else {
			$this->error = 'Must provide survey ID';
		}
	}
	
	public function voterkeys()
	{
		$this->surveyID = $this->URLdata;
		if (!empty($this->surveyID)) {
			$this->survey = $this->model->getSurveyByID($this->surveyID);
			if ($this->survey) {
				$this->title = 'Voter Keys for "'.$this->survey->title.'"';
				$this->voterKeys = $this->model->getVoterKeysBySurveyID($this->surveyID);
				$this->voterKeyCount = count($this->voterKeys);
				$this->usedVoterKeyCount = $this->model->getUsedVoterKeyCountBySurveyID($this->surveyID);
			} else $this->error = 'Invalid poll ID';
		} else $this->error = 'Must provide a poll ID';
	}
	
	public function ajaxgeneratevoterkeys()
	{
		if (!empty($_POST['surveyID'])) {
			// Get survey
			$this->survey = $this->model->getSurveyByID($_POST['surveyID']);
			if ($this->survey) {
				if ($this->user->userID == $this->survey->userID) {
					$keys = $this->generateVoterKeys($this->survey->surveyID, 16, $_POST['numKeys']);
					$return['keysGenerated'] = $_POST['numKeys'];
					// Fetch codes fresh from DB in case something didn't make it in
					$this->voterKeys = $this->model->getVoterKeysBySurveyID($this->survey->surveyID);
					$this->voterKeyCount = count($this->voterKeys);
					$return['html'] .= $this->ajaxInclude('view/survey/existingvoterkeys.view.php');
					$return['html'] .= '</p>';
				} else $return['error'] = 'Not admin of requested survey';
			} else $return['error'] = 'Invalid survey ID';
		} else $return['error'] = 'Invalid survey ID';
		echo json_encode($return);
	}
	
	private function generateVoterKeys($surveyID, $keyLength, $numKeys)
	{
		if (!$keyLength || $keyLength < 8) $keyLength = 8;
		for ($i = 0; $i < $numKeys; $i++) {
			$key = bin2hex(random_bytes($keyLength / 2));
			$keys[] = $key;
			$this->model->insertVoterKey($surveyID, $key);
		}
		return $keys;
	}
	
	public function ajaxcheckcustomsurveyslug()
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
			$surveyBySlug = $this->model->getSurveyByCustomSlug($_POST['slug']);
			$surveyByID = $this->model->getSurveyByID($_POST['slug']);
			if (!empty($pollBySlug)) {
				$return['html'] = 'Slug taken by poll';
				$return['returncode'] = '0';
			} else if (!empty($pollByID)) {
				$return['html'] = 'Slug not available, matches existing poll ID';
				$return['returncode'] = '0';
			} else if (!empty($surveyBySlug)) {
				$return['html'] = 'Slug taken by survey';
				$return['returncode'] = '0';
			} else if (!empty($surveyByID)) {
				$return['html'] = 'Slug not available, matches existing survey ID';
				$return['returncode'] = '0';
			} else {
				$return['html'] = 'Slug available';
				$return['returncode'] = '1';
			}
		}
		echo json_encode($return);
	}
	
	public function ajaxinsertsurvey()
	{
		$this->verifiedVotingTypes = array('gkc', 'eml', 'gau');
		// Actually saves the survey
		if ($_POST['surveyTitle'] != "") {
			$this->surveyTitle = $_POST['surveyTitle'];
			// Save the survey, save the world
			// Generate ID
			$oUtility = new UtilityController();
			$surveyIDIsTaken = true;
			while ($surveyIDIsTaken) {
				$newSurveyID = $oUtility->generateRandomString($type = 'distinctlower', $length = 8);
				$surveyIDIsTaken = $this->model->isSurveyIDTaken($newSurveyID);
			}
			if (!empty($_POST['fsCustomSlug'])) {
				$return['customSlug'] = $_POST['fsCustomSlug'];
			}
			$return['surveyID'] = $newSurveyID;
			if ($this->user->userID > 0) {
				$userID = $this->user->userID;
			} else $userID = 0;
			// Cleanup type if needed
			if (!in_array($_POST['fsVerifiedVotingType'], $this->verifiedVotingTypes)) $_POST['fsVerifiedVotingType'] = 'gkc';
			$dt = new DateTime();
			// Insert actual
			$this->model->insertSurvey($newSurveyID, $this->surveyTitle, $dt->format('Y-m-d H:i:s'), $_POST['fsRandomOrder'], $_POST['fsPrivate'], $_SERVER['REMOTE_ADDR'], $_POST['fsCustomSlug'], $_POST['fsVerifiedVoting'], $_POST['fsVerifiedVotingType'], $userID, $_POST['fsVerbage']);
			unset($dt);
			$return['html'] .= 'Survey saved! Loading results...';
		} else {
			$return['error'] = 'Must provide a title';
		}
		echo json_encode($return);
	}
	
	public function ajaxcheckvoterkey()
	{
		$this->survey = $this->model->getSurveyByID($_POST['surveyID']);
		if (!empty($this->survey)) {
			if ($this->survey->verifiedVoting) {
				if (strlen($_POST['voterKey']) < 16) {
					$return['html'] = 'Key too short; must be 16 characters';
					$return['returncode'] = '0';
				} else if (strlen($_POST['voterKey']) > 16) {
					$return['html'] = 'Key too long; must be 16 characters';
					$return['returncode'] = '0';
				} else {
					$regexResult = preg_match('/^[a-z0-9]{16}$/', $_POST['voterKey']);
					if ($regexResult === 0 || $regexResult === false) {
						$return['html'] = 'Key will only contain a-z (lower case) and 0-9';
						$return['returncode'] = '0';
					} else {
						// Passes regex
						$voterKeyResult = $this->model->verifyVoterKey($_POST['voterKey'], $_POST['surveyID']);
						if (!empty($voterKeyResult->surveyID)) {
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
				}
			} else {
				$return['html'] = 'Survey valid, no key required';
				$return['returncode'] = '1';
			}
		} else {
			$return['html'] = 'Survey ID invalid';
			$return['returncode'] = '0';
		}
		
		echo json_encode($return);
	}
	
	public function ajaxvote()
	{
		// Initialize voter (will provide $this->voterID)
		$oVoter = new VoterController();
		$oVoter->model = new VoterModel();
		$oVoter->initVoter($_POST['voterID']);
		$this->surveyID = $_POST['surveyID'];
		$this->survey = $this->model->getSurveyByID($this->surveyID);
		$voterKeyResult = $this->model->verifyVoterKey($_POST['voterKey'], $this->surveyID);
		// Determine eligibility if necessary
		if (($this->survey->verifiedVoting && $voterKeyResult->surveyID) || $this->survey->verifiedVoting == false) {
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
				foreach ($this->survey->polls as $poll) {
					$existingVote = $oVoter->model->getYourVote($this->voterID, $poll->pollID);
					if ($existingVote->answerID != '') $yourVotes[$poll->pollID] = $existingVote;
				}
				if (empty($yourVotes)) {
					// No vote, get the answers to make sure we have a score for each
					$this->survey->allAnswers = $this->model->getAllAnswersBySurveyID($this->surveyID);
					foreach ($this->survey->allAnswers as $answer) {
						if (!array_key_exists($answer->answerID, $voteArray)) {
							$voteArray[$answer->answerID] = 0;
						}
					}
					$this->votesTogether = $voteArray; // DEBUG ONLY!!!
					$oDate = new DateTime();	
					$voteTime = $oDate->format("Y-m-d H:i:s");
					/*foreach ($voteArray as $answerID => $vote) {
						$this->votes[] = $vote;
						// Insert vote
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
					$this->model->incrementSurveyVoteCount($this->surveyID);
					// If a verified vote, write extra db info
					if ($this->survey->verifiedVoting) {
						$this->model->updateVoterKeyEntry($_POST['voterKey'], $this->surveyID, $this->voterID, $voteTime);
					}*/
					unset($voteTime, $oDate);
				} else {
					$return['caution'] = 'Your vote had already been recorded for this poll';
				}
				unset($yourVotes);
				$this->survey = $this->model->getSurveyByID($this->surveyID);
				$this->survey->polls = $this->model->getPollsBySurveyID($this->survey->surveyID);
				$mPoll = new PollModel();
				foreach ($this->survey->polls as $poll) {
					$poll->answers = $mPoll->getAnswersByPollID($poll->pollID);
				}
				unset($mPoll);
				foreach ($this->survey->polls as $poll) {
					$this->yourVotes[$poll->pollID] = $oVoter->model->getYourVote($this->voterID, $poll->pollID);
				}
				$return['html'] .= $this->ajaxInclude('view/survey/yourvote.view.php');
			} else {
				$return['caution'] = 'This key has already been used to record a vote on this poll';
			}
		} else {
			// Failed eligibility
			$return['error'] .= 'Invalid voter key';
		}
		echo json_encode($return);
	}
	
	/*public function ajaxrunoffmatrix()
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
	}*/
	
	/*public function csv()
	{
		$this->ajax = 1;
		$this->doHeader = 0;
		$this->doFooter = 0;
		$this->survey = $this->model->getSurveyByID($this->URLdata);
		if (!empty($this->survey)) {
			// Get polls
			$this->survey->polls;
			foreach ($this->survey->polls) {
				$this->poll->answers = $this->model->getAnswersByPollID($this->URLdata);
				$this->poll->ballots = $this->model->getBallotsByPollID($this->URLdata);
				// Process ballots into a single, cohesive array
				$this->poll->processedBallots = $this->processBallots($this->poll->ballots);
			}
		} else $this->error = 'Poll not found';
	}*/
	
	public function voterkeyscsv()
	{
		$this->ajax = 1;
		$this->doHeader = 0;
		$this->doFooter = 0;
		$this->survey = $this->model->getSurveyByID($this->URLdata); 
		if (!empty($this->survey->surveyID)) {
			$this->voterKeys = $this->model->getVoterKeysBysurveyID($this->survey->surveyID);
		} else $this->error = 'Poll not found';
	}
	
	/*public function ajaxcvr()
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
	}*/
	
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
