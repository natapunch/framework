<?php

namespace Punchenko\Framework\Response;
/**
 * Class JsonResponse
 * @package Punchenko\Framework\Response
 */

class JsonResponse extends Response
{
    const DEFAULT_CODE = 200;
    const DEFAULT_KEY = 'Content-Type';
    const DEFAULT_VALUE = 'application/json';

    public function __construct($content, $code = self::DEFAULT_CODE)
    {
        parent::__construct($content, $code);
    }

    /**
     *Send content as JSON
     */
    public function sendBody()
    {
        echo json_encode($this->content);
    }

}
