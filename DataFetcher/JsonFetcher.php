<?php
namespace  Worldcup\App\DataFetcher;

class JsonFetcher Extends Fetcher
{	
	/*****
	*
	*****/
	protected function convert_to_array($data): Array
	{
		if ($data = json_decode($data, true))
			return $data;
		else
			throw new \Exception('Malformed data');
	}
}