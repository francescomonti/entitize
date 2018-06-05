<?php

namespace Wcr\Entitize\Facades;

use Illuminate\Support\Facades\Facade;

class Entitize extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'entitize';
    }
}
