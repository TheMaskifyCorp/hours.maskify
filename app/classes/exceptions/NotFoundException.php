<?php

namespace API;

use Throwable;

/**
 * Class NotFoundException
 * @package API
 */
class NotFoundException extends \Exception
{
    protected string $error;

    /**
     * NotFoundException constructor.
     * @param string $error
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($error = "", $message = "Not Found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->error = $error;
    }

    public function getError() : string
    {
        return $this->error;
    }
}