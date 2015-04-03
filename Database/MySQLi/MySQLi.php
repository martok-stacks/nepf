<?php
namespace Framework\Database\MySQLi;
use Framework\Database\Database;

class MySQLi extends Database {
	public $connection;
	function __construct($user, $password, $schema, $host=null) {
		if (empty($host)) {
			$host = 'localhost';
		}
		$this->connection = new \mysqli($host, $user, $password, $schema);
	}

	function handleErrors() {
		trigger_error("MySQLi Error {$this->connection->errno}: {$this->connection->error}",E_USER_ERROR);
	}

	function prepare($sql) {
		return new Query($this, $sql);
	}
}
?>