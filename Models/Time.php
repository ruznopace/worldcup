<?php
namespace Worldcup\App\Models;

class Time extends Model
{	
	const TABLE = 'time';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [];
	const UNIQUE_KEYS = ['id'];
	public $id;
}
