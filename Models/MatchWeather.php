<?php
namespace Worldcup\App\Models;

class MatchWeather extends Model
{	
	const TABLE = 'match_weather';
	const PRIMARY_KEYS = ['match_id'];
	const FOREIGN_KEYS = [
		'Match' => ['id' => 'match_id'],
		'WeatherDescription' => ['id' => 'weather_description_id']];
	const UNIQUE_KEYS = ['match_id'];
	public $match_id;
	public $weather_description_id;
	public $humidity;
	public $temp_celsius;
	public $temp_farenheit;
	public $wind_speed;
}
