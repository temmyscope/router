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
		$builder->enableCompilation(__DIR__ . '/tmp');
		$builder->writeProxiesToFile(true, __DIR__ . '/tmp/proxies');
		$builder->useAnnotations(false);
		$container = $builder->build();
		$container->call($fn, $params);
	}

	private function sanitize(array $dirty){
		$clean_input = [];
    	foreach ($dirty as $k => $v) {
            if ($v != '') {
            	$clean_input[$k] = htmlentities($v, ENT_QUOTES, 'UTF-8');
            }
        }
        return $clean_input;
  	}
	
}