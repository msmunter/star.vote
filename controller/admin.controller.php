<?php
class AdminController extends Controller
{
	// ------------------------------ Attributes ------------------------------
	public $userCount;
	public $users;
	public $admin;
	// Admin Levels
	public $adminLevel = array(
		'index' => 1
	);
	
	// ------------------------------- Methods --------------------------------
	
	public function index() {
		$this->title = 'Site/User Admin';
		$this->userCount = $this->model->getUserCount();
		$this->users = $this->model->getUsers(50);
	}
	
	public function passadmin()
	{
		$this->title = 'Change User Password';
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
	
	public function admincreateuser()
	{
		$this->title = 'Admin Create User';
	}
	
	public function ajaxadmincreateuser()
	{
		if ($_POST['pass1'] == $_POST['pass2']) {
			$this->newUser['firstName'] = $_POST['firstName'];
			$this->newUser['lastName'] = $_POST['lastName'];
			$this->newUser['email'] = $_POST['email'];
			$this->newUser['adminLevel'] = 0;
			$this->newUser['pass'] = password_hash($_POST['pass1'], PASSWORD_DEFAULT);
			$dateTime = new DateTime();
			$this->newUser['added'] = $dateTime->format('U');
			$userModel = new UserModel;
			$insertID = $userModel->addUser($this->newUser);
			unset($userModel);
			$return['newUserID'] = $insertID;
		} else $return['error'] = "Error: passwords do not match.";
		echo json_encode($return);
	}
	
	/* Private Methods */
	
	/*private function loadModel()
	{
		include_once('model/model.php');
		include_once('model/user.model.php');
		$this->model = new userModel;
	}*/
}
?>