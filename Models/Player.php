<?php
namespace Worldcup\App\Models;

class Player extends Model
{	
	const TABLE = 'player';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = ['Team' => ['id' => 'team_id']];
	const UNIQUE_KEYS = ['id'];
	public $id;
	public $team_id;
	public $shirt_number;
}
