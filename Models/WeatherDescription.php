<?php
namespace Worldcup\App\Models;

class WeatherDescription extends Model
{	
	const TABLE = 'weather_description';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [];
	const UNIQUE_KEYS = ['id'];
	public $id;
}
