<?php
namespace Seven\Router;

/**
 * @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
 * @copyright MIT
 *
*/

use \SplFixedArray;

final class RouteParser
{
	
	public static function build(string $default, string $method = 'index'): SplFixedArray
	{
		$url = (isset($_SERVER['PATH_INFO'])) ? explode('/', $_SERVER['PATH_INFO']) : [];
		array_shift($url);
		$controller = new SplFixedArray(3);
		if (!isset($url[0])) {
			$controller[0] = $default;
			$controller[1] = $method;
			$controller[2] = $url;
		} else {
			$controller[0] = ucfirst($url[0]).'Controller';
			array_shift($url);
			$controller[1] = $url[1] ?? 'index';
			array_shift($url);
			$controller[2] = $url;
		}
		return $controller;
	}
}