<?php

namespace API;

use Throwable;

/**
 * Class TeapotException
 * @package API
 */
class TeapotException extends \Exception
{
    protected string $error;

    /**
     * TeapotException constructor.
     * @param string $error
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($error = "", $message = "I'm a teapot", $code = 418, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->error = $error;
    }

    public function getError() : string
    {
        return $this->error;
    }
}