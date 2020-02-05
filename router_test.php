<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	public function routes(array $controllers): void
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])  && 
			$this->authorised === true
		) {
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
		}
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
}<?php
namespace Seven\Router;

use \SplFixedArray;
use \Exception;

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
	private $authorised = true;
	private $namespace = "";
	private $controller;


	/**
	* constraint: The default controller must contain an index method (for fallback). 
	* and can not be restricted (i.e. can not require login)
	* @param <string> namespace
	* @param <String> default: the default controller 
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
	* @param <Array> sessions:name of sessions required to access the chained controller routes
	* <pre>
	* $sessions = [
	* (string) Session Name.
	* (string) Session Name.
	* ]
	* </pre>
	* @return <Router> returns object of this class for method chaining.
	*/

	public function allow(array $sessions): Router
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
	* @param Array $controllers: all the controllers with accessible sub array of endpoints/actions
	* 
	* <pre>
	* $controller = [
	*	'AccountController' => ['balance', 'index']
 	*	'ProfileController' => [ 'edit', 'index']
	* ]
	* </pre>
	* @return 
	*/
	public function routes(array $controllers)
	{
		if ( array_key_exists($this->controller[0] , $controllers ) && 
			in_array($this->controller[1], $controllers[ $this->controller[1] ])){

			if ($this->authorised === true) {
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
			} else {
				return false;
			}
		}
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