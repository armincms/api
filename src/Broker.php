<?php

namespace Armincms\Api;

use Illuminate\Support\Manager;

interface Broker
{
    /**
     * Notify user by given message.
     * 
     * @param $user 
     * @param string $message 
     * @param array  $options 
     * @return        
     */
    public function notify($user, string $message, array $options = []);
}
