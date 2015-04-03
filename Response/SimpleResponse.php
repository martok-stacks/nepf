<?php
namespace Nepf\Response;

class SimpleResponse extends AbstractResponse{
	private $headers = array();
	private $html;

	function __construct($text, $headers=array()){
		$this->headers=$headers;
		$this->html = $text;
	}
	public function sendHeaders() {
		foreach($this->headers as $header) {
			header($header);
		}
	}

	public function sendData(){
		echo $this->html;
	}
}

?>