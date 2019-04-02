<?php
namespace Worldcup\App\Models\SqlBuilder;

use Worldcup\App\Db;

abstract class Builder
{
	protected $sql = '';
	protected $model_class = '';
	protected $fetch_mode;
	protected $models_associated = [];
	protected $bind_params = [];
	
	abstract public function get();
	
	/*****
	* Set model class
	* @param model_class: String
	*****/
	public function set_model_class(String $model_class)
	{
		$this->model_class = $model_class;
	}
	
	/*****
	* Set fetch_mode for setFetchMode
	* @param fetch_mode: String
	*****/
	public function set_fetch_mode($fetch_mode)
	{
		$this->fetch_mode = $fetch_mode;
	}
	
	/*****
	* Set associations with other models
	* @param models: Array
	*****/
	public function set_with(Array $models_associated)
	{
		$this->models_associated = $models_associated;
	}
	
	/*****
	* Helper for building SELECT * sql
	* @param rows Array
	* @return String
	*****/
	public function select(): Builder
	{ 
		$this->sql = "SELECT * FROM `". $this->model_class::TABLE .'` ';
		
		return $this;
	}
	
	/*****
	* Helper for building INSERT INTO sql
	* @param rows Array
	* @return String
	*****/
	public function insert(Array $rows): Builder
	{
		$snippets = ($this->is_multidimensional($rows) ? $this->multi_insert_snippets($rows) : $this->insert_snippets($rows));
		
		$this->sql = 'INSERT IGNORE INTO `'. $this->model_class::TABLE .'` ('. implode(', ', $snippets->quoted) .')';
		$this->sql .= ($this->is_multidimensional($rows) ? ' VALUES ' . implode(', ', $snippets->colon) : ' VALUE (' . implode(', ', $snippets->colon) .')');
		
		if ($snippets->quoted_value)
			$this->sql .= ' ON DUPLICATE KEY UPDATE ' . implode(', ', $snippets->quoted_value);
		
		$this->bind_params += $snippets->bind;
		
		return $this;
	}
	
	/*****
	* Build ORDER BY in sql
	* @param orders: Array col => ASC || DESC
	* @return String
	*****/
	public function order(Array $orders): Builder
	{
		foreach($orders as $col => $direction)
		{
			$arr[] = $col .' '. $direction;
		}
		
		if ($arr)
			$this->sql .= " ORDER BY " . implode(', ', $arr);
		
		return $this;
	}
	
	/*****
	* Build limit in sql
	* @param count: Integer
	* @param offset: Integer
	* @throws \Exception
	* @return String
	*****/
	public function limit(Int $count, Int $offset = 0): Builder
	{
		if ($count <= 0)
			throw new \Exception("Count must be >=1");
		
		$this->sql .= " LIMIT $offset, $count ";
		
		return $this;
	}
	
	/*****
	* Helper for building WHERE clausule in query witn AND
	* @param row Array
	* @return Builder
	*****/
	public function every(Array $row): Builder
	{
		return $this->where($row, 'AND');
	}
	
	/*****
	* Helper for building WHERE clausule in query with OR
	* @param row Array
	* @return Builder
	*****/
	public function any(Array $row): Builder
	{
		return $this->where($row, 'OR');
	}
	
	/*****
	* Helper for building WHERE clausule with  query
	* @param row Array
	* @return Builder
	*****/
	protected function where(Array $row, String $glue = 'AND'): Builder
	{
		$snippets = $this->where_snippets($row);
		
		$this->sql .= " WHERE ". implode(" $glue ", $snippets->quoted_colon) . ' ';

		$this->bind_params += $snippets->bind;
		
		return $this;
	}
	
	/*****
	* Build where snippets from array
	* @param row Array. Example: [id=>[1,2], name='example', 'city'=>['belgrade', 'moscow']]
	* @return Object. 
	* Return Example
	* {quoted_colon->['id = :id1', 'id = :id2', 'name = :name', 'city = :city1', 'city = :city2'], 
	* bind -> ['id' => ':id1', 'id' => ':id2', 'name' => ':name', 'city' => ':city1', 'city' => ':city2']}
	*****/
	protected function where_snippets(Array $row): Object
	{
		$quoted_colon = [];
		$bind = [];
		
		foreach($row as $col => $value)
		{
			if (is_array($value))
			{
				foreach($value as $k => $val)
				{
					$quoted_colon[] = "`$col` = :$col$k";
					$bind[":" . $col . $k] = $val;
				}
			}
			else
			{
				$quoted_colon[] = "`$col` = :$col";
				$bind[":$col"] = $value;
			}
		}
		
		return (object)[
			'quoted_colon' => $quoted_colon, 
			'bind' => $bind];
	}
	
	/*****
	* Build input snippets from array
	* @param row Array. Example [id=>1, name='example', 'city'=>'belgrade']
	* @return Object. 
	*****/
	protected function insert_snippets(Array $row): Object
	{
		$quoted = [];
		$colon = [];
		$quoted_colon = [];
		$quoted_value = [];
		$bind = [];

		foreach($row as $col => $val)
		{
			if (property_exists($this->model_class, $col))
			{
				$quoted[] = "`$col`";
				$quoted_colon[] = "`$col` = :$col";
				$colon[] = ":". $col;
				$bind[":". $col] = $val;
				
				if (!in_array($col, $this->model_class::UNIQUE_KEYS))
					$quoted_value[] = "`$col` = VALUES(`$col`)";
			}
		}
		
		return (object)[
			'quoted' => $quoted, 
			'colon' => $colon, 
			'quoted_value' => $quoted_value, 
			'bind' => $bind];
	}
	
	/*****
	* Build multi input snippets from array
	* @param rows Array. Example  [id=>1, name='example', 'city'=>'belgrade'], [id=>2, name='second example', 'city'=>'moscow']
	* @return Object.
	*****/
	protected function multi_insert_snippets(Array $rows): Object
	{
		$quoted = [];
		$colon = [];
		$quoted_colon = [];
		$quoted_value = [];
		$bind = [];
		
		foreach($rows as $index => $row)
		{
			$inner_colon = [];
			
			foreach($row as $col => $val)
			{	
				if (property_exists($this->model_class, $col))
				{
					if ($index === 0)
					{
						$quoted[] = "`$col`";
						$quoted_colon[] = "`$col` = :$col";
						
						if (!in_array($col, $this->model_class::UNIQUE_KEYS))
							$quoted_value[] = "`$col` = VALUES(`$col`)";
					}
					
					$inner_colon[] = ":". $col . $index;
					$bind[":". $col . $index] = $val;
				}
			}
			
			$colon[] = "(". implode(', ', $inner_colon) .")";
		}
		
		return (object)[
			'quoted' => $quoted, 
			'colon' => $colon, 
			'quoted_value' => $quoted_value, 
			'bind' => $bind];
	}
	
	/*****
	* Check is the array multidimensional or not
	* @param array
	* @return Bool
	*****/
	protected function is_multidimensional(Array $array): Bool
	{
		return count($array) !== count($array, COUNT_RECURSIVE);
	}
}
