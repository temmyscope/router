<?php
/**
 * @example 
 * $router = new Router("app\controller", "DefaultController");
 * $router->allow([ 'id', 'token' ])->routes([ 
 *	'AccountController' => ['balance', 'index'] 
 *	'ProfileController' => [ 'edit', 'index']
 * ]); //for routes that have e.g. isset($_SESSION['id']) && isset($_SESSION['token']) as TRUE
 * $router->routes([ "DefaultController" => [] ]) //for controllers that don't require any sessions
 