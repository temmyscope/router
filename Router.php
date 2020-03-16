<?php
namespace Seven\Router;

use \Exception;
use Seven\Router\DITrait;
/**
 * @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
 * @copyright MIT
 *
*/

class Router{

	use DITrait;

	/**
	* @var bool $authorised: Allows or restricts users' access to defined controllers
	* @var string $namespace
	* @var Array $controller
	*/
	public /*bool*/ $authorised = true;
	private /*string*/ $controller;
	private /*string*/ $method;
	private /*array*/ $params;
	/*
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default route

	$config = [
		'namespace' => , //namespace for controllers
		'app_url' => , //base url for the application
		'default_controller' => , //the default controller to use use when no controller could be accessed
		'default_method' => , 
	];
	*/

	public function __construct(Array $config)
	{
		$this->config = $config;
		[$this->controller, $this->method, $this->params] = $this->routeParser(
			$config['default_controller'], $config['default_method'] ?? 'index'
		);
		$this->params = self::sanitize($this->params);
	}

	/**
	* @param Callable $fn that authenticates api request and must return TRUE if authentication and authorization was successful.
	* @return <Router> returns object of this class for method chaining.
	*/

	public function requires(Callable $fn)
	{
		$this->authorised = ( $fn() === true) ? true : false;
		return $this;
	}

	/**
	* @param Array $controllers: all the controllers with accessible sub array of endpoints/actions
	* 
	* <pre>
	* $controller = [
	*	'AccountController' => ['balance', 'index']
 	*	'ProfileController' => [ 'edit', 'index']
	* ]
	* </pre>
	* @return void
	*/

	public function call(array $controllers, ?Callable $fallback = null)
	{
		if ( $this->defined($controllers, $this->controller, $this->method) && $this->authorised){
			self::diLoad([ $this->getConfig('namespace').$this->controller, $this->method ], $this->params);
		}else{
			if (!is_null($fallback)) {
				self::diLoad($fallback);
			}
		}
		$this->authorised = true;
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
}