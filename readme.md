## About SevenRouter

=> SevenRouter is developed by Elisha Temiloluwa a.k.a TemmyScope	

SevenRouter is a fast but simple router

The usage of Router Class of this library looks something like this:

```php
use Seven\Router\Router;
use Seven\Router\Route;


$router = new Router( [
	'default_controller' => AuthController::class,
	'default_method' => "index",
	'namespace' => 'app\controller\\',
	'app_url' => 'http://localhost/alt-vel/',
]);

$router->requires( 
	function(){
		if (isset($_SESSION[CURRENT_USER_SESSION_NAME])) {
			return true;
		}
	}
)->call([
		'SearchController' => [],
		'HomeController' => [ 'index' ],
], [ app\controller\AuthController::class, "index"]);

$router->match([
	'AuthController' => [ 'index', 'login', 'register', 'forgot_password', 'activate', 'about', 'logout'],
	'ErrorsController' => ['_404', '_405', 'bad', 'denied', 'unknown']
]);
```




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