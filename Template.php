<?php
namespace Framework;

class Template {
	private $file;
	private $components = array();
	private $vars = array();

	function __construct($basefile){
		$fp = Kernel::getInstance()->getResources();
		$this->file = $fp.DIRECTORY_SEPARATOR.$basefile;
	}

	/* Setup funcions */
	public function set($var, $value){
		$this->vars[$var] = $value;
	}

	public function add($var, $value){
		if (isset($this->vars[$var])) {
			$a = $this->vars[$var];
			if (is_array($a)) {
				$a[] = $value;
			} else {
				$a = array($a, $value);
			}
			$this->vars[$var] = $a;
		} else {
			$this->vars[$var] = array($value);
		}
	}

	public function embed($component, Template $tpl) {
		$this->components[$component] = $tpl;
	}

	/* Reading funcions  */
	public function doEsc($text) {
		return htmlspecialchars($text);
	}

	public function doURL($text) {
		return urlencode($text);
	}

	private function getVar($var,$default='') {
		return isset($this->vars[$var])?$this->vars[$var]:$default;
	}

	public function get($var) {
		return $this->getVar($var);
	}

	public function getEsc($var) {
		return $this->doEsc($this->getVar($var));
	}

	public function getURL($var) {
		return $this->doURL($this->getVar($var));
	}

	public function getArray($var) {
		$a = $this->getVar($var,array());
		if (!is_array($a)) {
			return array($a);
		}
		return $a;
	}

	public function isEmpty($var) {
		$a = $this->getVar($var,null);
		return empty($a);
	}

	public function component($name) {
		return isset($this->components[$name])?$this->components[$name]->render():'';
	}

	public function render() {
		if (!is_readable($this->file)) {
			throw new System\FrameworkException('Templater could not find file: '.$this->file);
		}
		ob_start();
		$TPL = $this;
		$renderfunc = function($__tpl_filename) use($TPL) {
			require $__tpl_filename;
		};
		$renderfunc($this->file);
		return ob_get_clean();
	}
}

?>