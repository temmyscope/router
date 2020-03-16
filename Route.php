<?php
namespace Seven\Router;

/**
* @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
* @copyright MIT
*/
use Seven\Router\DITrait;
use Seven\Vars\Strings;

final class Route
{
	use DITrait;

	private static /*?Route*/ $instance = null;
	private static /*bool*/ $authorised = true;
	private static /*string*/ $uri = '';
	private static /*string*/ $params = [];

	public static function get(string $uri, ?Callable $fn = null): Route
	{
		if (static::$instance === null) {
		 	static::$instance = new static();
		 	static::$uri = $uri;
		 	if (!is_null($fn)) {
		 		self::$authorised = ( call_user_func($fn) == true) ? true : false;
		 	} else {
		 		self::$authorised =  true;
		 	}
		}
	 	return static::$instance;
	}

	public static function call(Callable $fn, ?Callable $fallback = null)
	{
		$url = self::getUrl();
		if ( Strings::startsWith( $url, static::$uri, true)){
			if( static::$authorised ) {
				return static::diLoad($fn, array_merge( static::$params, static::getParams($url, static::$uri)) );
			}else{
				if (is_callable($fallback)) {
					return static::diLoad($fallback);
				}
			}
		}
	}

	public static function load(Callable $fn, ?Callable $fallback = null)
	{
		if ( Strings::match( self::getUrl(), static::$uri, true)){
			if( static::$authorised ) {
				return static::diLoad($fn, static::$params );
			}else{
				if (!is_null($fallback)) {
					return static::diLoad($fallback);
				}
			}
		}
	}

	public static function inject(array $args): Route
	{
		static::$params = $args;
		return static::$instance;
	}

	protected function getParams($url, $uri): array
	{
		return static::sanitize( explode( '/', substr_replace( $url, '', 0, strlen($uri) ) ) );
	}

	protected static function getUrl(): String
	{
		return (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : "/";
	}
}