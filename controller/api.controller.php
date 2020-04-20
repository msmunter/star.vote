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
		} else if (strtolower($this->URLdata == 'completeemail')) {
			$this->completeEmail();
		} else {
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

	private function completeEmail()
	{
		$msgID = $_GET['requestId'];
		if ($msgID) {
			$msg = $this->model->getMsgCompletedStatusByID($msgID);
			if ($msg->requestCompleted) {
				$this->return['requestId'] = $msgID;
				$this->return['template'] = $msg->template;
				$this->return['token'] = $msg->token;
			} else if ($msg->msgID) {
				$this->model->completeEmailByID($msgID);
				$this->return['requestId'] = $msgID;
				$this->return['template'] = $msg->template;
				$this->return['token'] = $msg->token;
			} else {
				$this->return['requestId'] = $msgID;
				$this->return['template'] = false;
				$this->return['token'] = false;
			}
		} else {
			$this->return['template'] = false;
			$this->return['token'] = false;
		}
		$this->return['status'] = 200;
		$this->return['statusText'] = "OK";
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
}
?>