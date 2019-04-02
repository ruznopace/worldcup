<?php
namespace  Worldcup\App\Controller\Results;

use  Worldcup\App\Models;

class TeamsSummary extends Controller
{	
	public function get_results()
	{
		$results = [];
		
		// Get summary statistics for teams
		foreach(Models\MatchTeamStatistic::all()->get() as $statistics)
		{
			if (!isset($teams[$statistics->team_id]))
			{
				$teams[$statistics->team_id]['goals_for'] = 0;
				$teams[$statistics->team_id]['goals_against'] = 0;
				$teams[$statistics->team_id]['wins'] = 0;
				$teams[$statistics->team_id]['losses'] = 0;
				$teams[$statistics->team_id]['draws'] = 0;
				$teams[$statistics->team_id]['games_played'] = 0;
			}
				
			// Goals statistics
			$goals[$statistics->team_id][$statistics->match_id] = $statistics->goals;
			
			// Number of matches played
			$teams[$statistics->team_id]['games_played']++;
		}
		
		foreach($teams as $team_id => $statistics)
		{
			$team = Models\Team::with(['Group'])->every(['id' => $team_id])->get()[0];
			$scores = $this->scores($goals, $team_id);
			
			/****
			* Populate results in appropriate format
			****/
			$results[] = [
				'id' => (int)$team->id,
				'country' => $team->country,
				'alternate_name' => $team->alternate_name,
				'fifa_code' => $team->fifa_code,
				'group_id' => (int)$team->group_id,
				'group_letter' => $team->Group->letter,
				'wins' => $scores->wins,
				'draws' => $scores->draws,
				'losses' => $scores->losses,
				'games_played' => (int)$statistics['games_played'],
				'points' => $scores->points,
				'goals_for' => $scores->goals_for,
				'goals_against' => $scores->goals_against,
				'goal_differential' => $scores->goal_differential];
		}
		
		return json_encode($results);
	}
	
	/***
	* Get scores for the team
	* param goals Array goals for all teams in all matches
	* return Object {goals_for, goals_against, goal_differential, wins, losses, draws, points}
	***/
	protected function scores(Array $goals, $team_id)
	{
		// Goals statistic
		$goals_for = 0;
		$goals_against = 0;
		
		// Game statistics
		$wins = 0;
		$losses = 0;
		$draws = 0;
			
		// Take all scores of the team
		foreach($goals[$team_id] as $match_id1 => $goals1)
		{
			// Compare with scores, matches they played against opponent
			foreach($goals as $team_scored_id => $matches)
			{
				foreach($matches as $match_id2 => $goals2)
				{
					// Go down if the team played this match
					if ($match_id1 == $match_id2)
					{
						// See do they gave that goals or took
						if ($team_id == $team_scored_id)
						{
							$goals_for += $goals2;
						}
						else
						{
							$goals_against += $goals2;
							
							if ($goals1 > $goals2)
								$wins++;
							else if ($goals1 < $goals2)
								$losses++;
							else
								$draws++;
						}
					}
				}
			}
		}
		
		return (object)[
			'goals_for' => $goals_for,
			'goals_against' => $goals_against,
			'goal_differential' => $goals_for - $goals_against,
			'wins' => $wins,
			'losses' => $losses,
			'draws' => $draws,
			'points' => $wins*3 + $draws];
	}
}