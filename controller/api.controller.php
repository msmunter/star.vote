<?php
class ApiController extends Controller
{
	public $msgID;
	
	/* public function __construct()
	{
		
	} */
	
	public function addEmail()
	{
		$this->model->addEmail();
	}

	public function getnextemail()
	{
		$this->ajax = 1;
		$this->doHeader = 0;
		$this->doFooter = 0;
		$nextEmail = $this->model->getNextEmail();
		if ($nextEmail) {
			$return['requestId'] = $nextEmail['msgID'];
			$return['token'] = $nextEmail['token'];
			$return['template'] = $nextEmail['template'];
			$return['fields'] = $nextEmail['fields'];
		} else {
			$return['requestId'] = false;
			$return['token'] = false;
		}
		echo json_encode($return);
	}	
}
?>