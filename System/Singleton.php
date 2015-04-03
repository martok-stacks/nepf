<?php
namespace Framework\System;

class Singleton {
	private static $instance = array();

	function __construct(){
		$cls = get_called_class();
		if (isset(self::$instance[$cls])) {
			throw new FrameworkException('Only one instance allowed.');
		}
		self::$instance[$cls] = &$this;
	}

	public static function getInstance(){
		$cls = get_called_class();
		return isset(self::$instance[$cls])?self::$instance[$cls]:null;
	}
}


?>