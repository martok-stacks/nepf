<?php
namespace Nepf\Database;

abstract class AbstractQuery {
	function __construct(Database $db, $sql) {}
	function __destruct() {
		$this->Close();
	}

	abstract function execute(/*...*/);
	abstract function close();
}
?>