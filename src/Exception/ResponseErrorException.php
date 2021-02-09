<?php

namespace SslCorp\Exception;

use Exception;
use Throwable;

class ResponseErrorException extends Exception
{
    private $data;

    public function __construct($message = "", $code = 0, ?Throwable $previous = null, $data)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    public function getData()
    {
        return $this->data;
    }
}
