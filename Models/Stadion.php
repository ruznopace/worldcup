<?php
namespace Worldcup\App\Models;

class Stadion extends Model
{	
	const TABLE = 'stadion';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = ['City' => ['id' => 'city_id']];
	const UNIQUE_KEYS = ['id'];
	public $id;
	public $city_id;
}
