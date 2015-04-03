<?php
namespace Framework\Response;
use Framework\Template;

class JSONResponse extends AbstractResponse{
	private $data;
	function __construct($data){
		$this->data = $data;
	}

	public function sendHeaders() {
	}

	public function sendData(){
		echo json_encode($this->data);
	}
}

?>