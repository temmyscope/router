<?php
require __DIR__.'/loader.php';

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Seven\Router\Router;
use Seven\Router\str_contains;

class RouterTest extends TestCase{

	public function testReturnedEndpoint()
	{
		$router = New Router('');

		$router->get('/', function($request, $response){
			return "/";
		});

		$router->get('hello-world', function($request, $response){
			return "world";
		});
		$router->get('/post/:id', function($request, $response){
			return $request->params->id;
		});

		$router->use(';prefix:api/authorized', function() use ($router){

			$router->get('/', function($request, $response){
				return 0;
			});
			
			$router->get('user/:id/post/:key/comment/:commentId', function($request, $response){
				return [ $request->params->id, $request->params->key, $request->params->commentId ];
			});

			$router->get('/post/:id/comment/:id', function($request, $response){
				return $request->params->id;
			});

			$router->get('/post/all/:id/comment/:id', function($request, $response){
				return $request->params->id;
			});

			$router->get('/post/:id', function($request, $response){
				return $request->params->id;
			});

		});
		
		$this->assertEquals(0, $router->run('GET', '/api/authorized/'));
		$this->assertEquals('world', $router->run('GET', '/hello-world'));
		$this->assertTrue( is_array($router->run('GET', '/api/authorized/user/1/post/A3sE5u7Ci/comment/56')) );
	}

}