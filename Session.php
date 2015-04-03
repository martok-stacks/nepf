<?php
namespace Framework;
use Framework\System\Singleton;

class Session extends Singleton{
	public $hasSession;
	function __construct(Request $source){
		parent::__construct();
		if ($source->cookie(session_name())) {
			$this->open();
		}
	}

	function open() {
		session_start();
		$this->hasSession = true;
	}

	function close() {
		if ($this->hasSession) {
			session_destroy();
			$this->hasSession = false;
			$_SESSION = array();
			unset($_SESSION);
		}
	}

	function __get($field){
		return isset($_SESSION[$field])?$_SESSION[$field]:null;
	}

	function __set($field, $value){
		if (!$this->hasSession) {
			$this->open();
		}
		$_SESSION[$field] = $value;
	}

	function __isset($field){
		return isset($_SESSION[$field]);
	}

	function __unset($field){
		unset($_SESSION[$field]);
	}
}

?>