<?php
namespace Nepf\Response;
use Nepf\Template;

class TemplateResponse extends AbstractResponse{
	private $template;
	function __construct(Template $template){
		$this->template = $template;
	}

	public function sendHeaders() {
	}

	public function sendData(){
		echo $this->template->render();
	}
}

?>