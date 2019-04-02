<?php
namespace Worldcup\App\Models;

class TypeOfEvent extends Model
{	
	const TABLE = 'type_of_event';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [];
	const UNIQUE_KEYS = ['id'];
	public $id;
}
