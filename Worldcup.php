<?php
namespace Worldcup\App;

use Worldcup\App\Controller\ResultsController;

require_once __DIR__ .'/autoload.php';

class Worldcup
{
	protected $controller;
	
	public function __construct(ResultsController $controller)
	{
		$this->change_controller($controller);
	}
	
	public function change_controller(ResultsController $controller)
	{
		$this->controller = $controller;
	}
	
	public function save_results()
	{
		return $this->controller->save_results();
	}
	
	public function get_results()
	{
		return $this->controller->get_results();
	}
}
