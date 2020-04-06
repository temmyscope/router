## About SevenRouter

=> SevenRouter is developed by Elisha Temiloluwa [ TemmyScope ]	

SevenRouter is a fast but simple router

The Router class should not be used a router for API. It processes users-enetered url into an available 
previously defined controller & endpoint.

The usage of Router Class of this library looks something like this:

```php
session_start();
use Seven\Router\Router;

require __DIR__.'/vendor/autoload.php';

$router = new Router([
	'namespace' => 'App\Controllers',
	//default Controller & method as fallback
	'controller' => AuthController::class,
	'method' => "index"
]);
if (($_SESSION['id'])) { //To restricts certain routes from being accessed without a certain session set
	$router->call([
		'SearchController' => [],
		'HomeController' => [ 'index' ],
		'AccountController' => ['balance', 'index'],
 	*	'ProfileController' => [ 'edit', 'index']
	]);
} else {
	$router->call([
		'AuthController' => [ 'index', 'login', 'register', 'forgot_password', 'activate', 'about', 'logout'],
		'ErrorsController' => ['_404', '_405', 'bad', 'denied', 'unknown']
	]);
}
```


Note: The difference between a route that expects a parameter and one that doesn't is the trailing slash in the route. E.g.

/user/ =>represents a route thta expects a parameter/variable in the request url, such as /user/1
/user => represents the /user route and expects no parameteror variable

The average speed of the Route library is 0.04 for up to 60 routes secs per request.
In order to make the routes recompile and show newly added route(s), delete the already compiled route7.cache.php file from the directory you provided to the Route library, as well as all the files in the /tmp sub-directory.

The usage of the Route Class of this library looks something like this:
```php
use Seven\Router\Route;

require __DIR__.'/vendor/autoload.php';
//This accepts the namespace for the controllers that would be used. and the cache directory for the compiled routes
//both parameters are required

$router = new Route('app\Controllers', __DIR__.'/cache');

//Please note that the router does not currently support any closure instance, use standard functions
//i haven't found a way to cache closures and maintain their 'eval-ability' without suffering a high lag in performance.
function show(){
	echo 'The version is 1';
}

$router->get('/', 'show');

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

$router->run(); //this is where the router actually starts routes request

```