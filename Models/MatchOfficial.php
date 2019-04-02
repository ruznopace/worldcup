<?php
namespace Worldcup\App\Models;

class MatchOfficial extends Model
{	
	const TABLE = 'match_official';
	const PRIMARY_KEYS = [];
	const FOREIGN_KEYS = [
		'Official' => ['id' => 'official_id'],
		'Match' => ['id' => 'match_id']];
	const UNIQUE_KEYS = [];
	public $official_id;
	public $match_id;
}
