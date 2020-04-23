<?php
class IndexController extends Controller
{
	public $survey;
	public $userCanValidate;
	
	public function index()
	{
		$mSurvey = new SurveyModel();
		$this->survey = $mSurvey->getSurveyByCustomSlug('2020primary');
		$this->userCanValidate = $mSurvey->userCanValidate($this->user->userID, $this->survey->surveyID);
		unset($mSurvey);
	}
}
?>