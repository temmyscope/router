<?php
namespace Seven\Router;

/**
* @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
* @copyright MIT
*/

use \DI;

trait DITrait
{
	protected static function diLoad(Callable $fn, $params = []){
		$builder = new DI\ContainerBuilder();
		$container = $builder->build();
		$container->call($fn, $params);
	}
	
}