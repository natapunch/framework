<?php
namespace Punchenko\Framework\Router;

use Punchenko\Framework\Request\Request;
use Punchenko\Framework\Router\Exceptions\RouteMethodNotFoundException;
use Punchenko\Framework\Router\Exceptions\RouteNameNotFoundException;
use Punchenko\Framework\Router\Exceptions\RouteNotFoundException;

/**
 * Class Router
 * @package Punchenko\Framework\Router
 */
class Router
{
    const DEFAULT_VAR_REGEXP = '[^\/]+';

    /**
     * @var array
     * Routing map
     */
    private $routes = [];

    /**
     * Router constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            $existed_variables = $this->getExistedVariables($value);
            $this->routes[$key] = [
                "origin" => $value["pattern"],
                "regexp" => $this->getRegexpFromRoute($value, $existed_variables),
                "method" => isset($value["method"]) ? $value["method"] : "GET",
                "controller_name" => $this->getControllerName($value),
                "controller_method" => $this->getControllerMethod($value),
                "variables" => $existed_variables
            ];
        }
    }

    /**GET CURRENT ROUTE OBJECT
     *Get current route object
     * check uri by regexp and method
     * @param Request $request
     * @return Route
     * @throws RouteMethodNotFoundException
     * @throws RouteNotFoundException
     * @internal param string $method
     */
        public function getRoute(Request $request): Route
        {
            $uri = $request->getUri();
            $method = $request->getMethod();
            foreach ($this->routes as $name => $route) 
            {
                if (preg_match_all('/' . $route['regexp'] . '/', $uri, $matches)) {
                    if ($route['method'] == $method){
                        $controller = $route['controller_name'];
                        $method = $route['controller_method'];
                        $params = isset($route['variables']) ? (array_combine($route['variables'], array_slice($matches, 1))) : [];
                        return new Route($name, $controller, $method, $params);
                    }
                    throw new RouteMethodNotFoundException("Route method not found");
                }
            }
            throw new RouteNotFoundException("Route not found");
        }
    /**
     * Returns name of controller
     * @param array $config
     * @return array
     */
    private function getControllerName($config): array
    {
        return explode("@", $config["action"])[0];
    }

    /**
     * Return name of controller method
     * @param $config
     * @return array
     * @internal param array $config
     */
    private function getControllerMethod($config): array
    {
        return explode("@", $config["action"])[1];
    }

    /**
     * Returns regexp by config
     *
     * @param array $config
     * @param array $existed_variables
     * @return string
     * @internal param array $config
     */
    private function getRegexpFromRoute(array $config, array $existed_variables): string
    {
        $pattern = $config["pattern"];
        $result = str_replace("/", '\/', $pattern);


        $variables_names = $existed_variables;

        for ($i = 0; $i < count($variables_names); $i++) {
            $var_reg = "(" .
                (array_key_exists($variables_names[$i], $config["variables"])
                    ? $config["variables"][$variables_names[$i]]
                    : self::DEFAULT_VAR_REGEXP
                )
                . ")";
            $result = str_replace("{" . $variables_names[$i] . "}", $var_reg, $result);

        }

        return "^" . $result . "$";
    }

    /**
     * Returns all variables that exist in pattern
     *
     * @param $config
     * @return array
     */
    private function getExistedVariables($config)
    {
        preg_match_all("/{.+}/U", $config["pattern"], $variables);

        return array_map(function ($value) {
            return substr($value, 1, strlen($value) - 2);
        }, $variables[0]);
    }
}