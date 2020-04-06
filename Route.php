<?php
namespace Seven\Router;

/**
* @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
* @package Seven Router Package
*/

use \DI;

class Route
{
	private $routes = [];
	private $prefix = "";
	private $dir = __DIR__;

	public function __construct($namespace = '')
	{
		$this->url= ( isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] != '/') ? rtrim($_SERVER['PATH_INFO'] , '/') : '/';
		$this->namespace= $namespace;
		$this->request_method = strtolower($_SERVER['REQUEST_METHOD']);
		$this->cached = $this->getCache();
	}

	public function group(array $array, Callable $fn)
	{
		if( isset($array['prefix']) ){
			$this->prefix = $array['prefix'];
			$fn($this);
		}
	}

	public function __call($method, $args)
	{
		if( $this->cached === false ){
			$method = strtolower($method);
			$uri = $this->prefix.strtolower($args[0]);
			$callable = $args[1];
			if( is_array($callable) ){
				$callable[0] = $this->namespace.'\\'.$callable[0];
			}
			$this->routes[$method][$uri] =  $callable;
		}
	}

	public function run(){
		if ( $this->cached === false ) {
			$this->setCache();
			return $this->processRequest($this->routes);
		}
		return $this->processRequest($this->cached);
	}

	protected function processRequest(array $routes)
	{
		if ( @$fn = $routes[$this->request_method][$this->url] ) {
			return self::diLoad($fn);
		}else{
			$exp = explode('/', $this->url);
			$param = $this->sanitize(array_pop($exp));
			$url_to_uri = implode('/', $exp).'/';
			if (@$fn = $routes[$this->request_method][$url_to_uri] ) {
				return self::diLoad($fn, [$param]);
			}else{
				return http_response_code(404);
			}
		}
	}

	public function getCache()
	{
		return @include $this->dir.'/tmp/route7.cache.php' ?? false;
	}

	public function setCache()
	{
		file_put_contents($this->dir.'/tmp/route7.cache.php', "<?php return ".var_export($this->routes, true).";", LOCK_EX);
	}

	protected static function diLoad(Callable $fn, $params = []){
		$builder = new DI\ContainerBuilder();
		$builder->enableCompilation(__DIR__ . '/tmp');
		$builder->writeProxiesToFile(true, __DIR__ . '/tmp/proxies');
		$builder->useAnnotations(false);
		$container = $builder->build();
		$container->call($fn, $params);
	}

	private function sanitize($dirty){
        return htmlentities($dirty, ENT_QUOTES, 'UTF-8');;
  	}
}