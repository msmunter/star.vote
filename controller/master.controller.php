<?php
class MasterController
{
	public $controller;
	public $requestedAction;
	
	public function loadController()
	{
		// Establish a class autoloader (private function for added safety/nifty)
		spl_autoload_register('MasterController::classAutoloader');
		
		// Determine controller from POST/GET
		$requestedController = $this->prepGetPost('c');
		if ($requestedController == '') $requestedController = 'index';
		
		// Determine action from POST/GET
		$requestedAction = $this->prepGetPost('a');
		if ($requestedAction == '' ) $requestedAction = 'index';
		
		// Verify existence of the specific controller's file
		$controllerFile = 'controller/'.$requestedController.'.controller.php';
		if (file_exists($controllerFile)) {
			// No need to include due to autoloader
			
			// Create an instance of the controller
			$controllerName = ucfirst($requestedController).'Controller';
			$this->controller = new $controllerName;
			$this->controller->name = $requestedController;
			$this->controller->action = $requestedAction;
			$this->controller->URLdata = $this->prepGetPost('d');
			
			// Relinquish control to a specific controller; the MCP's job is done.
			$this->controller->control();
		} else {
			// Since it isn't a controller see if it's a pollID (safely)
			include_once('model/model.php');
			include_once('model/poll.model.php');
			$mPoll = new PollModel;
			$pollByID = $mPoll->getPollByID($requestedController);
			if (empty($pollByID)) {
				// Not an ID, check slug
				$pollBySlug = $mPoll->getPollByCustomSlug($requestedController);
			}
			// Both checked, load poll if exists
			if (!empty($pollByID)) {
				// Valid poll by ID, load poll controller
				$this->controller = new PollController;
				$this->controller->name = 'poll';
				$this->controller->action = 'results';
				$this->controller->URLdata = $requestedController;
				$this->controller->control();
			} else if (!empty($pollBySlug)) {
				// Valid poll by Slug, load poll controller
				$this->controller = new PollController;
				$this->controller->name = 'poll';
				$this->controller->action = 'results';
				$this->controller->URLdata = $pollBySlug->pollID;
				$this->controller->control();
			} else {
				// Not a valid controller, not a valid pollID
				$this->controller->errors[] =  'ERROR: the requested page does not appear to exist.';
				// Nothing else is going to run after this so we should output errors
				foreach ($this->controller->errors as $error) {
					echo $error.'<br />';
				}
			}
		}
	}
	
	private function prepGetPost($var)
	{
		if ($_POST[$var] != '') {
			$return = trim($_POST[$var]);
		} else if ($_GET[$var] != '') {
			$return = trim($_GET[$var]);
		}
		// Here we remove some of the more common attempts to 'hack' our site
		$return = str_replace(array('.', '/'), '', $return);
		// Send the round
		return $return;
	}
	
	private function classAutoloader($class)
	{
		// Determine whether controller or model
		if (strpos($class, 'Controller')) {
			$classPath = 'controller/'.strtolower(str_replace('Controller', '', $class)).'.controller.php';
		} else if (strpos($class, 'Model')) {
			$classPath = 'model/'.strtolower(str_replace('Model', '', $class)).'.model.php';
		}
		//echo '<script type="text/javascript">alert("'.$class.'");</script>'; // DEBUG ONLY!!!
		if (file_exists($classPath)) require_once($classPath);
	}
}
?>