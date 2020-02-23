<?php
namespace Seven\Router;

/**
* @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
* @copyright MIT
*/

use Seven\Router\RouteParser;
use Seven\Router\Url;
use \DI;
use Seven\Vars\Strings;

final class Route
{
	private static ?Route $instance = null;
	private static bool $authorised = true;
	private static string $uri = '';
	private static string $controller = '';
	private static string $method = '';

	public static function get(string $uri, ?Callable $fn = null): Route
	{
		if (static::$instance === null) {
		 	static::$instance = new static();
		 	static::$uri = $uri;\
		 	self::$authorised = ( !is_null($fn) && call_user_func($fn) == true) ? true : false;
		}
	 	return static::$instance;
	}

	public static function load(Callable $fn)
	{
		if ( Strings::match( Url::get(), static::$uri, true) && self::$authorised ) {
			return static::diLoad($fn, static::$params);
		}
	}

	protected static function diLoad(Callable $fn, array $params = []){
		$builder = new DI\ContainerBuilder();
		$container = $builder->build();
		$container->call($fn, $params);
	}
}