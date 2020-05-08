<?php
class StatsController extends Controller
{
	//public $msgID;
	
	/* public function __construct()
	{
		
	} */

	public function index()
	{
		// $this->ajax = 1;
		// $this->doHeader = 0;
		// $this->doFooter = 0;
		// $this->return['apiVersion'] = 'v1';
		// if ($this->user->userID && $this->user->info->email == 'api') {
		// 	if (strtolower($this->URLdata == 'getnextemail')) {
		// 		$this->getNextEmail();
		// 	} else if (strtolower($this->URLdata == 'completeemail')) {
		// 		$this->completeEmail();
		// 	} else {
		// 		print json_encode($this->return);
		// 	}
		// } else {
		// 	$this->return['error'] = 'Not authorized';
		// 	echo json_encode($this->return);
		// }
		echo 'N/A';
	}

	public function starusage()
	{
		$this->ajax = 1;
		$this->doHeader = 0;
		$this->doFooter = 0;
		if ($this->URLdata) {
			if ($this->user->userID) {
				$tempvotes = $this->model->getTempvotes($this->URLdata);
				$this->return['voteCount'] = count($tempvotes);
				foreach ($tempvotes as $vote) {
					//echo '<pre>';print_r($vote);echo '</pre>'; // DEBUG ONLY!!!
					foreach (json_decode($vote->voteJson) as $id => $starNumber) {
						$this->return['votes'][$id][$starNumber] += 1;
					}
				}
			} else {
				$this->return['error'] = 'Not authorized';
			}
		} else {
			$this->return['error'] = 'Invalid survey/election';
		}
		header('Content-Type: application/json');
		echo json_encode($this->return);
	}

	public function anonymouscvr()
	{
		$this->ajax = 1;
		$this->doHeader = 0;
		$this->doFooter = 0;
		if ($this->URLdata) {
			if ($this->user->userID) {
				$cvr = $this->generateCvr($this->URLdata);
				$answers = $this->model->getSurveyAnswers($this->URLdata);
				ksort($answers);
			} else {
				$this->return['error'] = 'Not authorized';
			}
		} else {
			$this->return['error'] = 'Invalid survey/election';
		}
		if ($cvr) {
			header('Content-Type: text/plain');
			$cvrHeader = 'Voter';
			foreach ($answers as $answer) {
				$cvrHeader .= ','.$answer;
			}
			echo $cvrHeader . "\n" . $cvr;
		} else {
			header('Content-Type: application/json');
			echo json_encode($this->return);
		}
	}

	private function generateCvr($surveyID)
	{
		$tempvotes = $this->model->getTempvotes($this->URLdata);
		$i = 0;
		foreach ($tempvotes as $vote) {
			$i++;
			$cvr[$i] = json_decode($vote->voteJson, true);
			if ($cvr[$i]) {
				ksort($cvr[$i]);
				$output .= 'voter'.$i;
				foreach ($cvr[$i] as $id => $vote) {
					$output .= ",$vote";
				}
				$output .= "\n";
			}
		}
		return $output;
	}

	// private function getNextEmail()
	// {
	// 	$nextEmail = $this->model->getNextEmail();
	// 	if (!empty($nextEmail)) {
	// 		$this->return['requestId'] = $nextEmail->msgID;
	// 		$this->return['token'] = $nextEmail->token;
	// 		$this->return['template'] = $nextEmail->template;
	// 		$this->return['fields'] = json_decode($nextEmail->fields);
	// 	} else {
	// 		$this->return['requestId'] = false;
	// 		$this->return['token'] = false;
	// 	}
	// 	echo json_encode($this->return);
	// }

	// private function completeEmail()
	// {
	// 	$msgID = $_GET['requestId'];
	// 	if ($msgID) {
	// 		$msg = $this->model->getMsgCompletedStatusByID($msgID);
	// 		if ($msg->requestCompleted) {
	// 			$this->return['requestId'] = $msgID;
	// 			$this->return['template'] = $msg->template;
	// 			$this->return['token'] = $msg->token;
	// 		} else if ($msg->msgID) {
	// 			$this->model->completeEmailByID($msgID);
	// 			$this->return['requestId'] = $msgID;
	// 			$this->return['template'] = $msg->template;
	// 			$this->return['token'] = $msg->token;
	// 		} else {
	// 			$this->return['requestId'] = $msgID;
	// 			$this->return['template'] = false;
	// 			$this->return['token'] = false;
	// 		}
	// 	} else {
	// 		$this->return['template'] = false;
	// 		$this->return['token'] = false;
	// 	}
	// 	$this->return['status'] = 200;
	// 	$this->return['statusText'] = "OK";
	// 	echo json_encode($this->return);
	// }

	// public function addMsg()
	// {
	// 	if ($this->template && $this->fields) {
	// 		if (!$this->model) $this->model = new ApiModel(); 
	// 		$this->model->addMsg($this->template, json_encode($this->fields));
	// 		return true;
	// 	} else return false;
	// }
}
?>