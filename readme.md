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

$route = new Route();
$route->get('/users', function (){ //represents route that expects no parameter
	return ;
});
$route->get('/user/', function (){ //represents routes that expect one or more parameters
	return ;
});


```
Note: The difference between a route that expects a parameter and one that doesn't is the trailing slash in the route.