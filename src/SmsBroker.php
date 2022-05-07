<?php

namespace Armincms\Api;

use Illuminate\Support\Manager; 

class SmsBroker implements Broker
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
        app('qasedak')->send($message, $user->mobile);
    } 
}
