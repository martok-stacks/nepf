<?php
namespace Nepf;
use Nepf\System\Singleton;
use Nepf\System\FrameworkException;
use Nepf\System\ErrorHandler;

class Kernel extends Singleton{
	const ModeProduction = 'prod';
	const ModeDebug = 'debug';

	private $environment;
	private $resources;
	private $config;
	private $routes = array();

	public $request;
	public $db;

	function __construct($env, $resourceFolder){
		parent::__construct();
		$this->environment = $env;
		$this->resources = $resourceFolder;
		$this->config = array(
			'database' => null,
			'root' => dirname($_SERVER['PHP_SELF'])
		);
		ErrorHandler::Register($env == Kernel::ModeDebug);
	}

	public function getEnvironment() {
		return $this->environment;
	}

	public function getResources() {
		return $this->resources;
	}

	public function getWebRoot() {
		return $this->config['root'];
	}

	public function setup($config = array())
	{
		$this->config = array_merge($this->config, $config);
		$this->db = $this->getOpt('database');

		$this->request = new Request();
	}

	public function registerActionGroup($prefix, $class)
	{
		if ('/'!==substr($prefix,-1)) {
			$prefix .= '/';
		}
		$ref = new \ReflectionClass($class);
		$actions = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
		foreach ($actions as $action) {
			$fname = $action->getName();
			if (preg_match('#^(.*)Action$#',$fname, $m)) {
				$name = $m[1];
				if ('default'===$name) {
					$name='';
				}
				$minParams = $action->getNumberOfRequiredParameters();
				$maxParams = $action->getNumberOfParameters();
				$call = $prefix.$name;
				$call = trim($call, '/');
				$this->routes[] = array(
					'patch' => strlen($call)?explode('/',$call):array(),
					'class' => $class,
					'func' => $fname,
					'minParam' => $minParams,
					'maxParam' => $maxParams
				);
			}
		}

		usort($this->routes, function($l, $r) {
			return count($r['patch']) - count($l['patch']);
		});
	}

	public function run(){
		try {
			$response = $this->performRequest();
		} catch ( \Exception $e) {
			$response = new Response\HTTPErrorResponse(500, 'Uncaught Exception',
				ErrorHandler::formatTrace($e, $this->environment));
		}
		if (!is_object($response)) {
			$response = new Response\SimpleResponse((string)$response);
		}
		$response->sendHeaders();
		$response->sendData();
	}

	private function performRequest() {
		$req = Request::getInstance();
		$obj = $req->getObject();
		$num = count($obj);
		$longest = null;
		$length = -1;
		foreach ($this->routes as $route){
			$base = count($route['patch']);
			if ($base>$length && $num>=$base) {
				$good=true;
				for ($i=0; $i<$base; $i++) {
					if ($route['patch'][$i]!==$obj[$i]) {
						$good = false;
						break;
					}
				}
				if ($good) {
					$length = $base;
					$longest = $route;
				}
			}
		}
		if (is_null($longest)) {
			return new Response\HTTPErrorResponse(404, 'Not found',
				'<p>The requested page cannot be found.</p>');
		}

		$base = count($longest['patch']);
		$pn = $num - $base;
		if ($pn>=$longest['minParam'] && $pn <= $longest['maxParam']) {
			return $this->execute($req, $longest);
		} else {
			return new Response\HTTPErrorResponse(400, 'Invalid parameters',
				'<p>The requested pagelet for <code>/'.
				htmlspecialchars(join('/',$longest['patch'])).
				'</code> exists, but cannot be used with '.($pn).' parameters</p>');
		}

	}

	private function execute(Request $req, $route)
	{
		$params = array_slice($req->getObject(), count($route['patch']));
		$class = $route['class'];
		$handler = new $class($this);
		return call_user_func_array(array(&$handler, $route['func']), $params);
	}

	public function getOpt($opt) {
		return $this->config[$opt];
	}
}
?>