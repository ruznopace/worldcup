<?php
namespace  Worldcup\App\DataFetcher;

abstract class Fetcher
{
	protected $url = '';
	
	/*****
	*
	*****/
	abstract protected function convert_to_array($data): Array;
	
	/*****
	*
	*****/
	public function set_url(String $url)
	{
		$this->url = $url;
	}
	
	/*****
	*
	*****/
	public function get_data(): Array
	{
		return $this->fetch_data();
	}
	
	/*****
	*
	*****/
	protected function fetch_data(): Array
	{
		if (($raw_data = @file_get_contents($this->url)))
			try
			{
				return $this->convert_to_array($raw_data);
			}
			catch(\Exception $e)
			{
				die($e->getMessage());
			}
		else
			throw new \Exception('Can`t fetch the data.');
	}
}