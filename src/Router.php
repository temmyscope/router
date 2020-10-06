<?php

namespace Seven\Router;

/**
* @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
* @package Seven Router Package
*/

use DI;
use Closure;
use SplFileObject;
use Seven\Vars\Strings;
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
    private $middleware = [];

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
    protected $routes = [];

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
                [ $routeParams, $parametized ] = $this->parseRoute($routeArray);
            } else {
                foreach ($args[0] as $value) {
                    $this->$method($value, $args[1]);
                }
                return;
            }
                
            $method = strtoupper($method);
            if (!$parametized) {
                $this->routes['u'][$method][$route] = [
                    'callable' => $this->serializeCallable($action), 'middlewares' => $this->middleware
                ];
            } else {
                $size = count($routeArray);
                $startPosition = (empty($routeArray[0])) ? '/' : $routeArray[0];
                $this->routes['p'][$method][$size][$startPosition] = [
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
                $request = $this->addParams($request, $this->params);
                return ($callable instanceof SerializableClosure) ?
                $callable($request, $response) : $this->call($callable, [$request, $response]);
        } else {
            $middleware = array_shift($this->routeMiddlewares);
            return $this->call(
                $middleware, [$request, $response, $this]
            );
        }
    }

    private function addParams($request, array $key_value)
    {
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
        $this->fileAddress = $directory . '/iroute7.cache.php';
        $this->cache = @include $this->fileAddress ?? false;
    }

    protected function findRouteMatch(string $uri, array $relatedRoutes): array
    {
        [ $uriArray, $size ] = $this->preProcessUri($uri);
        if (@$similarRoutes = $relatedRoutes[$size][$uriArray[0]]) {
            foreach ($similarRoutes as $key => $value) {
                if ($this->matchUriPatterns($value['params'], $uriArray, $value['route']) === true) {
                    return $value;
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
            $uriArray[$position] = $value;
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
    * @param array routeCollection
    *
    */
    protected function process(string $method, string $uri, array $routesCollection)
    {   
        if (@$route = $routesCollection['u'][$method][$uri] ?? $routesCollection['u']['all'][$uri]) {
            $this->setRouteCallable($route['callable']);
            $this->setRouteMiddlewares($route['middlewares']);
        } else {
            if (@$relatedRoutes = $routeCollection['p'][$method]) {
                $match = $this->findRouteMatch($uri, $relatedRoutes);
                if (empty($match)) {
                    header(sprintf('%s %s %s', $_SERVER['SERVER_PROTOCOL'], 404, "Not Found"), true, 404);
                    http_response_code(404);
                    echo"Resource not found."; 
                    return;
                }
                $this->setRouteCallable($match['callable']);
                $this->setRouteMiddlewares($match['middlewares']);
            } else {
                header(sprintf('%s %s %s', $_SERVER['SERVER_PROTOCOL'], 405, "Method Not Allowed"), true, 405);
                http_response_code(405);
                echo"Http Method not allowed.";
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

    public function retrieveCache($cache)
    {
        if ($cache === false) {
            $file = new SplFileObject($this->fileAddress, 'a');
            $file->fwrite("<?php return " . var_export($cache, true) . ";");
        }
        return $cache;
    }

    protected function routesCollection(): array
    {
        return ($this->cache === false) ? $this->routes : $this->retrieveCache($this->cache);
    }

    public function run()
    {
        return $this->process(
            $_SERVER['REQUEST_METHOD'], strtolower($_SERVER['PATH_INFO']), $this->routesCollection()
        );
    }

    public function serializeCallable($callable): string
    {
        if ($callable instanceof \Closure) {
            $callable = new SerializableClosure($callable);
        }
        if (is_array($callable)) {
            $callable = [ $this->namespace . '\\' . $callable[0], $callable[1] ];
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
        return [strtolower($args[0]), explode('/', $args[0]), $args[1] ];
    }

    public function use($middles, \Closure $next)
    {
        if (is_array($middles)) {
            $this->prefix = $middles['prefix'];
            $this->middleware = $middles['middleware'];
        } else {
            $break = explode(';', $middles);
            $this->middleware = explode(',', $break[0]);
            $this->prefix = str_replace('prefix:', '', $break[1] ?? "");
        }
        $next();
    }
}
