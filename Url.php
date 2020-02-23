<?php
namespace Seven\Router;

/**
 * @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
 * @copyright MIT
 *
*/

final class Url
{
	
	public static function get(): String
	{
		return (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : "";
	}
}