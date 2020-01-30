<?php
Namespace Seven\Router;

use \SplFixedArray;
use \Exception;

class Router{

	private $authorised = true;

	/**
	* @param <String> default: the default controller [constraint: The controller must contain an index method]
	* note: your default controller can not be Authenticatable i.e. can not be access restricted
	*/
	public function __construct(string $namespace, string $default)
	{
		$this->namespace = $namespace.'\\';
		$url = (isset($_SERVER['PATH_INFO'])) ? explode('/', $_SERVER['PATH_INFO']) : [];
		array_shift($url);
		$url = $this->sanitize($url);
		$this->controller = new SplFixedArray(3);
		if ( isset($url[0]) ) {
			$controller = ucfirst(substr_replace($url[0], '', strcspn($url[0], '.'))).'Controller';
		}else{
			$controller = $default;
		}
		$this->controller[0] = $controller;
		$this->controller[1] = $url[1] ?? 'index';
		$this->controller[2] = $url[2] ?? [];
	}

	/**
	* @param <Array> sessions: all the sessions must be set in order for a user to have access to the passed controllers
	*/

	public function allow(array $sessions)
	{
		foreach ($sessions as $key) {
			if (!isset($_SESSION[$key])) {
				$this->authorised = false;
				break;
			}
		}
		return $this;
	}

	/**
	* @param <Array> controllers: all the controllers with accessible sub array of endpoints/actions
	*/
	public function routes(array $controllers)
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && $this->authorised === true) {
			try {
				$controller = $this->namespace.$this->controller[0];
				$builder = new \DI\ContainerBuilder();
				$builder->enableCompilation(__DIR__ . '/tmp');
				$builder->writeProxiesToFile(true, __DIR__ . '/tmp/proxies');
				$builder->useAnnotations(false);
				$container = $builder->build();
				$container->call([ $controller, $this->controller[1] ], $this->controller[2]);
				unset($container);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}else{
			echo("access denied");
		}
	}

	final private function sanitize(array $dirty){
		$clean_input = [];
    	foreach ($dirty as $k => $v) {
            if ($v != '') {
				$clean_input[$k] = htmlentities($v, ENT_QUOTES, 'UTF-8');
			}
        }
        return $clean_input;
  	}
}