## About SevenRouter

=> SevenRouter is developed by Elisha Temiloluwa a.k.a TemmyScope	

SevenRouter is a fast but simple router

The usage of this library looks something like this:

```php
use Seven\Router\Router;
use Seven\Router\Route;
use app\Model\FileUploader;

Route::get('/home/')->inject([ new FileUploader()])->call( [ app\controller\HomeController::class, "index"]);

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