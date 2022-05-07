<?php

namespace Armincms\Api\Nova;
 
use Armincms\Contract\Nova\Bios; 
use Illuminate\Http\Request; 
use Laravel\Nova\Fields\Textarea; 

class Api extends Bios
{  
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    { 
        return [
            Textarea::make(__('Verification code message'), 'api_verification_message')
                ->default('Yout verification code is : [CODE].')
                ->help(__('example: Yout verification code is : [CODE].')),

            Textarea::make(__('Welcome message'), 'api_welcome_message')
                ->default('Welcome to my site Mr/Ms [USER].')
                ->help(__('example: Welcome to my site Mr/Ms [USER].')),
        ];
    }  

    /**
     * Get login page for the given website id.
     *  
     * @return integer            
     */
    public static function verificationMessage()
    {
        return static::option('api_verification_message');
    }

    /**
     * Get login page for the given website id.
     *   
     * @return integer            
     */
    public static function welcomeMessage()
    {
        return static::option('api_welcome_message');
    } 
}
