<?php
class UserController extends Controller
{
	// ------------------------------ Attributes ------------------------------
	
	public $userID;
	public $info;
	public $newUser;
	public $users;
	public $settings;
	public $userToUpdateID;
	// Admin Levels
	public $adminLevel = array(
		'insertuser' => 2,
		'index' => 2,
		'passadmin' => 1,
		'insertpassadmin' => 1,
		'details' => 2
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
		}
	}
	
	// View single user
	public function details() {
		// Get by ID
		if (is_numeric($_GET['d'])) {
			$this->detailUserID = $_GET['d'];
			$this->detailUser = $this->model->getUserInfoByID($this->detailUserID);
		}
		if ($this->detailUserID) {
			$this->title = 'User Details';
			//$this->detailPerson = $this->model->getPersonByUserID($this->detailUserID);
			/*$this->photoPath = '/srv/www/vhosts/'.$this->staticServer.'/images/org_logos/'.$this->detailUserID;
			$this->photoURL = 'http://'.$this->staticServer.'/images/org_logos/'.$this->detailUserID;
			if (file_exists($this->photoPath.'.jpg')) {
				$this->photoPath .= '.jpg';
				$this->photoURL .= '.jpg';
			} else {
				$this->photoPath .= '.png';
				$this->photoURL .= '.png';
			}*/
			// Get user's phone #s
			//$this->user->phones = $this->model->getPhonesByUserID($person->person_id) && $this->assemblePhoneNumbers($this->user->phones);
		}
	}
	
	/*public function dash()
	{
		$this->title = 'User Dashboard';
		$this->detailUser = $this->model->getUserInfoByID($this->user->userID);
		// Get user's org
		$this->detailUser->org = $this->model->getOrgByUserID($this->user->userID);
		// Get user's batches
		$mBatches = new BatchesModel();
		if ($_POST['batchSearchText']) {
			$this->detailUser->batches = $mBatches->getBatchesByOrgIDAndSearch($this->detailUser->org->org_id, $_POST['batchSearchText'], 0, 25);
		} else $this->detailUser->batches = $mBatches->getBatchesByOrgIDAndSearch($this->detailUser->org->org_id, null, 0, 25);
		unset($mBatches);
		$mOrders = new OrdersModel();
		//$this->detailUserOrders = $mOrders->getOrdersByUserID($this->user->userID);
		unset($mOrders);
	}*/
	
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
				// Add token to database and session
				$authToken = $this->createToken($this->userID, $seObject->format('U'));
				//$_SESSION['authToken'] = $authToken;
				// Also add token to the cookie
				if ($_POST['authLength'] > 0) {
					setcookie("authToken", $authToken, $seObject->format('U'), '/', '.'.$this->server);
					setcookie("authID", $this->userID, $seObject->format('U'), '/', '.'.$this->server);
				} else {
					setcookie("authToken", $authToken, 0, '/', '.greenhealtheugene.com');
					setcookie("authID", $this->userID, 0, '/', '.greenhealtheugene.com');
				}
				//echo '$_COOKIE: <pre>';print_r($_COOKIE);echo '</pre>'; // DEBUG ONLY!!!
				//echo '$_SESSION: <pre>';print_r($_SESSION);echo '</pre>'; // DEBUG ONLY!!!
				header('Location: /');
			} else {
				header('Location: /user/login/');
			}
		}
	}
	
	public function add()
	{
		$this->title = 'Add User';
	}
	
	public function index()
	{
		$this->title = 'Manage Users';
		$this->users = $this->model->getUsersAlphabetical($_POST['searchText'], 0, 50);
	}
	
	public function passadmin()
	{
		$this->title = 'Change Password';
		$this->userToUpdateID = $_GET['d'];
	}
	
	public function insertpassadmin()
	{
		if ($_POST['userToUpdateID'] && $_POST['pass1'] == $_POST['pass2']) {
			$pass = password_hash($_POST['pass1'], PASSWORD_DEFAULT);
			$this->model->insertPassAdmin($_POST['userToUpdateID'], $pass);
			header('Location: /user/');
		} else {
			$this->errors[] = 'Error: passwords do not match';
		}
	}
	
	public function insertuser()
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
	}
	
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
			$token = md5(uniqid(mt_rand(), true));
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
	
	public function doesemailexist($email)
	{
		$this->model->checkExistingEmail($email);
		if (count($this->results) > 0) {
			echo '1';
		} else echo '0';
	}
}
?>