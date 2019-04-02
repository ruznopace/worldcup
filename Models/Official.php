<?php
namespace Worldcup\App\Models;

class Official extends Model
{	
	const TABLE = 'official';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [];
	const UNIQUE_KEYS = ['id'];
	public $id;
}
