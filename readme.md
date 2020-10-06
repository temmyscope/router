## About SevenRouter

=> Seven Router is developed by Elisha Temiloluwa [ TemmyScope ]	

=> The Library uses the PHP-DI dependency container to inject dependencies.


# Installation
```bash
composer require sevens/router
```

# PHP >=7.4 Performance Hack
	
	=> Use preloader to preload the cached route file after compilation on a production server.

## Seven\Router\Route

	=> The Route Class is an extremely lightweight and simple fast router

In order to make the routes recompile and show newly added route(s), 
delete the already compiled route7.cache.php file from the directory 
you provided to the Route library, as well as all the files in the /tmp sub-directory.

Note: The difference between a route that expects a parameter and one 
that doesn't is the trailing slash in the route. 
E.g.:
/user/ =>represents a route thta expects a parameter/variable in the request url, such as /user/1
/user => represents the /user route and expects no parameteror variable

# The usage of the Router\Route looks something like this:

```php
use Seven\Router\Route;

require __DIR__.'/vendor/autoload.php';
//This accepts the namespace for the controllers that would be used.

$route = new Route('App\Controllers'); //Route::init('App\Controllers');

$route->enableCache(__DIR__.'/cache'); //comment this line on a development server

//If the two fallbacks are not set, the router handles both automatically
$route->setFallback(function(){
	return print_r(['error' => 404]);
}, Route::NOT_FOUND);

$route->setFallback(function(){
	return print_r(['error' => 405]);
}, Route::METHOD_NOT_ALLOWED);


$route->get('/', function(){
	echo 'The api is ready';
});

function show(){
	echo 'The version is 1';
}
$route->get('/version', 'show');

$route->get('/login', [ AuthController::class, "login" ]);
$route->post('/login', [ AuthController::class, "login" ]);
$route->get('/login', [ AuthController::class, "login" ]);
$route->post('/register', [ AuthController::class, "register" ] );
$route->get('/home',  [ HomeController::class, 'index' ]);


//note that when giving route groups name, 'default' is a reserved name in the library, so don't use it.
$route->group(['prefix' => '/api/restricted', 
				'name' => 'auth',
				//objects or variables can be manually injected from v3.0.0 as an array like the one below:
				'inject' => [$req, $res],
				//the midleware should be fully namespaced middleware and must expect a closure $next param
				'middleware' => [ App\Controllers\AuthController::class, "index"]
		], function($route){
	$route->get('/user', [ UserController::class, 'index' ]);
	$route->get('/search/', [ UserController::class, 'index' ]);
	$route->post('/user', [ UserController::class, 'index' ]);
	$route->get('/users', [ UserController::class, 'index' ]);
	$route->delete('/user/', [ UserController::class, 'delete' ]);
	$route->post('/user/add', [ UserController::class, 'add' ]);
});

//this is where the router actually decides which response to be returned
$router->run($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
//$router->run($_SERVER['REQUEST_METHOD'], $_SERVER['PATH_INFO'] ?? '/');
```

## Seven\Router\Router

# From Version 3.1.0, The more conventional Router Library has been added to the Seven\Router Library.
Although quite faster than most Router libraries, the Router Class handles request slower than the
Route Class which does not accept parameters.

# The usage of the Router\Router is explained below.


# Initialize the class
```php
	use \Seven\Router\Router;

	#namespace: refers to the namespace from which the classes should be loaded
	$route = new Router($namespace = 'App\Controllers');
```

# Performance Optimization and Cache
	
	=> To Improve performance on a production server, you should enable cache.

	=> Downside: Whenever a new route is added, You have to delete the cache file from 
	the cache directory in order for the new route to be reloaded.

```php
	$route->enableCache($directory = __DIR__.'/cache');
```


# Register PSR-7 Compliant/Implementing Request & Response Objects
	
	=> This is an optional feature; You don't have to register request & response objects

```php
	/**
	* @param $request
	* @param $response
	* 
	* @return void
	*/

	$route->registerProviders($request, $response);
```

# Register middlewares you want to use later in your route calls:: All Callables are acceptable

```php
	#register middlewares E.g. for authentication, cors etc. using callables expecting the request, response, next
	$route->middleware('cors', function($request, $response, $next){
		#do something with request or set headers for response
		$headers = [
      'Access-Control-Allow-Origin'      => '*',
      'Access-Control-Allow-Methods'     => '*',
      'Access-Control-Allow-Credentials' => 'true',
      'Access-Control-Max-Age'           => '86400',
      'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
	  ];
	  if ($request->isMethod('OPTIONS')){
	      return $response->json('{"method":"OPTIONS"}', 200, $headers);
	  }
	  foreach($headers as $key => $value){
	      $response->header($key, $value);
	  }
		#if required conditions are met do:
		$next($request, $response);
	});
```

# The "next" is an object of the PSR-15 RequestHandlerInterface

	=> This means the handle method is available as well as it can be invoked like a function.

	=> Note: You can only use the "handle" method of the "next" object if you registered $request & $respnse objects 
	that implement PSR-7 Interface; Else, just call next as a function|closure i.e. next(request, response)

```php
	$route->middleware('auth', function($request, $response, RequestHandlerInterface $next){
		#do something with request or set headers for response
		
		#if required conditions are met do:
		$next->handle($request);
	});
```

# It is best when routes are defined in a different file
	=> route definition are included/required/loaded into the current file

	=> Note: routes can also be defined in the front controller (i.e. in your index.php);

```php
	require __DIR__.'/routes.php'; 

```

# The routing process starts here

	=> The "run" method processes routes and calls the appropriate action if the request succeeds.

```php
	$route->run();

```


