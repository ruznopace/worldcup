<?php
namespace Worldcup\App\Models;

class Group extends Model
{	
	const TABLE = 'group';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [];
	const UNIQUE_KEYS = ['id', 'letter'];
	public $id;
	public $letter;
}
