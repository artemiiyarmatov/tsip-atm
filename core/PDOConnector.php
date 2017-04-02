<?php

namespace core;

/**
 * PDO Database connector using database configuration.
 */
class PDOConnector
{
    /**
     * Connects user to database.
     *
     * @return PDO database connection specimen.
     */
	public function Connect()
	{
		$sep = DIRECTORY_SEPARATOR;
		$dsn = include_once(BASE_PATH . "config{$sep}db.php");
        if (false === $dsn) {
            throw new Exception('Can\t read database config file. Check that file \'config/db.php\' exists and server has permissions for read this file.');
        }
		$opt = [
			\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_DEFAULT_FETCH_MODE=>\PDO::FETCH_ASSOC,
		];
		try {
			$dsn_string = "{$dsn['dbtype']}:dbname={$dsn['dbname']};host={$dsn['host']};port:{$dsn['port']};charset={$dsn['charset']}";
			$dbh = new \PDO($dsn_string, $dsn['user'], $dsn['pass'], $opt);
			return $dbh;
		} catch (PDOException $e) {
			Logger::CatchError("Couldn't connect to database");
		}
	}
}
