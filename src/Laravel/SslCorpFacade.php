<?php

namespace SslCorp\Laravel;

use Illuminate\Support\Facades\Facade;

class SslCorpFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     * @mixin \SslCorp\Order
     */
    protected static function getFacadeAccessor()
    {
        return 'sslcorp';
    }
}
