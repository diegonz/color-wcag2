<?php

namespace Diegonz\ColorWcag2\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ColorWcag2.
 */
class ColorWcag2 extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'color-wcag2';
    }
}
