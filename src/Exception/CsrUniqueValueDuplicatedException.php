<?php

namespace SslCorp\Exception;

use Exception;

class CsrUniqueValueDuplicatedException extends Exception
{
    protected $code = 'Your csr and unique_value was duplicated!';
}
