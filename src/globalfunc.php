<?php
	/**
	 * Created by PhpStorm.
	 *
	 */
	use Punchenko\Framework\DI\DInjector;
	use Punchenko\Framework\Renderer\Renderer;

	if (!function_exists('view')) {
		/**
		 * Render view
		 *
		 * @param $view_name
		 * @param array $data
		 * @param bool $main_layout
		 *
		 * @return mixed
		 */
		function view($view_name, $data = [], $main_layout = true)
		{
			$request = DInjector::make('request');
			if ($request->wantsJson()) {
				// No need to render, just return raw data
				return empty($data) ? true : $data;

			}

			$output = Renderer::render($view_name, $data);

			if ($main_layout) {
				$output = Renderer::render('layout.html', ['content' => $output]);
			}

			return $output;

		}
	}

	if (!function_exists('route')) {
		/**
		 * Build route
		 *
		 * @param $route_name
		 * @param array $params
		 *
		 * @return string
		 */
		function route($route_name, $params = [])
		{
			$router = DInjector::make('router');
			return $router->getLink($route_name, $params);
		}
	}