<?php
require __DIR__.'/../src/Router.php';

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Seven\Router\Router;

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

	public function test($value='')
	{
		# code...
	}

	public function testReturnedEndpoint()
	{
		
	}

	public function test()
	{
		
	}

}