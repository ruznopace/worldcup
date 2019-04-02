<?php
namespace Worldcup\App\Controller;

interface ResultsController
{
	public function save_results(): Bool;
	public function get_results();
}