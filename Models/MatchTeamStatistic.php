<?php
namespace Worldcup\App\Models;

class MatchTeamStatistic extends Model
{	
	const TABLE = 'match_team_statistic';
	const PRIMARY_KEYS = [];
	const FOREIGN_KEYS = [
		'Match' => ['id' => 'match_id'],
		'Team' => ['id' => 'team_id'],
		'Tactic' => ['id' => 'tactic_id']];
	const UNIQUE_KEYS = [];
	public $match_id;
	public $team_id;
	public $tactic_id;
	public $is_home;
	public $goals;
	public $penalties;
	public $attempts_on_goal;
	public $on_target;
	public $off_target;
	public $blocked;
	public $woodwork;
	public $corners;
	public $offsides;
	public $ball_possession;
	public $pass_accuracy;
	public $num_passes;
	public $passes_completed;
	public $distance_covered;
	public $balls_recovered;
	public $tackles;
	public $clearances;
	public $yellow_cards;
	public $red_cards;
	public $fouls_committed;
}
