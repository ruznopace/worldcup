<?php
namespace Worldcup\App\Models;

class MatchTeamEvent extends Model
{	
	const TABLE = 'match_team_event';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [
		'Match' => ['id' => 'match_id'],
		'Team' => ['id' => 'team_id'],
		'Player' => ['id' => 'player_id'],
		'TypeOfEvent' => ['id' => 'type_of_event_id']];
	const UNIQUE_KEYS = ['id'];
	public $id;
	public $match_id;
	public $team_id;
	public $player_id;
	public $type_of_event_id;
	public $time;
}
