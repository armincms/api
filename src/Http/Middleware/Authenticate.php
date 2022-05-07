<?php

namespace Armincms\Api\Http\Middleware;

use Armincms\Api\Cypress\Login;
use Armincms\Api\Nova\Api;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Zareismail\Gutenberg\Gutenberg;
use Zareismail\Cypress\Http\Requests\ComponentRequest;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {  
        if ($request->expectsJson()) {
            return;
        } 

        $website = app(ComponentRequest::class)->resolveComponent()->website();
        $loginPage = Api::loginPage($website->getKey());  
        $fragment = Gutenberg::cachedFragments()->find($loginPage); 

        return url(optional($fragment)->getUrl() ?? '/');
    }
}
