<?php

	namespace Punchenko\Framework\Renderer;


	use Punchenko\Framework\Exception\ViewNotFoundException;

	class Renderer
	{
		/**
		 * @var array   Store all possible Views locations
		 */
		public static $views_all_paths = [];


		/**
		 * Add new view path
		 * @param $path
		 */
		public static function addViewPath($path)
		{
			if (file_exists(realpath($path))) {
				array_unshift(self::$views_all_paths, $path);

			}
		}

		/**
		 * Get all registered view paths
		 *
		 * @return array
		 */
		public static function getViewPaths(): array
		{
			return self::$views_all_paths;

		}


		/**
		 * Render specified view
		 *
		 * @param string $view
		 * @param array $params
		 * @return mixed
		 * @throws ViewNotFoundException
		 */
		public static function render(string $view, array $params = [])
		{
			$paths = self::$views_all_paths;

			do {
				$path_to_view = array_shift($paths);

				$path_to_view = realpath($path_to_view . self::processViewName($view));

				if (file_exists($path_to_view)) {
					break;
				} else {
					$path_to_view = null;
				}
			} while (!empty($paths));

			if (empty($path_to_view)) {
				throw new ViewNotFoundException(sprintf("View %s was not found in any of registered paths", $view));
			}

			include($path_to_view);
			ob_start();
			extract($params);
			ob_get_contents();
			return ob_get_clean();


		}

		/**
		 * Transform view or partial name to path / filename
		 *
		 * @param $view_name
		 * @return string
		 */
		private static function processViewName($view_name): string
		{
			return '/' . $view_name . '.php';


		}
	}