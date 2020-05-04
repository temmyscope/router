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
	const NOT_FOUND = 1;
	const METHOD_NOT_ALLOWED = 2;

	private $routes = [];
	private $cached = false;
	private $fallback = [];
	private $prefix = "";
	private $dir  = "";
	private $inject = [ ]; // ['group_name' => [$var1, $var2]//array of objects or variable injected ]
	private $middleware = [];
	private $Process_middleware = ['default' => ""];

	public function __construct($namespace = '')
	{
		//$this->url= ( isset($_SERVER['PATH_INFO'])) ? rtrim( , '/') : '/';
		$this->namespace= $namespace;
		$this->fallback[1] = function(){ echo "404: Resource Not Found."; };
		$this->fallback[2] = function(){ echo "405: METHOD NOT ALLOWED."; };
	}

	public function enableCache($cache_dir = __DIR__)
	{
		$this->dir = $cache_dir;
		$this->cached = @include $this->dir.'/route7.cache.php' ?? false;
	}

	public static function init($namespace = '')
	{
		return new self($namespace);
	}
	
	public function group(array $array, \Closure $fn)
	{	
		if ($this->cached === false) {
			@$this->middleware['name'] = $array['name'];
			@$this->middleware['_middle_'] = $array['middleware'];
			$this->prefix = $array['prefix'] ?? "";
			$fn($this);
		}
		@$this->Process_middleware[$array['name']] = $array['middleware'];
		@$this->inject[$array['name']] = $array['inject'];
	}

	public function __call($method, $args)
	{
		if( $this->cached === false ){
			$method = strtolower($method);
			$uri = $this->prefix.strtolower($args[0]);
			$callable = $this->processCallable($args[1]);
			if (!empty($this->middleware['name'])) {
				$this->routes[$this->middleware['name']][$method][$uri] =  $callable;
			} else {
				$this->routes['default'][$method][$uri] =  $callable;
			}
		}
	}

	public function setFallback(\Closure $fallback, $case)
	{
		$this->fallback[$case] = $fallback;
	}

	public function run(string $method, string $url){
		$routes = $this->getRoutes();
		$call = $this->routeChecker($routes, $method, $url);
		
		if ( isset($call['middleware']) ) {
			if ( $call['middleware'] != "" ) {
				$route = new Route;
				return $this->diLoad( $call['middleware'], [ function() use ($route, $call){
					return $route->diLoad( $call['call'], $call['param'] );
				}]);
			}
			return $this->diLoad($call['call'], $call['param'] );
		}
		if ( !isset($routes[$method]) ) {
			return $this->diLoad( $this->fallback[2] );
		} return $this->diLoad( $this->fallback[1] );
	}

	protected function diLoad( $fn, $params = [] ){
		$builder = new DI\ContainerBuilder();
		$builder->enableCompilation($this->dir . '/tmp');
		$builder->writeProxiesToFile(true, $this->dir . '/tmp/proxies');
		$container = $builder->build();
		$container->call( is_string($fn) ? unserialize($fn) : $fn, $params);
	}

	protected function routeChecker(array $routes, string $method, string $url): array
	{
		$method = strtolower($method);
		$call = [];
		foreach ($this->Process_middleware as $key => $value) {
			if(@$fn = $routes[$key][$method][$url]){
				$call ['call'] = $fn;
				@$call['param'] = $this->inject[$key] ?? [];
				$call['middleware'] = $value;
				return $call;
			}
		}
		$exp = explode('/', $url);
		$param = htmlentities(array_pop($exp), ENT_QUOTES, 'UTF-8');
		$url_to_uri = implode('/', $exp).'/';
		foreach ($this->Process_middleware as $key => $value) {
			if (@$fn = $routes[$key][$method][$url_to_uri] ) {
				$call ['call'] = $fn;
				@$call['param'] = $this->inject[$key] ?? [];
				array_push( $call['param'], $param );
				$call['middleware'] = $value;
				return $call;
			}
		}
		return $call;
	}

	protected function processCallable($callable)
	{
		if( is_array($callable) ){
			$callable[0] = $this->namespace.'\\'.$callable[0];
			return $callable;
		}elseif(is_string($callable)){
			return serialize($callable);
		} return serialize(new SerializableClosure($callable));
	}

	protected function getRoutes(): Array
    {
        if ($this->cached === false) {
			if ( $this->dir != "") {
				file_put_contents($this->dir.'/route7.cache.php', "<?php return ".var_export($this->routes, true).";", LOCK_EX);
			}
            return $this->routes;
		}
        return $this->cached;
	}
}