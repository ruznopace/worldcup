<?php
namespace  Worldcup\App\Controller\Results;

use Worldcup\App\Db;
use Worldcup\App\Models;
use Worldcup\App\DataFetcher;
use Worldcup\App\Controller\ResultsController;

abstract class Controller implements ResultsController
{
	protected const TEAMS_URL = 'http://worldcup.sfg.io/teams/';
	protected const MATCHES_URL = 'http://worldcup.sfg.io/matches';
	
	/*****
	* Save data into DB
	*****/
	public function save_results(): Bool
	{
		try
		{
			// Create fetcher to collect and parse data from URL
			// We can change type conversion, implementing another Fetcher in extended controller
			$fetcher =  new DataFetcher\JsonFetcher;
			
			// Set src url
			$data = $fetcher->set_url(self::TEAMS_URL);
			
			// Fetch and parse data from the external service
			$data = $fetcher->get_data();
			
			// Populate data for multi insertions from external source for Groups and Teams 
			foreach($data as $records)
			{
				$group_inserts[] = [
					'id' => $records['group_id'],
					'letter' => $records['group_letter']];
					
				$team_inserts[] = [
					'id' => $records['id'],	
					'country' => $records['country'],
					'alternate_name' => $records['alternate_name'],
					'fifa_code' => $records['fifa_code'],
					'group_id' => $records['group_id']];
			}
			
			// Set src url for MATCHES
			$data = $fetcher->set_url(self::MATCHES_URL);
			
			// Fetch and parse data from the external service
			$data = $fetcher->get_data();
			
			foreach($data as $records)
			{
				$city_inserts[] = ['id' => $records['venue']];
					
				$stadion_inserts[] = [
					'id' => $records['location'],	
					'city_id' => $records['venue']];
					
				$status_inserts[] = ['id' => $records['status']];
					
				$stage_inserts[] = ['id' => $records['stage_name']];
					
				$tactic_inserts[] = ['id' => $records['home_team_statistics']['tactics']];
				$tactic_inserts[] = ['id' => $records['away_team_statistics']['tactics']];
	
				$time_inserts[] = ['id' => $records['time']];
					
				$weather_description_inserts[] = ['id' => $records['weather']['description']];

				$match_inserts[] = [
					'id' => $records['fifa_id'],
					'stadion_id' => $records['location'],
					'status_id' => $records['status'],
					'stage_id' => $records['stage_name'],
					'time_id' => $records['time'],	
					'attendance' => $records['attendance'],
					'datetime' => $records['datetime'],
					'last_event_update_at' => $records['last_event_update_at'],
					'last_score_update_at' => $records['last_score_update_at'],
					'winner_team_id' => $this->get_primary_key_value($team_inserts, 'id', 'fifa_code', $records['winner_code'])];

				$match_weather_inserts[] = [
					'match_id' => $records['fifa_id'],
					'weather_description_id' => $records['weather']['description'],
					'humidity' => $records['weather']['humidity'],
					'temp_celsius' => $records['weather']['temp_celsius'],
					'temp_farenheit' => $records['weather']['temp_farenheit'],
					'wind_speed' => $records['weather']['wind_speed']];

				foreach($records['officials'] as $name)
				{
					$official_inserts[] = ['id' => $name];
						
					$match_official_inserts[] = [
						'official_id' => $name,	
						'match_id' => $records['fifa_id']];
				}

				// Home team statistics
				$match_team_statistic_inserts[] = [
					'match_id' => $records['fifa_id'],
					'team_id' => $this->get_primary_key_value($team_inserts, 'id', 'country', $records['home_team_country']), 
					'tactic_id' => $records['home_team_statistics']['tactics'],
					'is_home' => 1,
					'goals' => $records['home_team']['goals'],
					'penalties' => $records['home_team']['penalties'],
					'attempts_on_goal' => $records['home_team_statistics']['attempts_on_goal'],
					'on_target' => $records['home_team_statistics']['on_target'],
					'off_target' => $records['home_team_statistics']['off_target'],
					'blocked' => $records['home_team_statistics']['blocked'],
					'woodwork' => $records['home_team_statistics']['woodwork'],
					'corners' => $records['home_team_statistics']['corners'],
					'offsides' => $records['home_team_statistics']['offsides'],
					'ball_possession' => $records['home_team_statistics']['ball_possession'],
					'pass_accuracy' => $records['home_team_statistics']['pass_accuracy'],
					'num_passes' => $records['home_team_statistics']['num_passes'],
					'passes_completed' => $records['home_team_statistics']['passes_completed'],
					'distance_covered' => $records['home_team_statistics']['distance_covered'],
					'balls_recovered' => $records['home_team_statistics']['balls_recovered'],
					'tackles' => $records['home_team_statistics']['tackles'],
					'clearances' => $records['home_team_statistics']['clearances'],
					'yellow_cards' => $records['home_team_statistics']['yellow_cards'],
					'red_cards' => $records['home_team_statistics']['red_cards'],
					'fouls_committed' => $records['home_team_statistics']['fouls_committed']];
					
				//Away team statistics
				$match_team_statistic_inserts[] = [
					'match_id' => $records['fifa_id'],
					'team_id' => $this->get_primary_key_value($team_inserts, 'id', 'country', $records['away_team_country']),
					'tactic_id' => $records['away_team_statistics']['tactics'],
					'is_home' => 0,
					'goals' => $records['away_team']['goals'],
					'penalties' => $records['away_team']['penalties'],
					'attempts_on_goal' => $records['away_team_statistics']['attempts_on_goal'],
					'on_target' => $records['away_team_statistics']['on_target'],
					'off_target' => $records['away_team_statistics']['off_target'],
					'blocked' => $records['away_team_statistics']['blocked'],
					'woodwork' => $records['away_team_statistics']['woodwork'],
					'corners' => $records['away_team_statistics']['corners'],
					'offsides' => $records['away_team_statistics']['offsides'],
					'ball_possession' => $records['away_team_statistics']['ball_possession'],
					'pass_accuracy' => $records['away_team_statistics']['pass_accuracy'],
					'num_passes' => $records['away_team_statistics']['num_passes'],
					'passes_completed' => $records['away_team_statistics']['passes_completed'],
					'distance_covered' => $records['away_team_statistics']['distance_covered'],
					'balls_recovered' => $records['away_team_statistics']['balls_recovered'],
					'tackles' => $records['away_team_statistics']['tackles'],
					'clearances' => $records['away_team_statistics']['clearances'],
					'yellow_cards' => $records['away_team_statistics']['yellow_cards'],
					'red_cards' => $records['away_team_statistics']['red_cards'],
					'fouls_committed' => $records['away_team_statistics']['fouls_committed']];
				/***
				* Players // Player positions // match team list
				***/
				
				// Home
				foreach($records['home_team_statistics']['starting_eleven'] as $record)
				{
					$player_inserts[] = [
						'id' => $record['name'],				// PRIMARY KEY
						'team_id' => $this->get_primary_key_value($team_inserts, 'id', 'country', $records['home_team_country']),
						'shirt_number' => $record['shirt_number']];
						
					$player_position_inserts[] = [
						'id' => $record['position']];			// PRIMARY KEY
						
					$match_team_list_inserts[] = [
						'match_id' => $records['fifa_id'],			// PRIMARY KEY1  TOGGETHER
						'player_id' => $record['name'],				// PRIMARY KEY 2 TOGGETHER
						'player_position_id' => $record['position'],
						'is_captain' => $record['captain'],
						'is_starter' => 1];
				}
				
				foreach($records['home_team_statistics']['substitutes'] as $record)
				{
					$player_inserts[] = [
						'id' => $record['name'],				// PRIMARY KEY
						'team_id' => $this->get_primary_key_value($team_inserts, 'id', 'country', $records['home_team_country']),
						'shirt_number' => $record['shirt_number']];
						
					$player_position_inserts[] = [
						'id' => $record['position']];				// PRIMARY KEY
						
					$match_team_list_inserts[] = [
						'match_id' => $records['fifa_id'],			// PRIMARY KEY1 TOGGETHER
						'player_id' => $record['name'],				// PRIMARY KEY 2 TOGGETHER
						'player_position_id' => $record['position'],
						'is_captain' => $record['captain'],
						'is_starter' => 0];
				}
				
				// Away
				foreach($records['away_team_statistics']['starting_eleven'] as $record)
				{
					$player_inserts[] = [
						'id' => $record['name'],				// PRIMARY KEY
						'team_id' => $this->get_primary_key_value($team_inserts, 'id', 'country', $records['away_team_country']),
						'shirt_number' => $record['shirt_number']];
						
					$player_position_inserts[] = [
						'id' => $record['position']];			// PRIMARY KEY
						
					$match_team_list_inserts[] = [
						'match_id' => $records['fifa_id'],			// PRIMARY KEY1 TOGGETHER
						'player_id' => $record['name'],				// PRIMARY KEY 2 TOGGETHER
						'player_position_id' => $record['position'],
						'is_captain' => $record['captain'],
						'is_starter' => 1];
				}
				
				foreach($records['away_team_statistics']['substitutes'] as $record)
				{
					$player_inserts[] = [
						'id' => $record['name'],				// PRIMARY KEY
						'team_id' => $this->get_primary_key_value($team_inserts, 'id', 'country', $records['away_team_country']),
						'shirt_number' => $record['shirt_number']];
						
					$player_position_inserts[] = [
						'id' => $record['position']];			// PRIMARY KEY
						
					$match_team_list_inserts[] = [
						'match_id' => $records['fifa_id'],			// PRIMARY KEY1 TOGGETHER
						'player_id' => $record['name'],				// PRIMARY KEY 2 TOGGETHER
						'player_position_id' => $record['position'],
						'is_captain' => $record['captain'],
						'is_starter' => 0];
				}
				
				/****/
				
				/****
				* type of events // match team event
				****/

				// Home
				foreach($records['home_team_events'] as $record)
				{
					$type_of_event_inserts[] = [
						'id' => $record['type_of_event']];
						
					$match_team_event_inserts[] = [
						'id' => $record['id'],							// PRIMARY KEY
						'match_id' => $records['fifa_id'],
						'team_id' => $this->get_primary_key_value($team_inserts, 'id', 'country', $records['home_team_country']),
						'player_id' => $record['player'],
						'type_of_event_id' => $record['type_of_event'],
						'time' => $record['time']];
				}
				
				// Away
				foreach($records['away_team_events'] as $record)
				{
					$type_of_event_inserts[] = [
						'id' => $record['type_of_event']];
						
					$match_team_event_inserts[] = [
						'id' => $record['id'],							// PRIMARY KEY
						'match_id' => $records['fifa_id'],
						'team_id' => $this->get_primary_key_value($team_inserts, 'id', 'country', $records['away_team_country']),
						'player_id' => $record['player'],
						'type_of_event_id' => $record['type_of_event'],
						'time' => $record['time']];
				}
			}
		}
		catch(\Exception $e)
		{
			die($e->getMessage());
		}
		
		//We will need to wrap our queries inside a TRY / CATCH block.
		//That way, we can rollback the transaction if a query fails and a PDO exception occurs.
		try
		{
			//We start our transaction.
			Db::get_instance()->beginTransaction();
			
			// Create appropriate models for inserting mapped records in DB
			// First up goes independent models, and then associated. Foreign constrait are fulfilled this way.
			
			$group = new Models\Group;
			$team = new Models\Team;
			$city = new Models\City;
			$stadion = new Models\Stadion;
			$status = new Models\Status;
			$stage = new Models\Stage;
			$tactic = new Models\Tactic;
			$time = new Models\Time;
			$weather_description = new Models\WeatherDescription;
			$match = new Models\Match;
			$match_weather = new Models\MatchWeather;
			$official = new Models\Official;
			$match_official = new Models\MatchOfficial;
			$match_team_statistic = new Models\MatchTeamStatistic;
			$player = new Models\Player;
			$player_position = new Models\PlayerPosition;
			$match_team_list = new Models\MatchTeamList;
			$type_of_event = new Models\TypeOfEvent;
			$match_team_event = new Models\MatchTeamEvent;
			
			$group->insert($group_inserts);
			$team->insert($team_inserts);
			$city->insert($city_inserts);
			$stadion->insert($stadion_inserts);
			$status->insert($status_inserts);
			$stage->insert($stage_inserts);
			$tactic->insert($tactic_inserts);
			$time->insert($time_inserts);
			$weather_description->insert($weather_description_inserts);
			$match->insert($match_inserts);
			$match_weather->insert($match_weather_inserts);
			$official->insert($official_inserts);
			$match_official->insert($match_official_inserts);
			$match_team_statistic->insert($match_team_statistic_inserts);
			$player->insert($player_inserts);
			$player_position->insert($player_position_inserts);
			$match_team_list->insert($match_team_list_inserts);
			$type_of_event->insert($type_of_event_inserts);
			$match_team_event->insert($match_team_event_inserts);
			
			//We've got this far without an exception, so commit the changes.
			Db::get_instance()->commit();
			
			return true;
		} 
		catch(\Exception $e)
		{
			//An exception has occured, which means that one of our database queries failed.
			//Rollback the transaction.
			Db::get_instance()->rollBack();

			die($e->getMessage());
		}
	}
	
	/*****
	* Get results from DB
	*****/
	abstract public function get_results();
	
	/*****
	* Get PRIMARY KEY value using value of UNIQUE KEY for searching in inserts array
	*@inserts Array
	*@primary_key_name String
	*@unique_key_name String
	*@unique_key_value Mixed
	*@return primary_key_value Mixed
	*****/
	protected function get_primary_key_value(Array $inserts, String $primary_key_name, String $unique_key_name, $unique_key_value)
	{
		foreach($inserts as $insert)
		{
			if (strcasecmp($insert[$unique_key_name], $unique_key_value) == 0)
				return $insert[$primary_key_name];
		}
	}
}