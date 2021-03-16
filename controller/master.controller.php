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
			include_once('model/survey.model.php');
			$mPoll = new PollModel;
			$mSurvey = new SurveyModel;
			$pollByID = $mPoll->getPollByID($requestedController);
			$validKey = false;
			if (empty($pollByID)) {
				// Not a poll ID, check poll slug
				$pollBySlug = $mPoll->getPollByCustomSlug($requestedController);
			}
			if (empty($pollBySlug)) {
				// Not a poll slug, check survey ID
				$surveyByID = $mSurvey->getSurveyByID($requestedController);
			}
			if (empty($surveyByID)) {
				// Not a survey ID, check survey slug
				$surveyBySlug = $mSurvey->getSurveyByCustomSlug($requestedController);
			}
			// Both checked, load poll if exists
			if ($pollByID || $pollBySlug || $surveyByID || $surveyBySlug) {
				if ($pollByID) {
					// Valid poll by ID, load poll controller
					$this->controller = new PollController;
					$this->controller->name = 'poll';
					$this->controller->action = 'results';
					$this->controller->URLdata = $requestedController;
					$this->controller->passedKey = $this->prepGetPost('k');
				} else if ($pollBySlug) {
					// Valid poll by Slug, load poll controller
					$this->controller = new PollController;
					$this->controller->name = 'poll';
					$this->controller->action = 'results';
					$this->controller->URLdata = $pollBySlug->pollID;
					$this->controller->passedKey = $this->prepGetPost('k');
				} else if ($surveyByID) {
					// Valid survey by ID, load survey controller
					$this->controller = new SurveyController;
					$this->controller->name = 'survey';
					$this->controller->action = 'results';
					$this->controller->URLdata = $requestedController;
					$this->controller->passedKey = $this->prepGetPost('k');
				} else if ($surveyBySlug) {
					// Valid survey by Slug, load survey controller
					$this->controller = new SurveyController;
					$this->controller->name = 'survey';
					$this->controller->action = 'results';
					$this->controller->URLdata = $surveyBySlug->surveyID;
					$this->controller->passedKey = $this->prepGetPost('k');
				}
				$this->controller->control();
			} else {
				// Not a valid controller, not a valid ID/Slug for a poll/survey
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
		// Little bit of protection from trying to jump around in the path
		$return = str_replace(array('.', '/'), '', $return);
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
		if (file_exists($classPath)) require_once($classPath);
	}
}
?>