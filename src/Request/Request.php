<?php

	namespace Punchenko\Framework\Request;

	/**
	 * Class Request
	 * @package Punchenko\Framework\Request
	 */
	class Request
	{
		/**
		 * @var array $headers
		 */
		protected $headers = [];
		protected $raw = '';
		protected $buffer = '';
		protected $params;


		/**
		 * Request constructor.
		 */
		public function __construct()
		{
			$headers = [];
			if (function_exists('getallheaders')) {
				$headers = getallheaders();
			} else {
				foreach ($_SERVER as $key => $value) {
					if (substr($key, 0, 5) == "HTTP_") {
						$key = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($key, 5)))));
						$headers[$key] = $value;
					} else {
						$headers[$key] = $value;
					}
				}
			}
			$this->headers = $headers;
		}

		/**
		 * Return current Uri
		 * @return string
		 */
		public function getUri(): string
		{
			$raw = $_SERVER["REQUEST_URI"];
			$buffer = explode('?', $raw);
			return array_shift($buffer);
		}

		/**
		 * Return current metod
		 * @return string
		 */
		public function getMethod(): string
		{
			return $_SERVER["REQUEST_METHOD"];
		}

		public function getUriParams(): array
		{
			$res = [];
			parse_str($this->params, $res);
			return $res;
		}

		/**
		 * Get request header value
		 * @param null $key
		 * @return mixed
		 */
		public function getHeader($key = null)
		{
			if (empty($key)) {
				return $this->headers;
			}
			return isset($this->headers[$key]) ? $this->headers[$key] : null;
		}

		/**
		 * @param $name
		 * @return null
		 */
		public function __get($name)
		{
			return isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
		}

		/**
		 * @param $name
		 * @return bool
		 */
		public function __isset($name)
		{
			return isset($__REQUEST[$name]);
		}

		/**
		 * If $method does not exist - we use $method __call
		 * @param $method
		 * @param $args
		 * @return float|int|mixed|string
		 */
		public function __call($method, $args)
		{
			if (preg_match('/^get([\w\d_]*)/', $method, $match)) {
				$filter = @strtolower($match[1]);
				$param = array_shift($args);
				$default = array_shift($args);
				$raw = isset($_REQUEST[$param]) ? $_REQUEST[$param] : $default;
				switch ($filter) {
					case 'raw':
						$filtered = $raw;
						break;
					case 'int':
						$filtered = (int)$raw;
						break;
					case 'float':
						$filtered = (float)$raw;
						break;
					case 'string':
					default:
						$filtered = preg_replace('/[^\s\w\d_\-\.\,\+\(\)]*/Ui', '', urldecode($raw));
				}
				return $filtered;
			}
		}

		/**
		 * Returns true if request to receive back json response
		 *
		 * @return bool
		 */
		public function wantsJson(): bool
		{
			return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		}


	}
