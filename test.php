<?php
/**
 * @example 
 * $router = new Router("app\controller", "DefaultController");
 * $router->allow([ 'id', 'token' ])->routes([ 
 *	'AccountController' => ['balance', 'index'] 
 *	'ProfileController' => [ 'edit', 'index']
 * ]); //for routes that have e.g. isset($_SESSION['id']) && isset($_SESSION['token']) as TRUE
 * $router->routes([ "DefaultController" => [] ]) //for controllers that don't require any sessions
 

use Seven\Router\Router;
use Seven\Router\Route;


Route::get('/home')->inject(Route::getParams())->load( [ app\controller\HomeController::class, "index"]);

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
)->
call([
		'SearchController' => [],
		'HomeController' => [ 'index' ],
], [ app\controller\AuthController::class, "index"]);

$router->match([
	'AuthController' => [ 'index', 'login', 'register', 'forgot_password', 'activate', 'about', 'logout'],
	'ErrorsController' => ['_404', '_405', 'bad', 'denied', 'unknown']
]);
