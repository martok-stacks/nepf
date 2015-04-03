<?php
namespace Framework;
use Framework\System\Singleton;

class Request extends Singleton{
	private $object;
	private $query;

	function __construct(){
		parent::__construct();
		$part = $this->getCalledURI();
		if ($part) {
			$this->object = explode('/', $part);
		} else {
			$this->object = array();
		}
		foreach($this->object as &$val) {
			$val = urldecode($val);
		}
	}

	public function getObject() {
		return $this->object;
	}

	private function getCalledURI()
	{
		$uri = $_SERVER['REQUEST_URI'];
		$scr = $_SERVER['SCRIPT_NAME'];
		if (0 === strncmp($uri, $scr, strlen($scr))) {
			$uri = substr($uri, strlen($scr));
		}
		if ($qu=$_SERVER['QUERY_STRING']) {
			$uri = substr($uri, 0, -strlen($qu)-1);
		}

		if (0 === strncmp('/', $uri, 1)) {
			$uri = substr($uri, 1);
		}

		if (!$uri) {
			$uri = '';
		}
		return $uri;
	}

	public function post($name) {
		return isset($_POST[$name])?$_POST[$name]:null;
	}

	public function get($name) {
		return isset($_GET[$name])?$_GET[$name]:null;
	}

	public function cookie($name) {
		return isset($_COOKIE[$name])?$_COOKIE[$name]:null;
	}

	public static function cleanse($value) {
		return preg_replace('#[^_0-9a-zA-Z]#','',$value);
	}
	
	public function getSession() {
		$s = Session::getInstance();
		return is_null($s)?(new Session($this)):$s;
	}
}

?>