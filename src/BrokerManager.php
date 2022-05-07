<?php

namespace Armincms\Api;

use Illuminate\Support\Manager; 

class BrokerManager extends Manager
{ 

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
        return tap(parent::createDriver($driver), function($instance) {
            throw_unless($instance instanceof Broker);
        });
    }

    /**
     * Create a new log driver instance.
     *
     * @param  string  $driver
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */ 
    public function createLogDriver()
    {
        return new LogBroker;
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'log';
    }
}
