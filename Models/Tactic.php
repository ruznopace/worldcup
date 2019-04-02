<?php
namespace Worldcup\App\Models;

class Tactic extends Model
{	
	const TABLE = 'tactic';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [];
	const UNIQUE_KEYS = ['id'];
	public $id;
}
