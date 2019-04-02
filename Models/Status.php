<?php
namespace Worldcup\App\Models;

class Status extends Model
{	
	const TABLE = 'status';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [];
	const UNIQUE_KEYS = ['id'];
	public $id;
}
