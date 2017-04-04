<?php

	namespace Punchenko\Framework;

	use Punchenko\Framework\Exceptions\InvalidUrlException;
	use Punchenko\Framework\Request\Request;
	use Punchenko\Framework\Response\Response;
	use Punchenko\Framework\Router\Exceptions\RouteMethodNotFoundException;
	use Punchenko\Framework\Router\Exceptions\RouteNotFoundException;
	use Punchenko\Framework\Router\Router;

	/**
	 * Class Application
	 * @package Punchenko\Framework
	 */
	class Application
	{
		/**
		 * @package var=array()
		 */

		private $config = [];

		/**
		 * Application constructor.
		 * @param $config
		 */
		public function __construct($config=[])
		{
			$this->config = $config;
		}


		/**
		 *Start app
		 */
		public function run()
		{
			$router = new Router($this->config['routes']);

			try {
				$route = $router->getRoute(Request::getRequest());

				$route_controller = $route->getController();
				$route_method = $route->getMethod();
				if (class_exists($route_controller)) {
					$reflectionClass = new \ReflectionClass($route_controller);
					if ($reflectionClass->hasMethod($route_method)) {
						$controller = $reflectionClass->newInstance();
						$reflectionMethod = $reflectionClass->getMethod($route_method);
						$response = $reflectionMethod->invokeArgs($controller, $route->getParams());
						if ($response instanceof Response) {
							$response->send();
						}
					}
				}
			} catch (RouteNotFoundException $e) {
				echo "Route was not found";
			} catch (RouteMethodNotFoundException $e) {
				echo $e->getMessage();
			} catch (InvalidUrlException $e) {
				echo $e->getMessage();
			} catch (\Exception $e) {
				echo "OOOps";
			}
		}

		/**
		 *Destruct app
		 */
		public function destruct()
		{

		}
	}