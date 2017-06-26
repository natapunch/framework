<?php
	namespace Punchenko\Framework\Router;
	/**
	 * Class Route
	 * @package Punchenko\Framework\Router
	 */
	class Route
	{
		/**
		 * @var string Route name
		 */
		public $name;
		/**
		 * @var string Controller name
		 */
		public $controller;
		/**
		 * @var string Method name
		 */
		public $method;
		/**
		 * @var array  Parsed params
		 */
		public $params = [];
		/**
		 * @var array
		 */
		private $roles = [];

		/**
		 * Route constructor.
		 * @param string $name
		 * @param string $controller
		 * @param string $method
		 * @param array $params
		 * @param array $roles
		 */
		public function __construct($name, $controller, $method, array $params = [], array $roles = [])
		{
			$this->name = $name;
			$this->controller = $controller;
			$this->method = $method;
			$this->params = $params;
			$this->roles = $roles;
		}

		/**
		 * @return string
		 */
		public function getName(): string
		{
			return $this->name;
		}

		/**
		 * @param string $name
		 */
		public function setName(string $name)
		{
			$this->name = $name;
		}

		/**
		 * @return string
		 */
		public function getController(): string
		{
			return $this->controller;
		}

		/**
		 * @param string $controller
		 */
		public function setController(string $controller)
		{
			$this->controller = $controller;
		}

		/**
		 * @return string
		 */
		public function getMethod(): string
		{
			return $this->method;
		}

		/**
		 * @param string $method
		 */
		public function setMethod(string $method)
		{
			$this->method = $method;
		}

		/**
		 * @return array
		 */
		public function getParams(): array
		{
			return $this->params;
		}

		/**
		 * @param array $params
		 */
		public function setParams(array $params)
		{
			$this->params = $params;
		}

		/**
		 * Get required roles
		 *
		 * @return array
		 */
		public function getRoles(): array
		{
			return (array)$this->roles;
		}

		/**
		 * Set roles
		 *
		 * @param array $roles
		 * @return array
		 */
		public function setRoles($roles = [])
		{
			return $this->roles = (array)$roles;
		}
	}