<?php
namespace Nepf\Response;

class HTTPErrorResponse extends AbstractResponse{
	private $code;
	private $title;
	private $html;

	function __construct($code, $title, $html){
		$this->code = $code;
		$this->title = $title;
		$this->html = $html;
	}

	public function sendHeaders() {
		header("HTTP/1.1 {$this->code} $this->title");
		header("Status: {$this->code} $this->title");
		header('Content-Type: text/html');
	}

	public function sendData(){
		echo "<html><head><title>{$this->title}</title></head>".
			"<body><h1>{$this->code}: $this->title</h1>{$this->html}</body></html>";
	}
}

?>