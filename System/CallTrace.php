<?php
namespace Nepf\System;

class CallTrace {
	private static $PHP_ERROR_TYPES = array (
		E_ERROR => 'ERROR',
		E_WARNING => 'WARNING',
		E_PARSE => 'PARSING ERROR',
		E_NOTICE => 'NOTICE',
		E_CORE_ERROR => 'CORE ERROR',
		E_CORE_WARNING => 'CORE WARNING',
		E_COMPILE_ERROR => 'COMPILE ERROR',
		E_COMPILE_WARNING => 'COMPILE WARNING',
		E_USER_ERROR => 'TRIGGERED ERROR',
		E_USER_WARNING => 'TRIGGERED WARNING',
		E_USER_NOTICE => 'TRIGGERED NOTICE',
		E_STRICT => 'STRICT NOTICE',
		E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
		E_DEPRECATED => 'DEPRECATED'
	);


	private $full;

	private $code;
	private $message;
	private $file;
	private $line;
	private $trace;

	function __construct($fullDebugMode, $errno,$errstr,$errfile,$errline,$backtrace)
	{
		$this->full = $fullDebugMode;
		$this->code = $errno;
		$this->message = $errstr;
		$this->file = $errfile;
		$this->line = $errline;
		$this->trace = $backtrace;
	}

	static function FromException(\Exception $exception, $fullDebugMode) {
		return new self($fullDebugMode,$exception, $exception->getMessage(), $exception->getFile(),$exception->getLine(),$exception->getTrace());
	}

	public function getHTML() {
		// start backtrace
		$trace = array_map(array($this, "formatBacktrace"), $this->trace);
		// display error msg
		return '<table style="width:100%">'.
			'<tr><td colspan="2">' . $this->getInfoLine() . '</td></tr>'.
			($this->full?join($trace):'').
			'</table>';
	}

	public function getInfoLine() {
		$errfile = $this->webPath($this->file);
		// create error message
		if ($this->code instanceof \Exception) {
			$err = get_class($this->code);
		} else {
			if (isset(self::$PHP_ERROR_TYPES[$this->code])) {
				$err = self::$PHP_ERROR_TYPES[$this->code];
			} else {
				$err = "Unknown({$this->code})";
			}
		}

		// <b>Warning</b>:  filemtime() : stat failed for xxx in <b>file</b> on line <b>31</b><br />
		$errMsg = "{$this->message} in <b>{$errfile}</b> on line <b>{$this->line}</b>";
		return '<b>' . $err . '</b>: ' . nl2br($errMsg);
	}

	function formatBacktrace($v)
	{
		if (isset($v['class']) && (
			($v['class'] == get_class($this))||($v['class'] == __NAMESPACE__.NAMESPACE_SEPARATOR.'ErrorHandler')
		    )) {
			return '';
		}

		$row = array();
		if (isset($v['file']) || isset($v['line'])) {
			$row[] = "<td><pre>" .
			(isset($v['file'])?$this->webPath($v['file']):'') .
			":" .
			(isset($v['line'])?$v['line']:'') .
			"</pre></td>";
		} else {
			$row[] = "<td><pre><i>(no source)</i></pre></td>";
		}

		$args = (isset($v['args'])?$this->argsToString($v['args']) : '');
		if (isset($v['class'])) {
			$row[] = "<td><code>{$v['class']}{$v['type']}{$v['function']}($args)</code></td>";
		} elseif (isset($v['function'])) {
			$row[] = "<td><code>{$v['function']}($args)</code></td>";
		} else {
			$row[] = "<td></td>";
		}
		return '<tr style="vertical-align:top">' . join('', $row) . '</tr>';
	}

	function argsToString($array)
	{
		if ($this->full) {
			return join(", ", array_map(array($this, "getArgument"), $array));
		} else {
			return '';
		}
	}

	function getArgument($arg)
	{
		switch (strtolower(gettype($arg))) {
			case 'string':
				return '"' . $arg . '"';

			case 'boolean':
				return !!$arg?"TRUE":"FALSE";

			case 'object':
				return 'object(' . get_class($arg) . ')';

			case 'array':
				$ret = 'array(';
				$separtor = '';

				foreach ($arg as $k => $v) {
					$ret .= $separtor . $this->getArgument($k) . ' => ' . $this->getArgument($v);
					$separtor = ', ';
				}
				$ret .= ')';

				return $ret;

			case 'resource':
				return 'resource(' . get_resource_type($arg) . ')';

			default:
				return var_export($arg, true);
		}
	}
	function webPath($file)
	{
		$file = realpath($file);
		return substr($file, strlen($_SERVER["DOCUMENT_ROOT"]) + 1, 10000);
	}
}

?>