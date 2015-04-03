<?php
namespace Framework\Response;

class HTTPRedirectResponse extends AbstractResponse{
	const MovedPermanently = 301;
	const Found = 302;
	const SeeOther = 303;
	const TemporaryRedirect = 307;


	private $location;
	private $mode;

	function __construct($location, $mode=self::MovedPermanently){
		$this->location = $location;
		$this->mode = $mode;
	}

	public function sendHeaders() {
		header("Location: ".$this->location,TRUE,$this->mode);
	}

	public function sendData(){
	}
}

?>