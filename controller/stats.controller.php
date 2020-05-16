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

	// public function starusage()
	// {
	// 	$this->ajax = 1;
	// 	$this->doHeader = 0;
	// 	$this->doFooter = 0;
	// 	if ($this->URLdata) {
	// 		if ($this->user->userID) {
	// 			$tempvotes = $this->model->getTempvotes($this->URLdata);
	// 			$this->return['voteCount'] = count($tempvotes);
	// 			foreach ($tempvotes as $vote) {
	// 				//echo '<pre>';print_r($vote);echo '</pre>'; // DEBUG ONLY!!!
	// 				foreach (json_decode($vote->voteJson) as $id => $starNumber) {
	// 					$this->return['votes'][$id][$starNumber] += 1;
	// 				}
	// 			}
	// 		} else {
	// 			$this->return['error'] = 'Not authorized';
	// 		}
	// 	} else {
	// 		$this->return['error'] = 'Invalid survey/election';
	// 	}
	// 	header('Content-Type: application/json');
	// 	echo json_encode($this->return);
	// }

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
			header('Content-Type: text/csv');
			$filename = 'star_cvr_'.date('Ymd-His').'.csv';
			header('Content-disposition: attachment;filename="'.$filename.'"');
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

	public function allvoters()
	{
		$this->ajax = 1;
		$this->doHeader = 0;
		$this->doFooter = 0;
		if ($this->user->userID == 1) {
			$this->userCanValidate = 2;
		} else {
			$mSurvey = new SurveyModel();
			$this->userCanValidate = $mSurvey->userCanValidate($this->user->userID, $this->URLdata);
		}
		if ($this->userCanValidate > 1) {
			$voters = $this->model->getAllVotersBySurveyID($this->URLdata);
			$output = "voterId, starId, firstName, lastName, email, phone, birthdate, regDate, voteTime, status\n";
			foreach ($voters as $voter) { 
				$tempvote = $this->model->getTempvoteByVoterID($this->URLdata, $voter->starID);
				$voter->voteTime = $tempvote->voteTime;
				$output .= $voter->voterID.', '.$voter->starID.', '.$voter->firstName.', '.$voter->lastName.', '.$voter->email.', '.$voter->phone.', '.$voter->birthdate.', '.$voter->regDate.', '.$voter->voteTime.', '.$voter->status."\n";
			}
		} else {
			$return['error'] = 'ERROR: Not authorized';
		}

		if ($output) {
			header('Content-Type: text/csv');
			$filename = 'staripo_allvoters_'.date('Ymd-His').'.csv';
			header('Content-disposition: attachment;filename="'.$filename.'"');
			$cvrHeader = 'Voter';
			echo $output;
		} else {
			header('Content-Type: application/json');
			echo json_encode($return);
		}
	}
}
?>