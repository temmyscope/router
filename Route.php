<?php
namespace Seven\Router;

/**
* @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
* @package Seven Router Package
*/

use \Opis\Closure\SerializableClosure;
use \DI;
use \Closure;

class Route
{
	private $routes = [];
	private $prefix = "";
	private $middleware = [];
	private $Process_middleware = [];

	public function __construct($namespace = '', $cache_dir = __DIR__)
	{
		$this->url= ( isset($_SERVER['PATH_INFO'])) ? rtrim($_SERVER['PATH_INFO'] , '/') : '/';
		$this->namespace= $namespace;
		$this->request_method = strtolower($_SERVER['REQUEST_METHOD']);
		$this->dir = $cache_dir;
		$this->cached = $this->getCache();
	}

	public function group(array $array, Callable $fn)
	{
		if (is_array($array['middleware']))
		$array['middleware'][0] = $this->namespace.'\\'.$array['middleware'][0];
		
		if ($this->cached === false) {
			@$this->middleware['name'] = $array['name'];
			@$this->middleware['_middle_'] = $array['middleware'];
			$this->prefix = $array['prefix'] ?? "";
			$fn($this);	
		}
		@$this->Process_middleware[$array['name']] = $array['middleware'];
	}

	public function __call($method, $args)
	{
		if( $this->cached === false ){
			$method = strtolower($method);
			$uri = $this->prefix.strtolower($args[0]);
			$callable = $args[1];
			if( is_array($callable) ){
				$callable[0] = $this->namespace.'\\'.$callable[0];
			}elseif(is_string($callable)){
				$callable = serialize($callable);
			}else{
				$callable = serialize(new SerializableClosure($callable));
			}
			if (!empty($this->middleware['name'])) {
				$this->routes[$this->middleware['name']][$method][$uri] =  $callable;
			} else {
				$this->routes['default'][$method][$uri] =  $callable;
			}	
		}
	}

	public function run(){
		$routes = [];
		if ($this->cached) {	
			$routes = $this->cached;
			unset($this->cached);
		} else {
			$this->setCache();
			$routes = $this->routes;
			unset($this->routes);
		}
		
		if(@$fn = $routes['default'][$this->request_method][$this->url]){
			return $this->diLoad($fn);
		}else{
			$exp = explode('/', $this->url);
			$param = $this->sanitize(array_pop($exp));
			$url_to_uri = implode('/', $exp).'/';
			if (@$fn = $routes['default'][$this->request_method][$url_to_uri] ) {
				return $this->diLoad($fn, [$param]);
			}
		}

		$call = [];
		foreach ($this->Process_middleware as $key => $value) {
			if ( @$fn = $routes[$key][$this->request_method][$this->url] ) {
				$call ['call'] = $fn;
				$call['param'] = [];
				$call['middleware'] = $value;
				break;
			}
			$exp = explode('/', $this->url);
			$param = $this->sanitize(array_pop($exp));
			$url_to_uri = implode('/', $exp).'/';
			if (@$fn = $routes[$key][$this->request_method][$url_to_uri] ) {
				$call ['call'] = $fn;
				$call['param'] = [$param];
				$call['middleware'] = $value;
				break;
			}
		}
		if(!empty($call)){
			$route = new Route;
			$this->diLoad($call['middleware'], [ function() use ($route, $call){
				return $route->diLoad($call['call'], $call['param']);
			}]);
		}else{
			return http_response_code(404);
		}
	}

	protected function getCache()
	{
		return @include $this->dir.'/route7.cache.php' ?? false;
	}

	protected function setCache()
	{
		file_put_contents($this->dir.'/route7.cache.php', "<?php return ".var_export($this->routes, true).";", LOCK_EX);
	}

	protected function diLoad($fn, $params = []){
		$callable = is_string($fn) ? unserialize($fn) : $fn;
		$builder = new DI\ContainerBuilder();
		$builder->enableCompilation($this->dir . '/tmp');
		$builder->writeProxiesToFile(true, $this->dir . '/tmp/proxies');
		$builder->useAnnotations(false);
		$container = $builder->build();
		$container->call($callable, $params);
	}

	private function sanitize($dirty){
        return htmlentities($dirty, ENT_QUOTES, 'UTF-8');;
  	}
}