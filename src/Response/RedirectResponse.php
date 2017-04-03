<?php

namespace Punchenko\Framework\Response;
/**
 * Class RedirectResponse
 * @package Punchenko\Framework\Response
 */
class RedirectResponse extends Response
{
    const DEFAULT_CODE = 301;

    /**
     * RedirectResponse constructor.
     * @param int $code
     * @param string $redirect_uri
     */
    public function __construct($code = self::DEFAULT_CODE, string $redirect_uri)
    {
        $this->code = $code;
        $this->addHeader('Location', $redirect_uri);
    }

    public function send()
    {
        $this->sendHeaders();
    }
}
