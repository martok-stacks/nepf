<?php
namespace Nepf\Database;

abstract class AbstractResultset {
	const AS_ASSOC = 0;
	const AS_ARRAY = 1;
	function __construct($db) {	}

	abstract function first();
	abstract function next($type = self::AS_ASSOC);

	abstract function result_rows();
	abstract function affected_rows();
	abstract function autoinc_id();

	function toArray() {
		$this->first();
		$ret = array();
		while($row = $this->next(self::AS_ARRAY)){
			$ret[] = $row;
		}
		return $ret;
	}
	function toArrayAssoc() {
		$this->first();
		$ret = array();
		while($row = $this->next(self::AS_ASSOC)){
			$ret[] = $row;
		}
		return $ret;
	}
	function singleRow($type = self::AS_ASSOC){
		return $this->next($type);
	}
}
?>