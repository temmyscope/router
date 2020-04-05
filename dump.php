<?php
/*
namespace Seven\Router;


* @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
* @copyright MIT

use Seven\Router\DITrait;
use Seven\Vars\Strings;

final class Route
{
	use DITrait;

	private static  $instance = null;
	private static  $authorised = true;
	private static  $uri = '';
	private static  $params = [];

	public static function get(string $uri, ?Callable $fn = null): Route
	{
		if (static::$instance === null) {
		 	static::$instance = new static();
		 	static::$uri = $uri;
		 	if (!is_null($fn)) {
		 		self::$authorised = ( call_user_func($fn) == true) ? true : false;
		 	} else {
		 		self::$authorised =  true;
		 	}
		}
	 	return static::$instance;
	}

	public static function call(Callable $fn, ?Callable $fallback = null)
	{
		$url = self::getUrl();
		if ( Strings::startsWith( $url, static::$uri, true)){
			if( static::$authorised ) {
				$params = array_merge( static::$params, static::getParams($url, static::$uri));
				return static::diLoad($fn, $params );
			}else{
				if (is_callable($fallback)) {
					return static::diLoad($fallback);
				}
			}
		}
	}

	public static function load(Callable $fn, ?Callable $fallback = null)
	{
		if ( Strings::match( self::getUrl(), static::$uri, true)){
			if( static::$authorised ) {
				return static::diLoad($fn, static::$params );
			}else{
				if (!is_null($fallback)) {
					return static::diLoad($fallback);
				}
			}
		}
	}

	public static function inject(array $args): Route
	{
		static::$params = $args;
		return static::$instance;
	}

	protected function getParams($url, $uri): array
	{
		return static::sanitize( explode( '/', substr_replace( $url, '', 0, strlen($uri) ) ) );
	}

	protected static function getUrl(): String
	{
		return (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : "/";
	}
}
*/
/*
<?php
namespace Seven\Router;


* @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
* @copyright MIT

use \DI;

class Route
{
	private $routes = [];

	public function __construct($namespace)
	{
		$this->url= ( isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] != '/') ? rtrim($_SERVER['PATH_INFO'] , '/') : '/';
		$this->namespace= $namespace;
		$this->cache= $this->cache();
		$this->request_method = strtolower($_SERVER['REQUEST_METHOD']);
	}

	* $array =['prefix' => '/api', 'inject' => [array of dependent objects needed ], 'guard' => [callable that must return true 
	* for the routes in this group to be processed ] ] ]
	
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

	public function _Request_()
	{
		$this->rs[$this->request_method][$uri] = $callable;
	}

	public function __call($method, $args)
	{
		
	}


	public function run()
	{
		if ( $this->cache->exists() ) {
			return $this->processRequest( $this->cache->get(), $this->request_method );
		}else{
			$this->processRoutes();
			return $this;
		}
	}

	public function processRequest(array $routes, string $request_method)
	{
		if ( $fn = $routes[$request_method][$this->url] ) {
			return self::diLoad($fn);
		}else{
			$exp = explode('/', $this->url);
			$param = array_pop($exp);
			$url_to_uri = implode('/', $exp).'/';
			if ($fn = $routes[$request_method][$url_to_uri] ) {
				return self::diLoad($fn, $param);
			}
		}
	}

	public function processRoutes(array $routes)
	{
		
	}

	private function cache($dir = __DIR__)
	{
		return new class($dir){

			public function __construct($dir)
			{
				$this->dir = $dir;
			}
			public function get() {
				return @include $this->dir.'/tmp/route7.cache.php';
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


	public function run()
	{
		$this->rs[$this->request_method];

	}

	public function __call($method, $args)
	{
		$method = strtolower($method);
		$request_method = strtolower($_SERVER['REQUEST_METHOD']);
		$uri = strtolower($args[0]);
		if ($method === $request_method) {
			$call = $method.'Request';
			$this->$call( $uri, $args[1] );
		}
		/*
		switch($request_method) {
		 	case 'get':
				$this->getRequest($uri, $args[1]);
		 	break;
			case 'post':
				$this->postRequest($uri, $args[1]);
		 	break;
		 	case 'put':
				$this->putRequest($uri, $args[1]);
		 	break;
		 	case 'delete':
				$this->deleteRequest($uri, $args[1]);
		 	break;
		 	default:
				http_response_code(405);
		}
		*/
	}


	public function getRequest(string $uri, Callable $fn)
	{
		if (strpos($this->url, $uri) !== false) {
			$param = explode('/', $uri);
			dnd($param);

			$param_string = substr_replace( $this->url, '', 0, strlen($uri) );

			if ( rtrim($uri.$param_string , '/')  === rtrim($this->url, '/') ) {
				$param_array = explode('/', $param_string);
				self::diLoad($fn, $this->sanitize( $param_array) );
				exit;
			}
		}
	}

	public function postRequest(string $uri, Callable $fn){
		if (strpos($this->url, $uri) !== false) {
			$param_string = substr_replace( $this->url, '', 0, strlen($uri) );
			if ( rtrim($uri.$param_string , '/')  === rtrim($this->url, '/') ) {
				$param_array = explode('/', $param_string);
				self::diLoad($fn, $this->sanitize( $param_array) );
				exit;
			}
		}
	}

	public function putRequest(string $uri, Callable $fn)
	{
		if (strpos($this->url, $uri) !== false) {
			$param_string = substr_replace( $this->url, '', 0, strlen($uri) );
			if ( rtrim($uri.$param_string , '/')  === rtrim($this->url, '/') ) {
				$param_array = explode('/', $param_string);
				self::diLoad($fn, $this->sanitize( $param_array) );
				exit;
			}
		}
	}

	public function deleteRequest(string $uri, Callable $fn)
	{
		if (strpos($this->url, $uri) !== false) {
			$param_string = substr_replace( $this->url, '', 0, strlen($uri) );
			if ( rtrim($uri.$param_string , '/')  === rtrim($this->url, '/') ) {
				$param_array = explode('/', $param_string);
				self::diLoad($fn, $this->sanitize( $param_array) );
				exit;
			}
		}
	}

	protected static function diLoad(Callable $fn, $params = []){
		$builder = new DI\ContainerBuilder();
		$builder->enableCompilation(__DIR__ . '/tmp');
		$builder->writeProxiesToFile(true, __DIR__ . '/tmp/proxies');
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
}
*/