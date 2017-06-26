<?php

	namespace Punchenko\Framework\Config;

	/**
	 * Class Config
	 * System config wrapper
	 *
	 * @package Punchenko\Framework\Config
	 */
	class Config
	{
		/**
		 * @var array   Config storage
		 */
		protected static $config = [];

		/**
		 * Config constructor.
		 * @param array $data
		 */
		public function __construct($data = [])
		{
			if (empty(self::$config) && !empty($data)) {
				$this->set($data);
			}
			//Load config file
			if (!is_array($data))
				$this->loadFromFile($data);

		}

		/**
		 * Set config
		 *
		 * @param $data
		 */
		public function set($data)
		{
			self::$config = $data;
		}

		/**
		 * Load config
		 *
		 * @param $file
		 */
		public function loadFromFile($file)
		{
//        $section = file_get_contents(dirname(ROOT) . '/config/config.php');
//        var_dump($section);
			self::$config = include($file);
		}

		/**
		 * Get config param
		 * @param $param_name
		 * @return mixed
		 */
		public function __get($param_name)
		{
			return isset(self::$config[$param_name]) ? self::$config[$param_name] : null;
		}

		/**
		 * Recursive getter
		 *
		 * @param $key //Key may be complex like: db.host, db.driver, etc
		 * @param $default
		 *
		 * @return mixed
		 */
		public function get($key = null, $default = null)
		{
			$chain = explode('.', $key);
			$node = self::$config;
			if (!empty($chain)) {
				do {
					$cell = array_shift($chain);
					if (!isset($node[$cell])) {
						break;
					}
					$node = is_array($node) ? $node[$cell] : null;
				} while (!empty($chain) && !empty($node));
			}
			return $node ?? $default;
		}

		/**
		 * Check if key exists
		 *
		 * @param $key //Key may be complex like: db.host, db.driver, etc
		 *
		 * @return bool
		 */
		public function has($key): bool
		{
			$chain = explode('.', $key);
			$node = self::$config;
			do {
				$cell = array_shift($chain);
				if (!isset($node[$cell])) {
					return false;
				}
				$node = $node[$cell];
			} while (!empty($chain) && !empty($node));
			return true;
		}
	}