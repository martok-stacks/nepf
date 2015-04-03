<?php
namespace Framework\System;

define('NAMESPACE_SEPARATOR', '\\');
class AutoLoader {
	private static $namespaces = array();
	function __construct(){
		throw new Exception("Static only!");
	}

	public static function Register()
	{
		spl_autoload_register(__NAMESPACE__ .'\AutoLoader::LoadClass'); // As of PHP 5.3.0
	}

	public static function RegisterNamespaces($info)
	{
		foreach($info as $ns => $p) {
			self::RegisterNamespace($ns, $p);
		}
	}

	public static function RegisterNamespace($namespace, $path)
	{
		for ($i=0; $i<count(self::$namespaces); $i++) {
			$reg=self::$namespaces[$i];
			if ($reg[0]===$namespace) {
				return false;
			}
		}
		self::$namespaces[] = array($namespace,$path);
	}

	public static function LoadClass($className) {
		if (FALSE===($bs=strrpos($className, NAMESPACE_SEPARATOR))) {
			$ns = '';
			$class = $className;
		} else {
			$ns = substr($className, 0, $bs);
			$class = substr($className, $bs+1);
		}
		for ($i=0; $i<count(self::$namespaces); $i++) {
			$reg=self::$namespaces[$i];
			if (0===strncasecmp($ns, $reg[0], strlen($reg[0]))) {
				$rest = substr($ns, strlen($reg[0]));
				$pathname = $reg[1].DIRECTORY_SEPARATOR.strtr($rest, NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR);
				$file = $pathname.DIRECTORY_SEPARATOR.$class.'.php';
				if (is_readable($file)) {
					require $file;
					// return true if class is loaded
					return class_exists($className, false);
				}
			}
		}
		return false;
	}
}

?>