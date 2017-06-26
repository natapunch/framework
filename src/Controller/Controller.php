<?php
	/**
	 * Created by PhpStorm.
	 * User: Punchenko
	 * Date: 05.04.2017
	 * Time: 17:05
	 */

	namespace Punchenko\Framework\Controller;


	use Punchenko\Framework\Renderer\Renderer;
	use Punchenko\Framework\Response\Response;

	class Controller
	{

		/**
		 * @param string $path_to_view
		 * @param array $params
		 * @param bool $layout
		 * @return string
		 * @internal param string $viewPath
		 */
		public function render(string $path_to_view, array $params = [], bool $layout = true): string
		{
			$content = Renderer::render($path_to_view, $params);
			if ($layout) {
				$path = pathinfo($path_to_view);
				$layoutPath = $path['dirname'] . '/layout.html.' . $path['extension'];
				$content = Renderer::render($layoutPath, ['content' => $content]);
			}

			return $content;
		}
	}