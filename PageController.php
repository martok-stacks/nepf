<?php
namespace Framework;

class PageController {
	private $kernel;
	/**
	 * Constructor
	 */
	function __construct(Kernel $kernel) {
		$this->kernel = $kernel;
	}
	protected function getKernel() {
		return $this->kernel;
	}

}

?>