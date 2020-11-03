<?php

namespace Seven\Router;

/**
* @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
* @package Seven Router Package
*/

use DI;
use Closure;
use Seven\Router\str_contains;
use Opis\Closure\SerializableClosure;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\{ ServerRequestInterface, ResponseInterface };

class Router implements RequestHandlerInterface
{
    /**
    * @property [] app
    * all the middlewares used in the app router
    */
    protected $app = [];

    /**
    * @property [] | bool cache
    * contains array of routes loaded from cache, if not available fallbacks to cache
    */
    protected $cache = false;

    /**
    * @property callable
    */
    protected $callableAction;

    /**
    *
    * @property null | string
    */

    protected $fileAddress = null;

    /**
    * @property [] $middleware
    */
    protected $middleware = [];

    /**
    * @property string $namespace
    */
    protected $namespace = "";

    /**
    * @property [] params
    * refers to the key value pairs of expected parameter name as key and value passed as associated value
    */
    protected $params = [];

    /**
    *  @property string prefix
    * route prefix
    */
    protected $prefix = "";

    /**
    * @property [] routes
    * all the processed routes
    */
    public $routes = [];

    /**
    * @property callable[]
    * array of middlewares allocated to the current route in the order in which they were allocated.
    */
    protected $routeMiddlewares = [];

    /**
    * @property object $request
    */
    protected $request;

    /**
    * @property object $request
    */
    protected $response;


    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
    * @method __call
    * responds to GET, POST, PUT, DELETE, OPTIONS, HEAD, PATCH
    *
    * @return void
    */
    public function __call($method, $args)
    {
        if ($this->cache === false) {
            if (is_string($args[0])) {
                [ $route, $routeArray, $action ] = $this->unpackRequest($args);
                [ $parametized, $routeParams ] = $this->parseRoute($routeArray);
            } else {
                foreach ($args[0] as $value) {
                    $this->$method($value, $args[1]);
                }
                return;
            }
            $method = strtoupper($method);
            if (!$parametized) {
                $this->routes['u'][$method][rtrim($route, '/')] = [
                    'callable' => $this->serializeCallable($action), 'middlewares' => $this->middleware
                ];
            } else {
                $size = count($routeArray);
                $startPosition = (empty($routeArray[0])) ? '/' : $routeArray[0];
                $this->routes['p'][$method][$size][$startPosition][] = [
                    'callable' => $this->serializeCallable($action), 'middlewares' => $this->middleware,
                    'params' => $routeParams,  'route' => $routeArray
                ];
            }
        }
    }

    public function __invoke($request, $response)
    {
        if (empty($this->routeMiddlewares)) {
                $callable = $this->prepareCallable($this->callable);
                if (!empty($this->params)) {
                    $request = $this->addParams($request, $this->params);
                }
                return ($callable instanceof SerializableClosure) ?
                $callable($request, $response) : $this->call($callable, [$request, $response]);
        } else {
            $middleware = array_shift($this->routeMiddlewares);
            return $this->call(
                $middleware,
                [$request, $response, $this]
            );
        }
    }

    private function addParams($request, array $key_value)
    {
        $request = (is_object($request)) ? $request : new \stdClass();
        $request->params = new \stdClass();
        foreach ($key_value as $key => $value) {
            $request->params->$key = $value;
        }
        return $request;
    }

    /**
    * @method call
    * @param callable $callable
    * @param [] $params
    *
    * @return mixed
    */
    protected function call(callable $callable, $params)
    {
        $builder = new DI\ContainerBuilder();
        $builder->enableCompilation(__DIR__ . '/../../../cache/tmp');
        $builder->writeProxiesToFile(true, __DIR__ . '/../../../cache/tmp/proxies');
        $container = $builder->build();
        $container->call($callable, $params);
    }

    /**
    * @method enableCache
    *
    * @param string $directory
    *
    * @return void
    */
    public function enableCache($directory): void
    {
        $this->fileAddress = rtrim($directory, DIRECTORY_SEPARATOR) . '/iroute7.cache.php';
        @$this->cache = include $this->fileAddress;
    }

    protected function findRouteMatch(string $uri, array $relatedRoutes): array
    {
        [ $uriArray, $uriSize ] = $this->preProcessUri($uri);
        $routes = $relatedRoutes[$uriSize] ?? [];
        $routeSize = count($routes);
        if ($routeSize > 0) {
            if ($uriSize === 1 && $routeSize === 1) {
                if ($this->matchUriPatterns($routes[0]['params'], $uriArray, $routes[0]['route']) === true) 
                    return $routes[0];
            }
            if (@$similarRoutes = $routes[$uriArray[0]]) {
                foreach ($similarRoutes as $key => $value) {
                    if ($this->matchUriPatterns($value['params'], $uriArray, $value['route']) === true) {
                        return $value;
                    }
                }
            }
            
        }
        return [];
    }

