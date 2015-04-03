<?php
namespace Nepf\System;
use Nepf\Kernel;

class ErrorHandler {
	private static $debug = false;
	function __construct(){
		throw new Exception("Static only!");
	}

	public static function Register($debug=false) {
		self::$debug = $debug;
		set_error_handler(array(__CLASS__,'ErrorHandler'));
		set_exception_handler(array(__CLASS__,'ExceptionHandler'));
		register_shutdown_function(array(__CLASS__,'ShutdownHandler'));
	}

	public static function ErrorHandler($no,$str,$file,$line) {
		if (!!(error_reporting()&$no) && self::isFatal($no)) {
			throw new \ErrorException($str,$no,0,$file,$line);
		}
		if (!self::$debug && !self::isFatal($no)) {
			return;
		}
		$ct = new CallTrace(self::$debug, $no,$str,$file,$line,null);
		echo '<div>'.$ct->getInfoLine().'</div>';
	}

	public static function ExceptionHandler($exception) {
		$ct = CallTrace::FromException($exception, self::$debug);
		echo '<div>'.$ct->getHTML().'</div>';
	}

	public static function ShutdownHandler() {
		$err=error_get_last();
		if(!empty($err) && self::isFatal($err['type'])) {
			$ct = new CallTrace(self::$debug, $err['type'],$err['message'],$err['file'],$err['line'],debug_backtrace());
			echo '<div>'.$ct->getHTML().'</div>';
		}
	}

	protected static function isFatal($errno)
	{
		$recoverable = E_NOTICE|E_USER_NOTICE|E_DEPRECATED|E_USER_DEPRECATED|E_STRICT;
		return !($errno & $recoverable);
	}

	public static function formatTrace(\Exception $exception) {
		$ct = CallTrace::FromException($exception, self::$debug);
		return $ct->getHTML();
	}
}

?>