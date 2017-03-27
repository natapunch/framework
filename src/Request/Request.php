<?php

namespace Punchenko\Framework\Request;


/**
 * Class Request Singleton
 * @package Punchenko\Framework\Request
 */
class Request
{
    private static $request = null;
    protected $headers = [];
    protected $raw = '';
    protected $buffer = '';

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) == "HTTP_") {
                $key = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($key, 5)))));
                $headers[$key] = $value;
            } else {
                $headers[$key] = $value;
            }
        }
        $this->headers = $headers;
    }


    /**
     * Returns request
     * @return Request
     */
    public static function getRequest(): self
    {
        if (null === self::$request) {
            self::$request = new self();
        }
        return self::$request;
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

    private function __clone()
    {
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
}