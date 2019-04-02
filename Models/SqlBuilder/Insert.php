<?php
namespace Worldcup\App\Models\SqlBuilder;

use Worldcup\App\Db;

class Insert Extends Builder
{
	public function get(): Bool
	{
		$sth = Db::get_instance()->prepare($this->sql);
		
		return $sth->execute($this->bind_params);
	}
}