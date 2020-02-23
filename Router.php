<?php
namespace Seven\Router;

use \Exception;
use Seven\Router\RouteParser;
use \DI;
/**
 * @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
 * @copyright MIT
 *
*/

class Router{

	/**
	* @var bool $authorised: Allows or restricts users' access to defined controllers
	* @var string $namespace
	* @var Array $controller
	*/
	private bool $authorised = true;
	private string $namespace;
	private string $controller;
	private string $method;
	private array $params;
	private string $uri;
	private string $default;

	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
	*/

	public function __construct(string $namespace, string $default)
	{
		$this->namespace = $namespace.'\\';
		$this->uri = RouteParser::build();
		[$this->controller, $this->method, $this->params] = $this->parsed($this->uri);
		$this->params = $this->sanitize($this->params);
		$this->default = ucfirst($default)."Controller";
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
	public function match(array $controllers): void
	{
		if ( $this->authorised && $this->defined($controllers, $this->controller, $this->method) ){
			try {
				$this->diLoad([ $this->namespace.$this->controller, $this->method ], $this->params);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
	}

	public function call(array $controllers): void
	{
		if ($this->defined($controllers, $this->controller, $this->method) ){
			try {
				$this->diLoad([ $this->namespace.$this->controller, $this->method ], $this->params);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}
	}

	protected function defined(array $controllers, string $controller, string $method): bool
	{
		return (array_key_exists($controller , $controllers ) && in_array($method, $controllers[$controller])) ? true : false;
	}

	protected function parse(Array $uri = []): Array
	{
		$parsed = new SplFixedArray(3);
		$parsed[0] = (isset($uri[0])) ? ucfirst($uri[0]).'Controller' : $this->default;
		$parsed[1] = $uri[1] ?? 'index';
		$parsed[2] = $uri[2] ?? [];
		return $parsed;
	}

	protected function diLoad(Callable $fn, array $params = []){
		$builder = new DI\ContainerBuilder();
		$container = $builder->build();
		$container->call($fn, $params);
		unset($container);
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