<?php
namespace Nepf\Response;

class FileResponse extends AbstractResponse{
	private $content;
	private $file;
	private $name;

	function __construct($fullFilename, $contentType=null, $attachmentName=null){
		$this->file=$fullFilename;
		if (empty($contentType)) {

		}
		$this->content = $contentType;
		$this->name = $attachmentName;
	}

	public function sendHeaders() {
		header('Content-Type: '.$this->content);
		if (!empty($this->name)) {
			header('Content-Disposition: attachment; filename='.basename($this->name));
		}
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($this->file));
	}

	public function sendData(){
		readfile($this->file);

	}
}

?>