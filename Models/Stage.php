<?php
namespace Worldcup\App\Models;

class Stage extends Model
{	
	const TABLE = 'stage';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [];
	const UNIQUE_KEYS = ['id'];
	public $id;
}
