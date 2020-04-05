<?php
namespace Seven\Router;

/**
* @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
* @copyright MIT
*/
use \DI;

class Route
{
	private $routes = [];

	public function __construct($namespace)
	{
		$this->url= ( isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] != '/') ? rtrim($_SERVER['PATH_INFO'] , '/') : '/';
		$this->namespace= $namespace;
		$this->request_method = strtolower($_SERVER['REQUEST_METHOD']);
		$this->cached = $this->cache()->get(); 
	}

	/**
	* $array = ['prefix' => '/api', 'guard' => [callable that must return true 
	* for the routes in this group to be processed ] ] ]
	*/
	public function group(array $array, Closure $fn)
	{
		if( array_key_exists('prefix', $array) && !Strings::startswith($this->url, $array['prefix']) ){
			return null;
		}
		//guard ust be callable and must return true in order for the other routes in the closure to be processed
		if( array_key_exists('guard', $array) ){
			$fn();
		}
	}

	public function __call($method, $args)
	{
		if( $this->cached === false ){
			$method = strtolower($method);
			$uri = strtolower($args[0]);
			$this->routes[$method][$uri] =  $args[1];
		}
	}


	public function run()
	{
		if ($this->cached === false) {
			$this->cache()->set( (array)$this->routes );
		}
		return $this->processRequest( $this->cached, $this->request_method );
	}

	public function processRequest(array $routes, string $request_method)
	{
		if ( $fn = $routes[$request_method][$this->url] ) {
			return self::diLoad($fn);
		}else{
			$exp = explode('/', $this->url);
			$param = $this->sanitize(array_pop($exp));
			$url_to_uri = implode('/', $exp).'/';
			if ($fn = $routes[$request_method][$url_to_uri] ) {
				return self::diLoad($fn, $param);
			}
		}
	}

	private function cache($dir = __DIR__)
	{
		return new class($dir){

			public function __construct($dir)
			{
				$this->dir = $dir;
			}
			public function get() {
				return @include $this->dir.'/tmp/route7.cache.php' ?? false;
			}
			public function set(array $val) {
			   	file_put_contents($this->dir.'/tmp/route7.cache.php', '<?php return '.$val.';', LOCK_EX);
			}
			public function exists(): bool
			{
				return file_exists($this->dir.'/tmp/route7.cache.php');
			}
		};
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