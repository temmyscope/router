## About SevenRouter

=> SevenRouter is developed by Elisha Temiloluwa a.k.a TemmyScope	

SevenRouter is a fast but simple router

The usage of this library looks something like this:

use Seven\Router\Router;

$object = new Router($controller_namespace, $default_controller);
$router = new Router("app\controller", DEFAULT_CONTROLLER);


$object->routes([
	an array of controllers and their available endpoints/methods
]);

$router->allow([ CURRENT_USER_SESSION_NAME ])->routes([
		'SearchController' => [],
		'HomeController' => [],
]);

$router->routes([
	'AuthController' => ['login', 'register', 'forgot_password', 'activate', 'about', 'logout'],
	'ErrorsController' => ['_404', '_405', 'bad', 'denied', 'unknown']
]);