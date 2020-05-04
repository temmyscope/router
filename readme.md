## About SevenRouter

=> SevenRouter is developed by Elisha Temiloluwa [ TemmyScope ]	

SevenRouter is an extremely lightweight and simple fast router

The Router Class of this Library has been discontinued.

In order to make the routes recompile and show newly added route(s), delete the already compiled route7.cache.php file from the directory you provided to the Route library, as well as all the files in the /tmp sub-directory.


The usage of the Router looks something like this:
```php
use Seven\Router\Route;

require __DIR__.'/vendor/autoload.php';
//This accepts the namespace for the controllers that would be used. and the cache directory for the compiled routes
//both parameters are required

$route = new Route('App\Controllers'); //Route::init('App\Controllers', __DIR__.'/cache');

$router->enableCache(__DIR__.'/cache'); //comment this line on a development server

//If the two fallbacks are not set, the router handles both automatically
$router->setFallback(function(){
	return print_r(['error' => 404]);
}, Route::NOT_FOUND);

$router->setFallback(function(){
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
```

Note: The difference between a route that expects a parameter and one that doesn't is the trailing slash in the route. E.g.

/user/ =>represents a route thta expects a parameter/variable in the request url, such as /user/1
/user => represents the /user route and expects no parameteror variable