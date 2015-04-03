<?php
namespace Nepf;

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