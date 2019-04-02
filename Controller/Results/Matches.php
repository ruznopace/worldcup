<?php
namespace  Worldcup\App\Controller\Results;

use  Worldcup\App\Models;

class Matches extends Controller
{	
	public function get_results()
	{
		$results = [];
		
		// Get all records for the Weather and sort it in ascedentaly order for temperature
		foreach(Models\MatchWeather::with(['WeatherDescription'])->order(['temp_celsius' => 'DESC'])->get() as $match_weather)
		{
			// Get matches for the sorted weather
			$match = Models\Match::with(['Stadion', 'Team'])->every(['id' => $match_weather->match_id])->get()[0];
			
			// Get officials for the match
			$officials = array_map(function($match_official){
				return $match_official->Official->id;
			}, Models\MatchOfficial::with(['Official'])->every(['match_id' => $match->id])->get());
			
			// Get statistics for the home team
			$home_team_statistics = Models\MatchTeamStatistic::with(['Team'])->every(['match_id' => $match->id, 'is_home' => 1])->get()[0];
			
			// Away team statistics
			$away_team_statistics = Models\MatchTeamStatistic::with(['Team'])->every(['match_id' => $match->id, 'is_home' => 0])->get()[0];
			
			// Starting eleven and substitute for the home and away teams
			// team_list[team_id][is_substitution][colName] = dataValue
			$team_list = [];
			
			foreach(Models\MatchTeamList::with(['Player'])->every(['match_id' => $match->id])->get() as $match_team_list)
			{
				$team_list[$match_team_list->Player->team_id][$match_team_list->is_starter][] = [
					'name' => $match_team_list->player_id,
					'captain' => $match_team_list->is_captain,
					'shirt_number' => $match_team_list->Player->shirt_number,
					'position' => $match_team_list->player_position_id];
			}
			
			// Get events for the home team
			$home_team_events = [];
			
			foreach(Models\MatchTeamEvent::all()->every(['match_id' => $match->id, 'team_id' => $home_team_statistics->team_id])->get() as $event)
			{
				$home_team_events[] = [
					'id' => (int)$event->id,
					'type_of_event' =>$event->type_of_event_id,
					'player' => $event->player_id,
					'time' => $event->time];
			}
			
			// Get events for the away team
			$away_team_events = [];
			
			foreach(Models\MatchTeamEvent::all()->every(['match_id' => $match->id, 'team_id' => $away_team_statistics->team_id])->get() as $event)
			{
				$away_team_events[] = [
					'id' => (int)$event->id,
					'type_of_event' =>$event->type_of_event_id,
					'player' => $event->player_id,
					'time' => $event->time];
			}
			
			/****
			* Populate results in appropriate format
			****/
			$results[] = [
				'venue' => $match->Stadion->city_id,
				'location' => $match->stadion_id,
				'status' => $match->status_id,
				'time' => $match->time_id,
				'fifa_id' => $match->id,
				
				'weather' => [
					'humidity' => $match_weather->humidity,
					'temp_celsius' => $match_weather->temp_celsius,
					'temp_farenheit' => $match_weather->temp_farenheit,
					'wind_speed' => $match_weather->wind_speed,
					'description' => $match_weather->WeatherDescription->id],
					
				'attendance' => $match->attendance,
				'officials' => $officials,
				'stage_name' => $match->stage_id,
				'home_team_country' => $home_team_statistics->Team->country,
				'away_team_country' => $away_team_statistics->Team->country,
				'datetime' => $match->datetime,
				'winner' => (isset($match->Team->country) ? $match->Team->country : 'Draw'),
				'winner_code' => (isset($match->Team->fifa_code) ? $match->Team->fifa_code : 'Draw'),
				
				'home_team' => [
					'country' => $home_team_statistics->Team->country,
					'code' => $home_team_statistics->Team->fifa_code,
					'goals' => (int)$home_team_statistics->goals,
					'penalties' => (int)$home_team_statistics->penalties],
					
				'away_team' => [
					'country' => $away_team_statistics->Team->country,
					'code' => $away_team_statistics->Team->fifa_code,
					'goals' => (int)$away_team_statistics->goals,
					'penalties' => (int)$away_team_statistics->penalties],
					
				'home_team_events' => $home_team_events,
				'away_team_events' => $away_team_events,
				
				'home_team_statistics' => [
					'country' => $home_team_statistics->Team->country,
					'attempts_on_goal' => (int)$home_team_statistics->attempts_on_goal,
					'on_target' => (int)$home_team_statistics->on_target,
					'off_target' => (int)$home_team_statistics->off_target,
					'blocked' => (int)$home_team_statistics->blocked,
					'woodwork' => (int)$home_team_statistics->woodwork,
					'corners' => (int)$home_team_statistics->corners,
					'offsides' => (int)$home_team_statistics->offsides,
					'ball_possession' => (int)$home_team_statistics->ball_possession,
					'pass_accuracy' => (int)$home_team_statistics->pass_accuracy,
					'num_passes' => (int)$home_team_statistics->num_passes,
					'passes_completed' => (int)$home_team_statistics->passes_completed,
					'distance_covered' => (int)$home_team_statistics->distance_covered,
					'balls_recovered' => (int)$home_team_statistics->balls_recovered,
					'tackles' => (int)$home_team_statistics->tackles,
					'clearances' => (int)$home_team_statistics->clearances,
					'yellow_cards' => (int)$home_team_statistics->yellow_cards,
					'red_cards' => (int)$home_team_statistics->red_cards,
					'fouls_committed' => (int)$home_team_statistics->fouls_committed,
					'tactics' => $home_team_statistics->tactic_id,
					'starting_eleven' => $team_list[$home_team_statistics->Team->id][1],
					'substitutes' => $team_list[$home_team_statistics->Team->id][0]],
					
				'away_team_statistics' => [
					'country' => $away_team_statistics->Team->country,
					'attempts_on_goal' => (int)$away_team_statistics->attempts_on_goal,
					'on_target' => (int)$away_team_statistics->on_target,
					'off_target' => (int)$away_team_statistics->off_target,
					'blocked' => (int)$away_team_statistics->blocked,
					'woodwork' => (int)$away_team_statistics->woodwork,
					'corners' => (int)$away_team_statistics->corners,
					'offsides' => (int)$away_team_statistics->offsides,
					'ball_possession' => (int)$away_team_statistics->ball_possession,
					'pass_accuracy' => (int)$away_team_statistics->pass_accuracy,
					'num_passes' => (int)$away_team_statistics->num_passes,
					'passes_completed' => (int)$away_team_statistics->passes_completed,
					'distance_covered' => (int)$away_team_statistics->distance_covered,
					'balls_recovered' => (int)$away_team_statistics->balls_recovered,
					'tackles' => (int)$away_team_statistics->tackles,
					'clearances' => (int)$away_team_statistics->clearances,
					'yellow_cards' => (int)$away_team_statistics->yellow_cards,
					'red_cards' => (int)$away_team_statistics->red_cards,
					'fouls_committed' => (int)$away_team_statistics->fouls_committed,
					'tactics' => $away_team_statistics->tactic_id,
					'starting_eleven' => $team_list[$away_team_statistics->Team->id][1],
					'substitutes' => $team_list[$away_team_statistics->Team->id][0]],
					
					'last_event_update_at' => $match->last_event_update_at,
					'last_score_update_at' => $match->last_score_update_at];
		}

		return json_encode($results);
	}
}