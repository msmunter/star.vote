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
	
	/*public function ajaxloadmorepolls()
	{
		if (empty($_POST['index'])) {
			$index = 0;
		} else {
			$index = $_POST['index'];
		}
		$this->mostPopularPolls = $this->model->getMostPopularPolls($index, 10);
		$this->mostRecentPolls = $this->model->getMostRecentPolls($index, 10);
		$this->processPollSet($this->mostPopularPolls);
		$this->processPollSet($this->mostRecentPolls);
		$return['html'] = $this->ajaxInclude('view/poll/pollset.view.php');
		echo json_encode($return);
	}*/
	
	public function csv()
	{
		if ($this->user->info->admin_level == 1) {
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

	public function csvadvanced()
	{
		if ($this->user->info->admin_level == 1) {
			$this->ajax = 1;
			$this->doHeader = 0;
			$this->doFooter = 0;
			$this->poll = $this->model->getPollByID($this->URLdata);
			if (!empty($this->poll)) {
				$this->poll->answers = $this->model->getAnswersByPollID($this->URLdata);
				$this->poll->ballots = $this->model->getBallotsByPollID($this->URLdata);
				// Process ballots into a single, cohesive array
				$this->poll->processedBallots = $this->processExtendedBallots($this->poll->ballots);
			} else $this->error = 'Poll not found';
		}
	}

	private function processExtendedBallots($ballots)
	{
		foreach ($ballots as $index => $ballot) {
			if (empty($return[$ballot->voterID])) {
				$extendedInfo = $this->model->getExtendedVoterInfo($ballot->voterID);
				// New, establish a base and populate first vote
				$return[$ballot->voterID]['voteTime'] = $ballot->voteTime;
				$return[$ballot->voterID]['pollID'] = $ballot->pollID;
				$return[$ballot->voterID]['ip'] = $extendedInfo->ip;
				$return[$ballot->voterID]['browserInfo'] = $extendedInfo->browserInfo;
				$return[$ballot->voterID]['clientHost'] = $extendedInfo->clientHost;
				$return[$ballot->voterID]['fname'] = $extendedInfo->fname;
				$return[$ballot->voterID]['lname'] = $extendedInfo->lname;
				$return[$ballot->voterID]['email'] = $extendedInfo->email;
				$return[$ballot->voterID]['mailingList'] = $extendedInfo->mailingList;
				$return[$ballot->voterID]['votes'][$ballot->answerID] = $ballot->vote;
			} else {
				// Exists, populate
				$return[$ballot->voterID]['votes'][$ballot->answerID] = $ballot->vote;
			}
		}
		//echo '<pre>';print_r($return);echo '</pre><br />'; // DEBUG ONLY!!!
		return $return;
	}
}
?>
