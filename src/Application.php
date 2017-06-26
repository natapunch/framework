<?php

	namespace Punchenko\Framework;

	use Punchenko\Framework\Config\Config;
	use Punchenko\Framework\DI\DInjector;
	use Punchenko\Framework\Exception\InvalidUrlException;
	use Punchenko\Framework\Renderer\Renderer;
	use Punchenko\Framework\Request\Request;
	use Punchenko\Framework\Response\JsonResponse;
	use Punchenko\Framework\Response\Response;
	use Punchenko\Framework\Router\Exception\RouteMethodNotFoundException;
	use Punchenko\Framework\Router\Exception\RouteNotFoundException;
	use Punchenko\Framework\Router\Route;
	use Punchenko\Framework\Router\Router;

	include_once('globalfunc.php');

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
		private $response;

		/**
		 * Application constructor.
		 * @param $config
		 */
		public function __construct($config = [])
		{
			// Init system config:
			$this->config = new Config($config);
			DInjector::setConfig($this->config);


			$this->request = DInjector::make('request');

			// Set default (system) view path:
			Renderer::addViewPath(dirname(__FILE__) . '/View');

			// Application own views path (if provided):

			if ($this->config->has('views')) {
				Renderer::addViewPath($this->config->views);

			}
		}


		/**
		 *Start app
		 */
		public function run()
		{
			$router = DInjector::make('router');
			try {

				$route = $router->getRoute($this->request);

				if ($route) {

					$response = $this->processRoute($route);

				}
			} catch (RouteNotFoundException $e) {
				$response = $this->setError($e->getMessage(), 404);
			} catch (\Exception $e) {
				$response = $this->setError($e->getMessage(), 500);
			}


			$this->prepareResponse($this->response)->send();

		}

		/**
		 * Process route
		 *
		 * @param Route $route
		 * @return mixed
		 * @throws \Exception
		 */
		protected function processRoute(Route $route)
		{
			$route_controller = $route->getController();
			$route_method = $route->getMethod();
			if (class_exists($route_controller)) {
				$reflectionClass = new \ReflectionClass($route_controller);
				if ($reflectionClass->hasMethod($route_method)) {
					$controller = DInjector::make($route_controller);
					$reflectionMethod = $reflectionClass->getMethod($route_method);
					$params = DInjector::resolveParams(
						$reflectionMethod->getParameters(),
						$route->getParams()
					);

					return $reflectionMethod->invokeArgs($controller, $params);
				} else {
					throw new \Exception(sprintf('Controller method [%s] not found in [%s]', $route_method, $route_controller));
				}
			} else {
				throw new \Exception(sprintf('Controller class [%s] not found', $route_controller));
			}
		}

		/**
		 * Create system error response
		 *
		 * @param $message
		 * @return mixed
		 */
		public function setError($message, $code = 500)
		{
			if ($this->request->wantsJson()) {
				return compact('code', 'message');
			} else {
				//@TODO: Check first if appropriate layout exists...
				return Renderer::render('error/' . $code . '.html', compact('code', 'message'));
			}
		}

		/**
		 * Prepare content to be processed like response
		 *
		 * @param   $content
		 * @return  Response
		 */
		protected function prepareResponse($content): Response
		{
			if ($content instanceof Response) {
				// Do nothing, just return:
				return $content;

			}

			// Otherwise...
			if ($this->request->wantsJson() || is_array($content) || is_object($content)) {
				// Deal with Json response:
				$response = new JsonResponse($content);
			} else {
				$response = new Response($content);
			}

			return $response;
		}

		/**
		 *Destruct app
		 */
		public function destruct()
		{

		}
	}