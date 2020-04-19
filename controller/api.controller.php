<?php
class ApiController extends Controller
{
	public $msgID;
	
	/* public function __construct()
	{
		
	} */

	public function v1()
	{
		$this->ajax = 1;
		$this->doHeader = 0;
		$this->doFooter = 0;
		$this->return['apiVersion'] = 'v1';
		if (strtolower($this->URLdata == 'getnextemail')) {
			$this->getNextEmail();
		} /*else if (strtolower($this->URLdata == 'getstarid')) {
			$this->getStarID();
		}*/ else {
			print json_encode($this->return);
		}
	}

	private function getNextEmail()
	{
		$nextEmail = $this->model->getNextEmail();
		if (!empty($nextEmail)) {
			$this->return['requestId'] = $nextEmail->msgID;
			$this->return['token'] = $nextEmail->token;
			$this->return['template'] = $nextEmail->template;
			$this->return['fields'] = $nextEmail->fields;
		} else {
			$this->return['requestId'] = false;
			$this->return['token'] = false;
		}
		echo json_encode($this->return);
	}

	public function addMsg()
	{
		if ($this->template && $this->fields) {
			if (!$this->model) $this->model = new ApiModel(); 
			$this->model->addMsg($this->template, $this->fields);
			return true;
		} else return false;
	}

	// private function getStarID()
	// {
	// 	$oSurvey = new SurveyController();
	// 	$oSurvey->model = new SurveyModel();
	// 	$res = $oSurvey->getstarid();
	// 	$this->return['starId'] = $res['starId'];
	// 	$this->return['status'] = $res['status'];
	// 	echo json_encode($this->return);
	// }
}
?>