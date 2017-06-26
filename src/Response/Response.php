<?php

	namespace Punchenko\Framework\Response;
	/**
	 * Class Response
	 *
	 * @package Punchenko\Framework
	 */
	class Response
	{
		const DEFAULT_CODE = 200;
		const DEFAULT_KEY = 'Content-Type';
		const DEFAULT_VALUE = 'text/html';
		/**
		 * Status messages
		 */
		const STATUS_MSGS = [
			'200' => 'Ok',
			'301' => 'Moved permanently',
			'302' => 'Moved temporary',
			'401' => 'Auth required',
			'403' => 'Access denied',
			'404' => 'Not found',
			'500' => 'Server error'
		];
		/**
		 * @var string
		 */
		protected $content = '';
		/**
		 * @var array
		 */
		protected $headers = [];

		/**
		 * Response constructor.
		 * @param string $content
		 * @param int $code
		 * @internal param string $body
		 */
		public function __construct($content, $code = self::DEFAULT_CODE)
		{
			// Warning: Cannot modify header information - headers already sent by
			$this->code = $code;
			$this->setBody($content);
			$this->addHeader(self::DEFAULT_KEY, self::DEFAULT_VALUE);
		}

		/**
		 * Set content
		 * @param $content
		 * @internal param $body
		 */
		public function setBody($content)
		{
			$this->content = $content;
		}

		/**
		 * Add header
		 * @param $key
		 * @param $value
		 */
		public function addHeader($key, $value)
		{
			$this->headers[$key] = $value;
		}

		/**
		 * Send response
		 */
		public function send()
		{
			$this->sendBody();
			exit();
		}

		/**
		 * Send headers
		 */
		public function sendHeaders()
		{
			header($_SERVER['SERVER_PROTOCOL'] . " " . $this->code . " " . self::STATUS_MSGS[$this->code]);
			if (!empty($this->headers)) {
				foreach ($this->headers as $key => $value) {
					header($key . ": " . $value);
				}
			}
		}

		/**
		 * Send content
		 */
		public function sendBody()
		{
			echo $this->content;
		}
	}
