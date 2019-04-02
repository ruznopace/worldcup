<?php
namespace Worldcup\App\Models\SqlBuilder;

use Worldcup\App\Db;

class FetchAll Extends Builder
{
	public function get(): Array
	{
		$sth = Db::get_instance()->prepare($this->sql);
		$sth->setFetchMode(\PDO::FETCH_CLASS, $this->fetch_mode);
		$sth->execute($this->bind_params);
		$models = $sth->fetchAll();

		if ($this->models_associated)
			$models = $this->associate($models);
		
		return $models;
	}
	
	/*****
	* Get all associated models and map them to their belonging models
	* @param models: Array
	* @return Array
	*****/
	protected function associate(Array $models): Array
	{
		$results = [];
		
		foreach($this->models_associated as $i => $class)
		{
			// With foreign keys values we will call WHERE conditions in their sql
			$key_pairs = $this->model_class::FOREIGN_KEYS[$class];
			$rows = [];
			
			foreach($models as $k => $model)
			{
				foreach((array)$key_pairs as $key => $foreign_key)
				{
					$rows[$key][] = $model->$foreign_key;
				}
			}
			
			// In one query fetch all associated models for each model
			${"builder". $i} = new self;
			${"builder". $i}->set_model_class('Worldcup\\App\\Models\\'. $class);
			${"builder". $i}->set_fetch_mode('Worldcup\\App\Models\\'. $class);
			
			$results[$class] = ${"builder". $i}->select()->any($rows)->get();

			unset(${"builder" . $i});
		}
		
		// Mapping associated models with their belonging model 
		return $this->map($models, $results);
	}
	
	/*****
	* Map associated models to their belonging models
	* @param models: Array
	* @param results: Array
	* @return Array
	*****/
	protected function map(Array $models, Array $results)
	{
		foreach($results as $class => $records)
		{
			foreach($records as $record)
			{
				foreach($models as $index => $model)
				{
					$map = true;
				
					foreach($model::FOREIGN_KEYS[$class] as $key => $foreign_key)
					{
						if ($record->$key != $model->$foreign_key)
							$map = false;
					}
					
					if ($map)
						$models[$index]->$class = $record;
				}
			}
		}
		
		return $models;
	}
}
