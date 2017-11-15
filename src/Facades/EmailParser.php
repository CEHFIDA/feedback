<?php

namespace Selfreliance\feedback\Facades;

use Illuminate\Support\Facades\Facade;

class EmailParser extends Facade 
{
  	/**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return new \Selfreliance\Feedback\EmailParser();
    }
}