<?php
require __DIR__.'/loader.php';

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Seven\Router\Router;
use Seven\Router\str_contains;

class RouterTest extends TestCase{

	public function setUp()
	{
		$request = Request::create(
	    '/hello-world',
	    'GET',
	    ['name' => 'Fabien']
		);
		$request->overrideGlobals();
	}

	public function testMiddlewareStack()
	{
		
	}

	public function testReturnedEndpoint()
	{
		$router = New Router('');
		$ret = $router->get('hello-world', function(){
			return "hello world";
		});
		$router->run();
		$this->assertTrue( str_contains($ret, 'hello world') );
	}

	public function test()
	{
		
	}

}