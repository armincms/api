<?php

namespace Armincms\Api;

use Illuminate\Support\Manager; 

interface Broker
{  
    /**
     * Notify user by given message.
     * 
     * @param   $user 
     * @param   $message 
     * @return        
     */
    public function notify($user, $message); 
}
