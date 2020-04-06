## About SevenRouter

=> SevenRouter is developed by Elisha Temiloluwa [ TemmyScope ]	

SevenRouter is a fast but simple router

The Router class should not be used a router for API. It processes users-enetered url into an available 
previously defined endpoint.

The usage of Router Class of this library looks something like this:

```php
session_start();
use Seven\Router\Router;


$router = new Router( [
	'namespace' => 'App\Controllers',
	//default Controller & method as fallback
	'controller' => AuthController::class,
	'method' => "index",
]);

$router->call([
		'SearchController' => [],
		'HomeController' => [ 'index' ],
]);

$router->call([
	'AuthController' => [ 'index', 'login', 'register', 'forgot_password', 'activate', 'about', 'logout'],
	'ErrorsController' => ['_404', '_405', 'bad', 'denied', 'unknown']
]);
```

The average speed of the Route library is 0.04 secs per request.


The usage of the Route Class of this library looks something like this:
```php

use Seven\Router\Route;

require __DIR__.'/vendor/autoload.php';
$router = new Route('app\Controllers'); //This accepts the namespace for the controllers that would be used.

$router->get('/',  [ HomeController::class, 'index']);

$router->get('/login', [ AuthController::class, "login" ]);
$router->post('/login', [ AuthController::class, "login" ]);
$router->get('/login', [ AuthController::class, "login" ]);
$router->post('/register', [ AuthController::class, "register" ] );
$router->get('/home',  [ HomeController::class, 'index' ]);

$router->group(['prefix' => '/api'], function($router){

	$router->get('/search', [ SearchController::class, 'index' ]);
	$router->get('/search/', [ SearchController::class, 'index' ]);
	$router->post('/user', [ UserController::class, 'index' ]);
	$router->get('/users', [ UserController::class, 'index' ]);
	$router->put('/user/', [ UserController::class, 'update' ]);
	$router->delete('/user/', [ UserController::class, 'delete' ]);
	$router->post('/user/add', [ UserController::class, 'add' ]);

});

$router->run();


/*
Please note that the router does not currently support any closure instance, use anonymous function like the above

Note: The difference between a route that expects a parameter and one that doesn't is the trailing slash in the route. E.g.

/user/ =>represents a route thta expects a parameter/variable in the request url, such as /user/1
/user => represents the /user route and expects no parameteror variable
*/
```