## About SevenRouter

	=> Seven Router is developed by Elisha Temiloluwa [ TemmyScope ]	

	=> The Library uses the PHP-DI dependency container to inject dependencies.

	=> The Library has been completely unit tested and is ready for use.

### Installation
```bash
composer require sevens/router
```

### PHP >=7.4 Performance Hack
	
	=> Use preloader to preload the cached route file after compilation on a production server.

### Seven\Router\Router: Usage


#### Initialize the class
```php
use \Seven\Router\Router;

#namespace: refers to the namespace from which the classes should be loaded
$route = new Router($namespace = 'App\Controllers');
```

#### Performance Optimization and Cache
	
	=> To Improve performance on a production server, you should enable cache.

***Downside: Whenever a new route is added, You have to delete the cache file 
	from the cache directory in order for the new route to be reloaded.***

```php
$route->enableCache($directory = __DIR__.'/cache');
```


#### Registering PSR-7 Compliant/Implementing Request & Response Objects
	
	=> This is an optional feature; You don't have to register PSR-7 request & response objects

```php
/**
* @param $request
* @param $response
* 
* @return void
*/
$route->registerProviders($request, $response);
```

#### Register middlewares you want to use later in your route calls:: All Callables are acceptable

```php
#register middlewares 
#E.g. for authentication, cors etc. using callables expecting the request, response, next
$route->middleware('cors', function($request, $response, $next){
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

#### The "next" is an object of the PSR-15 RequestHandlerInterface

	=> This means the handle method is available as well as it can be invoked like a function.

***Note: You can only use the "handle" method of the "next" object if you registered $request & $respnse objects 
	that implement PSR-7 Interface; Else, just call next as a function|closure i.e. next(request, response)***

```php
$route->middleware('auth', function($request, $response, RequestHandlerInterface $next){
 #do something with request or set headers for response
	
 #if required conditions are met do:
 $next->handle($request);
});
```

#### It is best when routes are defined in a different file
	=> route definition are included/required/loaded into the current file

	=> Note: routes can also be defined in the front controller (i.e. in your index.php);

```php
require __DIR__.'/routes.php';
```

#### The routing process starts here

	=> The "run" method processes routes and calls the appropriate action if the request succeeds.

```php
$route->run();
```

#### The route difinition in the route file that was required in the front controller e.g. index.php

	=> All Standard Http Methods are supported: GET, POST, PUT, OPTIONS, PATCH, HEAD, DELETE;
	
	=>All methods accept a callable as the second parameter 

	=>All callables are injected with the request & response objects that were previously registered

```php
$route->get('/post/:id/creator/:name', function($request, $response){

});
```

#### To make all requests to a certain endpoint return the same callable, use the "all" method

```php
$route->all('/posts', function($request, $response){
 return $response->send("This handles all requests to /posts endpoint, regardless of request method");
});
```

#### All params in uri are accessible through the request param object
```php
$route->put('/post/:key', function($request, $response){
 return $response->send("This is a request containing key: ". $request->param->key )
});
```

#### The "use" method is used to call registered middlewares before returning the endpoint's callable
	=> The middlewares are called in the order in which they were written/passed

	=> The second parameter passed to the "use" method must be a closure that accepts no parameter

```php
#cors middleware is called first in this case.
$route->use(['middleware' => ['cors', 'auth'],'prefix'=>'api' ], function() use ($route){
 $route->get('/post/:id', function($request, $response){
 
 });

 # request & response objects are passed as arguments automagically
 $route->post('/post', [ PostController::class, 'create' ]);

});
```

#### Shorthand for Use Keyword
	
	=> There is a shorthand way to use the "use" method (Of-course it is negligibly slower, if you're performance-anxious)

```php
$route->use('cors,auth;prefix:api;', function() use ($route){
 $route->get('/post/:id', function($request, $response){

 });
	
 # request & response objects are passed as arguments automagically
 $route->post('/post', [ PostController::class, 'create' ]);

});

#start with a ';' if no middleware is being used
$route->use(';prefix:api/test;', function() use ($route){

});
```

#### Apache - .HTACCESS
	
	=> An example .htaccess directive file fit for this router would look sth like this: 

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

#### NGINX Site Configuration Directive

	=> An example nginx configuration directive fits for this router would look sth like this: 

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

#### Example use Case In a real Life Applicatiion
```php
use Seven\Router\Router;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Auth;

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

#### Example use Case of PSR-7 Request-Response Handlers In an Applicatiion making use of Symfony/http-foundation

***Note: Not all use of PSR-7 compliants Request & Response handlers are this stressful.<br> 
This example is given as it might be the most complicated scenario use case.***

```php
use Seven\Router\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Auth;

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

$router->run( 
 $_SERVER['REQUEST_METHOD'], 
 $_SERVER['REQUEST_URI'] ?? $_SERVER['PATH_INFO'] ?? '/'
);
```

***Note: Routes without parameters are accessed faster that those that accept parameters***