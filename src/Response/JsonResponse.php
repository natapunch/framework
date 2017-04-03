<?php

namespace Punchenko\Framework\Response;
/**
 * Class JsonResponse
 * @package Punchenko\Framework\Response
 */

class JsonResponse extends Response
{
    const DEFAULT_VALUE = 'application/json';

    /**
     *Send content as JSON
     */
    public function sendBody()
    {
        echo json_encode($this->body);
    }

}
