<?php
namespace Worldcup\App\Models;

class PlayerPosition extends Model
{	
	const TABLE = 'player_position';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [];
	const UNIQUE_KEYS = ['id'];
	public $id;
}
