<?php
namespace Nepf\Response;
use Nepf\Template;

class JSONResponse extends AbstractResponse{
	private $data;
	function __construct($data){
		$this->data = $data;
	}

	public function sendHeaders() {
		header('Content-Type: text/json');
	}

	public function sendData(){
		echo json_encode($this->data);
	}
}

?>