<?php
namespace Nepf\Database\MySQLi;
use Nepf\Database\Database;
use Nepf\Database\AbstractResultset;

class Resultset extends AbstractResultset {
	private $stmt;
	private $res_meta;
	private $res_row;

	function __construct($db, $statement) {
		$this->stmt = $statement;
		if (0>$this->affected_rows()) {
			$this->res_meta = $this->stmt->result_metadata();
			$this->first();
		}
	}

	function first() {
		$params = array();
		$this->res_row = array();
		$this->res_meta->field_seek(0);
		while ($field = $this->res_meta->fetch_field())
		{
			$params[] = &$this->res_row[$field->name];
		}
		$this->stmt->store_result();
		call_user_func_array(array($this->stmt, 'bind_result'), $params);
		return $this->stmt->data_seek(0);
	}

	function next($type = self::AS_ASSOC) {
		if ($this->stmt->fetch()) {
			$result = array();
			foreach($this->res_row as $key => $val)
			{
				if (self::AS_ASSOC == $type) {
					$result[$key] = $val;
				} else {
					$result[] = $val;
				}
			}
			return $result;
		}
		return false;
	}

	function result_rows() {
		return $this->stmt->num_rows;
	}
	function affected_rows() {
		return $this->stmt->affected_rows;
	}
	function autoinc_id(){
		return $this->stmt->insert_id;
	}

}
?>