    /**
    * @method handle
    * @param  ServerRequestInterface $request
    * @return ResponseInterface
    * implemetation of PSR-15 RequestHandlerInterface
    */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this($request, $this->response);
    }
    
    /**
    * @method matchUriPatterns
    *
    * @param [] $paramKeyValuePairs
    * @param [] $uriArray
    * @param [] $routeArray
    *
    * @return bool
    */
    protected function matchUriPatterns(array $paramKeyValuePairs, array $uriArray, array $routeArray): bool
    {
        $params = [];
        foreach ($paramKeyValuePairs as $position => $value) {
            $params[$value] = $uriArray[(int)$position];
            $uriArray[$position] = ':' . $value;
        }
        if ($uriArray === $routeArray) {
            $this->setParams($params);
            return true;
        }
        return false;
    }

    /**
    * @method middleware
    *
    * @param string $name
    * @param callable $middleare
    *
    * @return void
    */
    public function middleware(string $name, callable $middleware)
    {
        $this->app[$name] = $middleware;
    }

    /**
    * @method parseRoute
    *
    * @param [] $routeArray
    *
    * @return array
    */
    protected function parseRoute(array $routeArray): array
    {
        $parametized = false;
        $routeParams = [];
        foreach ($routeArray as $position => $value) {
            if (str_contains($value, ':')) {
                $routeParams[$position] = str_replace(':', '', $value);
                $parametized = true;
            }
        }
        return [$parametized, $routeParams];
    }

    /**
    * @method prepareCallable
    * @param string $entry
    *
    * @return callable
    */
    public function prepareCallable(string $entry)
    {
        return unserialize($entry);
    }

    /**
    * @method preProcessUri
    *
    * @param string $uri
    *
    * @return array routeCollection
    */
    protected function preProcessUri(string $uri): array
    {
        $uriArray = explode('/', $uri);
        return [$uriArray, count($uriArray)];
    }

    /**
    * @method process
    *
    * @param string $method
    * @param string $uri
    * @param array routesCollection
    *
    */
    protected function process(string $method, string $uri, array $routesCollection)
    {
        if (@$route = $routesCollection['u'][$method][$uri] ?? $routesCollection['u']['ALL'][$uri]) {
            $this->setRouteCallable($route['callable']);
            $this->setRouteMiddlewares($route['middlewares']);
        } else {
            if (@$relatedRoutes = $routesCollection['p'][$method]) {
                $match = $this->findRouteMatch($uri, $relatedRoutes);
                if (empty($match)) {
                    header(
                        sprintf('%s %s %s', $_SERVER['SERVER_PROTOCOL'] ?? "HTTP/1.1", 404, "Not Found"),
                        true,
                        404
                    );
                    http_response_code(404);
                    echo"Resource not found.";
                    return;
                }
                $this->setRouteCallable($match['callable']);
                $this->setRouteMiddlewares($match['middlewares']);
            } else {
                header(
                    sprintf('%s %s %s', $_SERVER['SERVER_PROTOCOL'] ?? "HTTP/1.1", 405, "Method Not Allowed"), 
                    true,
                    405
                );
                http_response_code(405);
                echo"Http Method not allowed For requested resource.";
                return;
            }
        }
        return $this($this->request, $this->response);
    }

    public function registerProviders($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected function routesCollection(): array
    {
        if ($this->fileAddress === null) {
            return $this->routes;
        } else {
            if ($this->cache === false) {
                return $this->saveCache($this->routes);
            }
            return $this->cache;
        }
    }

    public function run(string $method, string $uri)
    {
        $uri = strtok($uri, '?');
        $uri = strtolower(trim($uri, '/'));

        return $this->process(
            $method, $uri, $this->routesCollection()
        );
    }

    public function saveCache($routes)
    {
        file_put_contents($this->fileAddress, "<?php return " . var_export($routes, true) . ";");
        return $routes;
    }

    public function serializeCallable($callable): string
    {
        if ($callable instanceof \Closure) {
            $callable = new SerializableClosure($callable);
        } elseif (is_array($callable)) {
            $callable[0] = $this->namespace . '\\' . $callable[0];
        }
        return serialize($callable);
    }

    protected function setParams($params): void
    {
        $this->params = $params;
    }

    protected function setRouteMiddlewares(array $routeMiddlewares = []): void
    {
        $middlewares = [];
        if (!empty($routeMiddlewares)) {
            foreach ($routeMiddlewares as $key => $value) {
                $middlewares[] = $this->app[$value];
            }
        }
        $this->routeMiddlewares = $middlewares;
    }

    protected function setRouteCallable($routeCallable): void
    {
        $this->callable = $routeCallable;
    }

    protected function unpackRequest($args): array
    {
        $uri = $this->prefix . ltrim($args[0], '/');
        return [ strtolower($uri), explode('/', $uri), $args[1] ];
    }

    public function use($middles, \Closure $next)
    {
        if ($this->cache === false) {
            if (is_array($middles)) {
                $this->prefix = (
                    isset($middles['prefix']) && !empty($middles['prefix'])
                ) ? $middles['prefix'].'/' : "";

                foreach ($middles['middleware'] as $key => $value) {
                    $this->middleware[] = $value;
                }
            } else {
                $break = explode(';', $middles);
                if (!empty($break[0])) {
                    $middlewares = explode(',', $break[0]);
                    foreach ($middlewares as $key => $value) {
                        $this->middleware[] = $value;
                    }
                }
                $prefix = str_replace('prefix:', '', $break[1] ?? "");
                $this->prefix = (!empty($prefix)) ? $prefix.'/' : "";
            }
            $next();
            $this->prefix = "";
        }
    }
}
