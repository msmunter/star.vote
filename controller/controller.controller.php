<?php
class Controller
{
	public $name;
	public $title;
	public $action;
	public $user;
	public $doHeader = true;
	public $doFooter = true;
	public $doPrintHeader = false;
	public $doPrintFooter = false;
	public $doJS = true;
	public $model;
	public $ajax = false;
	public $errors = array();
	public $adminLevel = 0;
	public $userHasRights = true;
	//public $staticServer;
	public $server;
	
	public function control()
	{
		// Use different servers for test and live
		$this->server = $_SERVER['SERVER_NAME'];
		/*if ($this->server == 'starvote.msmunter.com') {
			$this->staticServer = 'statictest.greenhealtheugene.com';
		} else {
			$this->staticServer = 'static.greenhealtheugene.com';
		}*/
		
		// Set timezone
		date_default_timezone_set('America/Los_Angeles');
		
		// Set monetary locale
		setlocale(LC_MONETARY, 'en_US');
		
		// Determine if AJAX output is requested
		if ($_POST['ajax'] != '' || $_GET['ajax'] != '') $this->ajax = true;
		
		// Include basic model in case it gets used via code or by autoloader
		include_once('model/model.php');
		// Include the model if there is one
		$modelFile = 'model/'.$this->name.'.model.php';
		if (file_exists($modelFile)) {
			include_once($modelFile);
			$modelName = ucfirst($this->name).'Model';
			$this->model = new $modelName;
		}
		
		// Init the user controller
		/*$this->user = new UserController;
		// Do as you are told or you will be subject to immediate deresolution
		$this->user->init();*/
		
		// Set view parameters
		if ($this->ajax) {
			$this->doHeader = false;
			$this->doFooter = false;
			$this->doJS = false;
		}
		
		// Execute the requested action if possible
		if (method_exists($this, $this->action)) {
			// Determine whether access is required and if user has it
			if ($this->adminLevel[$this->action] > 0) {
				// Admin access required, indicate necessity for rights
				$this->userHasRights = false;
				if ($this->user->info) {
					// User exists, check for admin level compatibility
					if ($this->user->info->admin_level > 0 && $this->user->info->admin_level <= $this->adminLevel) {
						// User has sufficient rights, load the controller
						// Have to set $action and call this way, $this->$this->action(); doesn't work
						$this->userHasRights = true;
						$action = $this->action;
						$this->$action();
					} else {
						$this->errors[] = 'ERROR: Insufficient admin rights.';
					}
				} else {
					$this->errors[] = 'ERROR: user must be <a href="/user/login/">logged in</a>.';
				}
			} else {
				// No admin level required, load the controller
				// Have to set $action and call this way, $this->$this->action(); doesn't work
				$action = $this->action;
				$this->$action();
			}
			// Execute view
			$this->viewActual(true);
		} else if ($this->action == 'index') {
			// Don't bother erroring out on index(), we may not have one and that's okay
			// Execute view
			$this->viewActual(true);
		} else {
			// Nonexistent method and not an index, error and skip full view
			$this->errors[] = 'ERROR: the requested action does not appear to exist.';
			// Execute view
			$this->viewActual(false);
		}
	}
	
	private function viewActual($methodExists)
	{
		// Header
		if ($this->doHeader) $this->doHeader();
		if ($this->doPrintHeader) $this->doPrintHeader();
		
		// Error messages
		if ($this->errors[0]) {
			foreach ($this->errors as $error) {
				echo $error.'<br />';
			}
		}
		// Errors have printed, empty the array
		$this->errors = array();
		
		// View
		if ($this->userHasRights) {
			if ($methodExists) {
				// User has rights and method exists, attempt to include view
				$viewFile = 'view/'.$this->name.'/'.$this->action.'.view.php';
				if (file_exists($viewFile)) {
					include_once($viewFile);
				} else {
					// Only give an error if not an ajax request, those may not have a view, which is okay
					if (!$this->ajax) $this->errors[] = 'ERROR: unable to include view.';
				}
			}
		} // else nothing since user lacks privileges or method nonexistent
		
		// Post-view error messages
		if ($this->errors[0]) {
			foreach ($this->errors as $error) {
				echo $error.'<br />';
			}
		}
		
		// Include JS if it exists
		if ($this->doJS) {
			if ($this->action != '') {
				$jsFile = 'web/js/'.$this->name.'/'.$this->action.'.js';
			} else {
				$jsFile = 'web/js/'.$this->name.'/'.$this->name.'.js';
			}
			if (file_exists($jsFile)) {
				echo '<script type="text/javascript">';
				include_once($jsFile);
				echo '</script>';
			}
		}
		
		// Footer
		if ($this->doFooter) $this->doFooter();
		if ($this->doPrintFooter) $this->doPrintFooter();
	}
	
	private function doHeader()
	{
		include_once('web/header.php');
	}
	
	private function doPrintHeader()
	{
		include_once('web/printheader.php');
	}
	
	private function doFooter()
	{
		include_once('web/footer.php');
	}
	
	private function doPrintFooter()
	{
		include_once('web/printfooter.php');
	}
	
	public function ajaxInclude($file)
	{
		if ($file) {
			ob_start();
			include_once($file);
			return ob_get_clean();
		}
	}
	
	public function debug($thing)
	{
		echo '<pre>';
		print_r($thing);
		echo '</pre>';
	}
}
?>