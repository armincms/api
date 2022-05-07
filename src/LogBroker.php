<?php

namespace Armincms\Api;

use Illuminate\Support\Manager; 

class LogBroker implements Broker
{  
    /**
     * Notify user by given message.
     * 
     * @param   $user 
     * @param   $message 
     * @return  void     
     */
    public function notify($user, $message)
    { 
        \Log::info('verification message:'. $message);
    } 
}
