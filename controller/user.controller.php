<?php
class UserController extends Controller
{
	// ------------------------------ Attributes ------------------------------
	
	public $userID;
	public $info;
	public $userToUpdateID;
	// Admin Levels
	public $adminLevel = array(
		'insertuser' => 1,
		'passadmin' => 1,
		'insertpassadmin' => 1
	);
	
	// ------------------------------- Methods --------------------------------
	
	public function init()
	{
		// Check for existing token
		if ($_COOKIE['authToken'] && $_COOKIE['authID']) {
			// There's a cookie, check and populate the session
			$this->userID = $this->verifyToken($_COOKIE['authToken'], $_COOKIE['authID']);
		}
		// If a userID was found, populate info
		if ($this->userID) {
			$this->info = $this->model->getUserInfoByID($this->userID);
			$this->info->initials = strtoupper(substr($this->info->firstName, 0, 1)).' '.strtoupper(substr($this->info->lastName, 0, 1));
		}
	}
	
	public function index() {
		$this->title = 'Your Polls and Surveys';
		if ($this->user->userID > 0) {
			$this->pollCount = $this->model->getUserPollCount($this->user->userID);
			if ($this->pollCount > 0) {
				if ($this->pollCount > 20) $limit = 20;
				$this->polls = $this->model->getPollsByUserID($this->user->userID, 0, $limit);
			}
			$this->surveyCount = $this->model->getUserSurveyCount($this->user->userID);
			if ($this->surveyCount > 0) {
				if ($this->surveyCount > 20) $limit = 20;
				$this->surveys = $this->model->getSurveysByUserID($this->user->userID, 0, $limit);
			}
		}
	}
	
	public function login()
	{
		$this->title = 'User Login';
	}
	
	public function logout()
	{
		// Destroy tokens in DB
		if ($_COOKIE['authToken']) $this->model->destroyTokenByID($_COOKIE['authToken']);
		// Make a DateTime for 3600 sec in the past to delete the cookies
		$dateTime = new DateTime();
		$dateTime->modify("-3600 seconds");
		// Destroy cookies
		setcookie("authToken", "", $dateTime->format('U'), '/', '.'.$this->server);
		setcookie("authID", "", $dateTime->format('U'), '/', '.'.$this->server);
		// Send to home page
		header("Location: /");
	}
	
	public function ajaxlogin()
	{
		//echo '<pre>';print_r($_POST);echo '</pre>'; // DEBUG ONLY!!!
		if ($_POST['email'] && $_POST['pass']) {
			$this->userID = $this->model->verifyPassword($_POST['email'], $_POST['pass']);
			if ($this->userID) {
				// Get user info
				$this->info = $this->model->getUserInfoByID($this->userID);
				if ($_POST['authLength'] > 0) {
					$seObject = new DateTime();
					$seObject->modify("+".$_POST['authLength']." days");
				} else {
					$seObject = new DateTime();
					$seObject->modify("+24 hours");
				}
				// Add token to database (and formerly the session)
				$authToken = $this->createToken($this->userID, $seObject->format('U'));
				//$_SESSION['authToken'] = $authToken;
				// Also add token to the cookie
				if ($_POST['authLength'] > 0) {
					setcookie("authToken", $authToken, $seObject->format('U'), '/', '.'.$this->server);
					setcookie("authID", $this->userID, $seObject->format('U'), '/', '.'.$this->server);
				} else {
					setcookie("authToken", $authToken, 0, '/', '.'.$this->server);
					setcookie("authID", $this->userID, 0, '/', '.'.$this->server);
				}
				//echo '$_COOKIE: <pre>';print_r($_COOKIE);echo '</pre>'; // DEBUG ONLY!!!
				//echo '$_SESSION: <pre>';print_r($_SESSION);echo '</pre>'; // DEBUG ONLY!!!
				$return['html'] = 'Success';
			} else {
				$return['error'] = 'Invalid login or password';
			}
		}
		echo json_encode($return);
	}
	
	public function signup()
	{
		$this->title = 'Signup';
	}
	
	public function changepass()
	{
		$this->title = 'Change Password';
	}
	
	public function ajaxchangepass()
	{
		if ($this->user->userID < 1) {
			$return['error'] = 'User must be logged in';
		} else if ($_POST['pass1'] != $_POST['pass2']) {
			$return['error'] = 'Passwords to not match';
		} else {
			$userID = $this->model->verifyPassword($this->user->info->email, $_POST['currentPass']);
			if ($userID) {
				// Good so far, attempt to update
				$pass = password_hash($_POST['pass1'], PASSWORD_DEFAULT);
				$this->model->insertPassAdmin($this->user->userID, $pass);
				$return['html'] = 'Password changed';
			} else $return['error'] = 'Incorrect password';
		}
		echo json_encode($return);
	}
	
	/*public function insertuser()
	{
		// Parse incoming form (ajax/post)
		if (!empty($_POST['form'])) $_POST['form'] = parse_str($_POST['form'], $this->form);
		if ($this->form['pass1'] == $this->form['pass2']) {
			$this->newUser['firstName'] = $this->form['firstName'];
			$this->newUser['lastName'] = $this->form['lastName'];
			$this->newUser['email'] = $this->form['email'];
			$this->newUser['adminLevel'] = $this->form['adminLevel'];
			$this->newUser['pass'] = password_hash($this->form['pass1'], PASSWORD_DEFAULT);
			$dateTime = new DateTime();
			$this->newUser['added'] = $dateTime->format('U');
			$insertID = $this->model->addUser($this->newUser);
			if ($insertID > 0) echo $insertID;
		} else $this->errors[] = "Error: passwords do not match.";
	}*/
	
	/* Private Methods */
	
	private function loadModel()
	{
		include_once('model/model.php');
		include_once('model/user.model.php');
		$this->model = new userModel;
	}
	
	private function createToken($userID, $expires)
	{
		// Generate new tokens until one not taken is found
		$tokenExists = true;
		while ($tokenExists) {
			// Generate token
			$token = bin2hex(random_bytes(64));
			// Verify token
			$tokenExists = $this->verifyToken($token, true);
		}
		if (!$expires) $expires = strtotime('today +1 month');
		// Insert into database
		$this->model->insertToken($userID, $token, $expires);
		// Return token value
		return $token;
	}
	
	// Verifies user token, returns userID if valid
	private function verifyToken($token, $givenID)
	{
		// This gets called early in the framework, load the model if not already loaded.
		if (!$this->model) $this->loadModel();
		// Get user ID, verifying token is good if exists
		$userID = $this->model->getUserIDByToken($token);
		if ($userID == $givenID) {
			return $userID;
		} else return false;
	}
	
	private function doesemailexist($email)
	{
		$this->model->checkExistingEmail($email);
		if (count($this->results) > 0) {
			echo '1';
		} else echo '0';
	}
	
	private function passFailsReqs($pass)
	{
		if (strlen($pass) < 8) {
			return 'Password too short';
		} else if (strlen($pass) > 64) {
			return 'Password too long';
		} else return false;
	}
}
?>