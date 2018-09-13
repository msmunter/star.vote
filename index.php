<?php
if ($_SERVER['SERVER_NAME'] == 'test.star.vote') {
	// Only display errors on the test server
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('display_errors', '1');
	// Also restrict access on the test server
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$userIP = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$userIP = $_SERVER['REMOTE_ADDR'];
	}
	$testOkayIPs = array('67.189.2.252', '50.137.133.184', '67.170.165.87');
	// Fine to view locally
	if (stristr($userIP, '192.168.') === FALSE) {
		if (!in_array($userIP, $testOkayIPs)) {
			echo 'Access is restricted for '.$userIP.'.';
			exit();
		}
	}
}
// END ERROR DISPLAY SETTINGS
require_once('controller/master.controller.php');
require_once('controller/controller.controller.php');
$mcp = new MasterController;
$mcp->loadController();
?>