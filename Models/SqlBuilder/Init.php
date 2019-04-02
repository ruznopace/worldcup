<?php
namespace Worldcup\App\Models\SqlBuilder;

use Worldcup\App\Db;

class Init Extends Builder
{
	public function get()
	{
		$sth = Db::get_instance()->prepare($this->sql);
		$sth->setFetchMode(\PDO::FETCH_INTO, $this->fetch_mode);
		$sth->execute($this->bind_params);
		
		return $sth->fetch();
	}
}