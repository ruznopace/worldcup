<?php
namespace Worldcup\App\Models;

class Match extends Model
{	
	const TABLE = 'match';
	const PRIMARY_KEYS = ['id'];
	const FOREIGN_KEYS = [
		'Stadion' => ['id' => 'stadion_id'],
		'Status' => ['id' => 'status_id'],
		'Time' => ['id' => 'time_id'],
		'Stage' => ['id' => 'stage_id'],
		'Team' => ['id' => 'winner_team_id']];
	const UNIQUE_KEYS = ['id'];
	public $id;
	public $stadion_id;
	public $status_id;
	public $time_id;
	public $stage_id;
	public $winner_team_id;
	public $attendance;
	public $datetime;
	public $last_event_update_at;
	public $last_score_update_at;
}
