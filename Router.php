<?php
namespace Seven\Router;

use \Exception;
use Seven\Router\RouteParser;
use Seven\Router\Route;
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
	private /*bool*/ $authorised = true;
	private /*string*/ $controller;
	private /*string*/ $method;
	private /*array*/ $params;
	private /*string*/ $uri;
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
		[$this->controller, $this->method, $this->params] = RouteParser::build(
			$config['default_controller'], $config['default_method'] ?? 'index'
		);
		$this->params = $this->sanitize($this->params);
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
	public function match(array $controllers)
	{
		if ($this->defined($controllers, $this->controller, $this->method) ){
			if ($this->authorised) {
				return self::diLoad([ $this->getConfig('namespace').$this->controller, $this->method ], $this->params);
			}else {
				return self::diLoad([ $this->getConfig('namespace').$this->getConfig('default_controller'), $this->getConfig('default_method') ], $this->params);
			}
		}
	}

	public function call(array $controllers, Callable $fn)
	{
		if ($this->defined($controllers, $this->controller, $this->method) ){
			if ($this->authorised) {
				return self::diLoad([ $this->getConfig('namespace').$this->controller, $this->method ], $this->params);
			}else {
				return self::diLoad($fn);
			}
		}
	}

	protected function getConfig(string $var)
	{
		return $this->config[$var];
	}

	protected function defined(array $controllers, string $controller, string $method): bool
	{
		return (array_key_exists($controller , $controllers ) && in_array($method, $controllers[$controller])) ? true : false;
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