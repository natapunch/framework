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
     * @param string $view_name
     * @param array $params
     * @return mixed
     * @throws ViewNotFoundException
     */
    public static function render(string $view_name, array $params = [])
    {
        $paths = self::$views_all_paths;
        do {
            $path_to_view = array_shift($paths);
            $path = pathinfo($path_to_view);
            $path_to_view = realpath($path_to_view . $path['basename']);
            if (file_exists($path_to_view)) {
                break;
            } else {
                $path_to_view = null;
            }
        } while (!empty($paths));
        if (empty($path_to_view)) {
            throw new ViewNotFoundException(sprintf("View %s was not found in any of registered paths", $view_name));
        }
        extract($params);
        ob_start();
        include($path_to_view);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;

    }

}