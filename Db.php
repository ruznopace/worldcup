<?php
namespace  Worldcup\App;

class Db
{
	const DB_TYPE = 'mysql';
	const DB_HOST = 'localhost';
	const DB_NAME = 'worldcup';
	const DB_USERNAME = 'root';
	const DB_PASSWORD = 'kaka';
	
    private static $instance;

    protected function __construct() { }

    protected function __clone() { }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function get_instance(): \PDO
    {
        if (!self::$instance) {
			try
			{
				self::$instance = new \PDO(static::DB_TYPE . ':dbname=' . static::DB_NAME . ';host=' . static::DB_HOST, static::DB_USERNAME, static::DB_PASSWORD);
			}
			catch(\PDOException $e)
			{
				die("Can't connect on DB. " . $e->getMessage());
			}
        }

        return self::$instance;
    }
}
