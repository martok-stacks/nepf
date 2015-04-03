<?php
namespace Nepf\Response;

class EventSourceResponse extends AbstractResponse{
	private $flushDummy;
	private $headerDone;
	function __construct($flushDummy){
		$this->flushDummy = $flushDummy;
		$this->headerDone = false;
	}

	private function emitHeader() {
		if ($this->headerDone) {
			return;
		}
		$this->headerDone = true;

		header('Content-Type: text/event-stream');
		header('Connection: close');
		header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
	}

	private function flush() {
		if ($this->flushDummy) {
			// insert filler bytes to fill up fcgi buffer
			echo "event: fcgidummy\n";
			echo "data:" . str_repeat("#", 64*1024);
			echo "\n\n";
		}
		@ob_flush();
		flush();
	}

	public function sendEvent($event, $data) {
		$this->emitHeader();
		echo "event: $event\n";
		echo 'data: '.json_encode($data);
		echo "\n\n";
		$this->flush();
	}

	public function sendMessage($data) {
		$this->emitHeader();
		echo 'data: '.json_encode($data);
		echo "\n\n";
		$this->flush();
	}

	public function sendHeaders() {
		// data is already sent; just here for Nepf\Kernel
	}

	public function sendData(){
		// data is already sent; just here for Nepf\Kernel
	}
}

?>