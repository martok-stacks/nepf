<?php
namespace Framework\Response;

class BufferedResponse extends AbstractResponse{
	private $headers = array();
	private $html;

	function __construct(){
	}
	public function sendHeaders() {
		foreach($this->headers as $header) {
			header($header);
		}
	}

	public function sendData(){
		echo $this->html;
	}

	public function beginWrite() {
		ob_start();
	}

	public function endWrite() {
		$this->html = ob_get_clean();
	}

	public function addHeader($data) {
		$this->headers[]=$data;
	}
}

?>