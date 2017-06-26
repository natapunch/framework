<?php
	namespace Punchenko\Framework\Router;

	use Punchenko\Framework\Config\Config;
	use Punchenko\Framework\Request\Request;
	use Punchenko\Framework\Router\Exception\RouteKeyNotFoundException;
	use Punchenko\Framework\Router\Exception\RouteNotFoundException;

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
		private $request;

		/**
		 * Router constructor.
		 * @param Config $config
		 * @param Request $request
		 */
		public function __construct(Config $config, Request $request)
		{
			$this->request = $request;
			$config = $config->get('routes', []);
			foreach ($config as $key => $value) {
				$existed_variables = $this->getExistedVariables($value);
				$this->routes[$key] = [
					"origin" => $value["pattern"],
					"regexp" => $this->getRegexpFromRoute($value, $existed_variables),
					"method" => isset($value["method"]) ? $value["method"] : "GET",
					"controller_name" => $this->getControllerName($value),
					"controller_method" => $this->getControllerMethod($value),
					"variables" => $existed_variables,
					"roles" => isset($value["roles"]) ? $value["roles"] : []
				];
			}
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

		/**
		 * Returns regexp by config
		 * @param array $config_routes
		 * @param array $existed_variables
		 * @return string
		 * @internal param array $config
		 */
		private function getRegexpFromRoute(array $config_routes, array $existed_variables): string
		{
			$pattern = $config_routes["pattern"];
			$result = str_replace("/", '\/', $pattern);
			$variables_names = $existed_variables;
			for ($i = 0; $i < count($variables_names); $i++) {
				$var_reg = "(" .
					(array_key_exists($variables_names[$i], $config_routes["variables"])
						? $config_routes["variables"][$variables_names[$i]]
						: self::DEFAULT_VAR_REGEXP
					)
					. ")";
				$result = str_replace("{" . $variables_names[$i] . "}", $var_reg, $result);
			}
			return "/^" . $result . "$/";
		}

		/**
		 * Returns name of controller
		 * @param array $config_routes
		 * @return string
		 */
		private function getControllerName($config_routes): string
		{
			return $config_routes["controller"];
		}

		/**
		 * Return name of controller method
		 * @param $config_routes
		 * @return string
		 */
		private function getControllerMethod($config_routes): string
		{
			return $config_routes["action"];
		}

		public function getRoute()
		{
			$uri = $this->request->getUri();
			foreach ($this->routes as $name => $route) {
				if (preg_match_all($route['regexp'], $uri, $matches) && ($route['method'] == $this->request->getMethod())) {
					$result = new Route($name, $route['controller_name'], $route['controller_method']);
					if (!empty($route['variables'])) {
						array_shift($matches);
						$result->setParams($this->parseParamValues($route['variables'], $matches));
					}
					$result->setRoles($route['roles']);
					return $result;
				}
			}
			throw new RouteNotFoundException('Route not found');
		}

		/**
		 * Bind param values to assoc array
		 *
		 * @param $variables
		 * @param $values
		 *
		 * @return array
		 */
		private function parseParamValues($variables, $values)
		{
			$buffer = array_map(function ($item) {
				return is_array($item) ? array_shift($item) : $item;
			}, $values);
			return array_combine($variables, $buffer);
		}

		/**
		 * Get link
		 * @param string $route_name
		 * @param array $params
		 * @return string
		 * @throws RouteKeyNotFoundException
		 * @throws RouteNotFoundException
		 */
		public function getLink(string $route_name, array $params = []): string
		{
			if (!array_key_exists($route_name, $this->routes))
				throw new RouteNotFoundException("\"$route_name\" route was not found in config");
			preg_match_all('/\{([\w\d_]+)\}/', $link = $this->routes[$route_name]['origin'], $matches);
			foreach ($matches[1] as $key) {
				if (!array_key_exists($key, $params))
					throw new RouteKeyNotFoundException("Key \"$key\" is required for route \"$route_name\"");
				$link = str_replace("{" . $key . "}", $params[$key], $link);
			}
			return $link;
		}
	}