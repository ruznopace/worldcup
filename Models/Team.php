<?php
namespace Worldcup\App\Models;

class Team extends Model
{	
	const TABLE = 'team';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = ['Group' => ['id' => 'group_id']];
	const UNIQUE_KEYS = ['id', 'country', 'fifa_code'];
	public $id;
	public $group_id;
	public $country;
	public $fifa_code;
}
