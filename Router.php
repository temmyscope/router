<?php
namespace Seven\Router;

use \Exception;
use \DI;
/**
 * @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
 * @package Seven Router Package
 *
*/

class Router{

	/**
	* @var string $namespace
	* @var Array $controller
	*/
	private /*string*/ $controller;
	private /*string*/ $method;
	private /*array*/ $params;
	

	public function __construct(Array $config)
	{
		$this->config = $config;
		[$this->controller, $this->method, $this->params] = $this->routeParser(
			$config['controller'], $config['method']
		);
		$this->params = $this->sanitize($this->params);
	}

	public function call(array $controllers)
	{
		if ( $this->defined($controllers, $this->controller, $this->method)){
			return self::diLoad([ $this->getConfig('namespace').'\\'.$this->controller, $this->method ], $this->params);
		}
	}

	protected static function diLoad(Callable $fn, $params = []){
		$builder = new DI\ContainerBuilder();
		$builder->enableCompilation($this->config['cache_dir']. '/tmp');
		$builder->writeProxiesToFile(true, $this->config['cache_dir'].'/tmp/proxies');
		$builder->useAnnotations(false);
		$container = $builder->build();
		$container->call($fn, $params);
	}

	private function sanitize(array $dirty){
		$clean_input = [];
    	foreach ($dirty as $k => $v) {
            if ($v != '') {
            	$clean_input[$k] = htmlentities($v, ENT_QUOTES, 'UTF-8');
            }
        }
        return $clean_input;
  	}

	protected function getConfig(string $var)
	{
		return $this->config[$var];
	}

	protected function defined(array $controllers, string $controller, string $method): bool
	{
		return (array_key_exists($controller , $controllers ) && in_array($method, $controllers[$controller]));
	}

	public function routeParser(string $default, string $method = 'index'): SplFixedArray
	{
		$url = (isset($_SERVER['PATH_INFO'])) ? explode('/', $_SERVER['PATH_INFO']) : [];
		array_shift($url);
		$controller = new SplFixedArray(3);
		if (!isset($url[0])) {
			$controller[0] = $default;
			$controller[1] = $method;
			$controller[2] = $url;
		} else {
			$controller[0] = ucfirst($url[0]).'Controller';
			array_shift($url);
			$controller[1] = $url[1] ?? 'index';
			array_shift($url);
			$controller[2] = $url;
		}
		return $controller;
	}

	public static function redirect($base_url, $location){
		$location = $base_url."/{$location}";
		if(!headers_sent()){ header("location: $location"); exit();
		}else{
			echo "<script type='text/javascript'> window.location.href= '{$location}';</script>";
			echo '<noscript> <meta http-equiv="refresh" content="0;url='.$location.'"/></noscript>'; exit();
		}
	}

	public static function getRedirect(){
		@$route = $_SESSION['redirect'];
		unset($_SESSION['redirect']);
		return self::redirect($route);
	}

}