# The route difinition in the route file that was required in the front controller e.g. index.php

	=> All Standard Http Methods are supported: GET, POST, PUT, OPTIONS, PATCH, HEAD, DELETE;
	
	=>All methods accept a callable as the second parameter 

	=>All callables are injected with the request & response objects that were previously registered

```php
	$route->get('/post/:id/creator/:name', function($request, $response){

	});
```

# To make all requests to a certain endpoint return the same callable, use the "all" method
```php
	$route->all('/posts', function($request, $response){
		return $response->send("This handles all requests to /posts endpoint, regardless of request method");
	});
```

# All params in uri are accessible through the request param object
```php
	$route->put('/post/:key', function($request, $response){
			return $response->send("This is a request containing key: ". $request->param->key )
	});
```

# The "use" method is used to call registered middlewares before returning the endpoint's callable
	=> The middlewares are called in the order in which they were written/passed

	=> The second parameter passed to the "use" method must be a closure that accepts no parameter

```php
	#cors middleware is called first in this case.
	$route->use(['middleware' => ['cors', 'auth'],'prefix'=>'api' ], function() use ($route){}
		//shorthand: $route->use('cors,auth;prefix:api;', function() use ($route){
		$route->get('/post/:id', function($request, $response){

		});
		
		# request & response objects are passed as arguments automagically
		$route->post('/post', [ PostController::class, 'create' ]);

	});
```

# Shorthand for Use Keyword
	
	=> There is a shorthand way to use the "use" method (Of-course it is negligibly slower, if you're performance-anxious)

```php
	$route->use('cors,auth;prefix:api;', function() use ($route){
		$route->get('/post/:id', function($request, $response){

		});
		
		# request & response objects are passed as arguments automagically
		$route->post('/post', [ PostController::class, 'create' ]);

	});
```

# .HTACCESS
	
	=> An example .htaccess file that fits perfectly for this router would look sth like this: 

```htaccess

<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond $1 !^(cdn|robots.txt)
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>

```

# Example use Case of PSR-7 Routes In a real Life Applicatiion

```php
use Seven\Router\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Auth;


//use GuzzleHttp\Psr7\{Request, Response};

/*
|---------------------------------------------------------------------------|
| Register The Auto Loader 																									|
|---------------------------------------------------------------------------|
|
*/
require __DIR__.'/vendor/autoload.php';

$psr17Factory = new Psr17Factory();
$psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

$request = $psrHttpFactory->createRequest(Request::createFromGlobals());

$response = $psrHttpFactory->createResponse(new Response());

/**
* @package  SevenPHP
* @author   Elisha Temiloluwa <temmyscope@protonmail.com>
|-------------------------------------------------------------------------------|
|	SevenPHP by Elisha Temiloluwa a.k.a TemmyScope 																|
|-------------------------------------------------------------------------------|
*/

$router = new Router('App\Controllers');

//$router->enableCache(__DIR__.'/cache');

$router->registerProviders($request, $response);

$router->middleware('cors', function($request, $response, $next){
		$headers = [
	      'Access-Control-Allow-Origin'      => '*',
	      'Access-Control-Allow-Methods'     => '*',
	      'Access-Control-Allow-Credentials' => 'true',
	      'Access-Control-Max-Age'           => '86400',
	      'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
	  ];
	  if ($request->getMethod() === 'OPTIONS'){
	      return $response->send('{"method":"OPTIONS"}', 200, $headers);
	  }
	  foreach($headers as $key => $value){
	      $response->withHeader($key, $value);
	  }
		$next->handle($request);
});

$router->middleware('auth', function($request, $response, $next){
		$token = $request->getHeader('Authorization');
		if ( !$token || Auth::isValid($token) ) {
				return $response->send('Unauthorized.', 401);
		}
		$request->userId = Auth::getValuesFromToken($token)->user_id;
		$next->handle($request);
});

require __DIR__.'/routes/web.php';

$router->run();
```

# Example use Case In a real Life Applicatiion

```php
use Seven\Router\Router;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Auth;


//use GuzzleHttp\Psr7\{Request, Response};

/*
|---------------------------------------------------------------------------|
| Register The Auto Loader 																									|
|---------------------------------------------------------------------------|
|
*/
require __DIR__.'/vendor/autoload.php';

$request = Request::createFromGlobals();

$response = new Response();

/**
* @package  SevenPHP
* @author   Elisha Temiloluwa <temmyscope@protonmail.com>
|-------------------------------------------------------------------------------|
|	SevenPHP by Elisha Temiloluwa a.k.a TemmyScope 																|
|-------------------------------------------------------------------------------|
*/

$router = new Router('App\Controllers');

//$router->enableCache(__DIR__.'/cache');

$router->registerProviders($request, $response);

$router->middleware('cors', function($request, $response, $next){
		$headers = [
	      'Access-Control-Allow-Origin'      => '*',
	      'Access-Control-Allow-Methods'     => '*',
	      'Access-Control-Allow-Credentials' => 'true',
	      'Access-Control-Max-Age'           => '86400',
	      'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
	  ];
	  if ($request->isMethod('OPTIONS')){
	      return $response->send('{"method":"OPTIONS"}', 200, $headers);
	  }
	  foreach($headers as $key => $value){
	      $response->headers->set($key, $value);
	  }
		$next($request, $response);
});

$router->middleware('auth', function($request, $response, $next){
		$token = $request->getHeader('Authorization');
		if ( !$token || Auth::isValid($token) ) {
				return $response->send('Unauthorized.', 401);
		}
		$request->userId = Auth::getValuesFromToken($token)->user_id;
		$next->handle($request);
});

require __DIR__.'/routes/web.php';

$router->run();
```