<?php
$oe=error_reporting(E_ALL|E_STRICT);
$od=ini_set("display_errors", 1);

require __DIR__.'/System/AutoLoader.php';
use \Nepf\System\AutoLoader;

AutoLoader::Register();
AutoLoader::RegisterNamespaces(array(
	'Nepf' => __DIR__
));
error_reporting($oe);
ini_set("display_errors", $od);


?>