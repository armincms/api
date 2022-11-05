<?php

namespace Armincms\Api;


class PatternBroker implements Broker
{
    /**
     * Notify user by given message.
     * 
     * @param $user 
     * @param string $pattern 
     * @param array  $options 
     * @return        
     */
    public function notify($user, string $pattern, array $options = [])
    {
        app('qasedak')->sendPattern($pattern, $user->mobile, (array) data_get($options, 'vars', []));
    }
}
