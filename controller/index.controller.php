<?php
class IndexController extends Controller
{
	public $memUsed;
	
	public function index()
	{
		$this->memUsed = memory_get_usage();
	}
}
?>