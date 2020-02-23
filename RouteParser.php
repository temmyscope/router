<?php
namespace Seven\Router;

/**
 * @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
 * @copyright MIT
 *
*/

final class RouteParser
{
	
	public static function build(): Array
	{
		$url = (isset($_SERVER['PATH_INFO'])) ? explode('/', $_SERVER['PATH_INFO']) : [];
		array_shift($url);
		$controller = [];
		foreach ($url as $key => $value) {
			$controller[$key] = $value;
		}
		return $controller;
	}
}