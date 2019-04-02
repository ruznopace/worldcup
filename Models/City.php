<?php
namespace Worldcup\App\Models;

class City extends Model
{	
	const TABLE = 'city';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [];
	const UNIQUE_KEYS = ['id'];
	public $id;
}
