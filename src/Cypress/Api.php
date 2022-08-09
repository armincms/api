<?php

namespace Armincms\Api\Cypress;

use Laravel\Nova\Exceptions\AuthenticationException;
use Zareismail\Cypress\Component;
use Zareismail\Cypress\Contracts\Resolvable;
use Zareismail\Cypress\Http\Requests\CypressRequest;

class Api extends Component implements Resolvable
{
    /**
     * The display layout class name.
     *
     * @var string
     */
    public $layout = \Zareismail\Cypress\Layouts\Clean::class;

    /**
     * Resolve the resoruce's value for the given request.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @return void
     */
    public function resolve($request): bool
    {
        throw_unless(
            \Auth::guard('admin')->check() || \Auth::guard('sanctum')->check(),
            AuthenticationException::class
        );

        $request->setUserResolver(function() {
            return \Auth::guard('sanctum')->user();
        });

        return true;
    }

    /**
     * Get the component fragments.
     *
     * @return string
     */
    public function fragments(): array
    {
        return [
        ];
    }
}
