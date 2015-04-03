<?php
namespace Framework\Response;

abstract class AbstractResponse{
	abstract public function sendHeaders();
	abstract public function sendData();
}

?>