<?php
class IndexController extends Controller
{
	public $pollSet;
	
	public function index()
	{
		$oPoll = new PollController();
		$mPoll = new PollModel;
		$this->mostRecentPolls = $mPoll->getMostRecentPolls(0, 10);
		$oPoll->processPollSet($this->mostRecentPolls);
		$this->pollSet = $this->mostRecentPolls;
		$this->mostPopularPolls = $mPoll->getMostPopularPolls(0, 10);
		unset($oPoll, $mPoll);
		/*$oSurvey = new SurveyController();
		$mSurvey = new SurveyModel();
		$this->mostRecentSurveys = $mSurvey->getMostRecentSurveys(0, 10);
		$this->mostPopularSurveys = $mSurvey->getMostRecentPolls(0, 10);*/
	}
}
?>