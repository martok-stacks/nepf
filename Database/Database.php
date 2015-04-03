<?php
namespace Framework\Database;
use Framework\Kernel;

abstract class Database {
	function __construct($user, $password, $schema, $host=null) {}

	abstract function handleErrors();
	abstract function prepare($sql);

	public static function get() {
		return Kernel::getInstance()->db;
	}

	public function simpleQuery($sql, $params=null /*...*/) {
		if ((func_num_args()==2) && is_array($params)) {
			$parameter = $params;
		} else {
			$parameter = func_get_args();
			array_shift($parameter);
		}
		if (($q = $this->prepare($sql)) && !is_null($r = $q->execute($parameter))) {
			return $r->toArrayAssoc();
		}
		return null;
	}

	public function valueOf($sql, $params=null /*...*/) {
		if ((func_num_args()==2) && is_array($params)) {
			$parameter = $params;
		} else {
			$parameter = func_get_args();
			array_shift($parameter);
		}
		if (($q = $this->prepare($sql)) && !is_null($r = $q->execute($parameter))) {
			$r=$r->toArray();
			return $r[0][0];
		}
		return null;
	}
}
?>