<?php
namespace Framework\System;

class EnumIterator implements \Iterator{
	private $defined;
	private $cursor;
	function __construct($enumClass){
		$this->defined = array();
		$this->cursor = 0;
		
		$refl = new \ReflectionClass($enumClass);
		$const = $refl->getConstants();
		foreach ($const as $c => $v){
			$this->defined[] = array($c, $v);
		}
	}
	
	function rewind() {
		$this->cursor = 0;
	}

	function current() {
		return $this->defined[$this->cursor][1];
	}

	function key() {
		return $this->defined[$this->cursor][0];
	}

	function next() {
		$this->cursor++;
	}

	function valid() {
		return count($this->defined)>$this->cursor;
	}
}

class Enum {
	public static function All() {
		return new EnumIterator(get_called_class());
	}
}

?>