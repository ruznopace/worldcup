<?php
namespace Worldcup\App\Models;

class MatchTeamList extends Model
{	
	const TABLE = 'match_team_list';
	const PRIMARY_KEYS = [];
	const FOREIGN_KEYS = [
		'Match' => ['id' => 'match_id'],
		'Player' => ['id' => 'player_id'],
		'PlayerPosition' => ['id' => 'player_position_id']];
	const UNIQUE_KEYS = [];
	public $match_id;
	public $player_id;
	public $player_position_id;
	public $is_captain;
	public $is_starter;
}
