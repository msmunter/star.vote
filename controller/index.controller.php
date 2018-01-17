<?php
class IndexController extends Controller
{
	public $pollSet;
	
	public function index()
	{
		$oPoll = new PollController();
		$mPoll = new PollModel;
		$this->mostRecentPolls = $mPoll->getMostRecentPolls(10);
		$oPoll->processPollSet($this->mostRecentPolls);
		$this->pollSet = $this->mostRecentPolls;
	}
}
?>