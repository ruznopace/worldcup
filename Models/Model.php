<?php
namespace Worldcup\App\Models;

use Worldcup\App\Models\SqlBuilder\Builder;
use Worldcup\App\Models\SqlBuilder\FetchAll;
use Worldcup\App\Models\SqlBuilder\Fetch;
use Worldcup\App\Models\SqlBuilder\Init;
use Worldcup\App\Models\SqlBuilder\Insert;

abstract class Model
{
	protected static $sql = '';
	
	/*****
	* Data population from DB
	* @ param row: Array
	* return Void
	*****/
	public function init(Array $row)
	{		
		$builder = new Init;
		$builder->set_model_class(static::class);
		$builder->set_fetch_mode($this);
		
		return $builder->select()->every($row)->get();
	}
	
	/*****
	* Data insertion into DB
	* @param rows: [] or [[]]
	* @return Bool
	*****/
	public static function insert(Array $rows): Bool
	{
		$builder = new Insert;
		$builder->set_model_class(static::class);
		
		return $builder->insert($rows)->get();
	}
	
	/*****
	* Select data for specified fields 
	* @param rows: Array
	* @return Array
	*****/
	public static function all(): Builder
	{
		$builder = new FetchAll;
		$builder->set_model_class(static::class);
		$builder->set_fetch_mode(static::class);
		$builder->select();
		
		return $builder;
	}
	
	/*****
	* Add associations to model for getting their data
	* @param models: Array of class names of the models
	*****/
	public static function with(Array $models): Builder
	{
		$builder = new FetchAll;
		$builder->set_model_class(static::class);
		$builder->set_fetch_mode(static::class);
		$builder->set_with(array_intersect($models, array_keys(static::FOREIGN_KEYS)));
		$builder->select();
		
		return $builder;
	}
}

