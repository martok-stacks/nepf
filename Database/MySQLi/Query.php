<?php
namespace Framework\Database\MySQLi;
use Framework\Database\Database;
use Framework\Database\AbstractQuery;

class Query extends AbstractQuery {
	protected $db;
	public $statement;

	function __construct(Database $db, $sql) {
		$this->db = $db;
		if (!($this->statement = $db->connection->prepare($sql))) {
			$db->handleErrors();
		}
	}

	function execute(/*...*/) {
		$parameters = func_get_args();
		if (count($parameters)==1 && is_array($parameters[0])) {
			$parameters = $parameters[0];
		}
		$t = '';
		$ar = array();
		if (count($parameters)) {
			for($i = 0; $i<count($parameters); $i++) {
				$p = &$parameters[$i];
				if (is_numeric($p)) {
					if (is_float($p)) {
						$t.= 'd';
					} else {
						$t.= 'i';
					}
				} else {
					$t.='s';
				}
				$ar[] = &$p;
			}
			array_splice($ar, 0, 0, $t);
			call_user_func_array(array($this->statement, 'bind_param'),$ar);
		}
		$rrr = $this->statement->execute();
		if ($rrr) {
			return new Resultset($this->db, $this->statement);
		}
		return null;
	}

	function close() {
		$this->statement->close();
	}
}
?>