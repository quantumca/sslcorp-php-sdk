<?php

namespace SslCorp\Exception;

use Exception;

class MethodNotFoundException extends Exception
{
    const CODE = 0x404;

    const MESSAGE_PREFIX = 'Method not found';

    protected $code = self::CODE;
}
