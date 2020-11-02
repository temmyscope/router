<?php
require __DIR__.'/loader.php';

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Seven\Router\Router;
use Seven\Router\str_contains;

class RouterTest extends TestCase{

	public function testReturnedEndpoint()
	{
		$request = Request::create(
	    //'/hello-world',
	    '/post/1',
	    'GET',
	    ['name' => 'Fabien']
		);
		$request->overrideGlobals();

		$router = New Router('');

		$router->get('hello-world', function(){
			return "hello world";
		});
		$router->get('/post/:id', function($request, $response){
			return $request->params->id;
		});
		
		$result = $router->run();
		
		$this->assertEquals(1, $result);
		//$this->assertTrue( str_contains($result, 'hello') );

	}

}