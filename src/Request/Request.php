<?php

namespace Punchenko\Framework\Request;

/**
 * Class Request Singleton
 * @package Punchenko\Framework\Request
 */
class Request
{
    private static $request = null;
    /**
     * @var array $headers
     */
    protected $headers = [];
    protected $raw = '';
    protected $buffer = '';

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

    private function __clone()
    {
    }
}