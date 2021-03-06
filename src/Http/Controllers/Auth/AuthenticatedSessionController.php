<?php

namespace Armincms\Api\Http\Controllers\Auth;

use Armincms\Api\Http\Controllers\Controller;
use Armincms\Api\Http\Requests\Auth\AuthRequest;
use Armincms\Api\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zareismail\Gutenberg\Gutenberg;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return redirect()->intended('/');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Armincms\Api\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $user = $request->authenticate();

        return [
            'token' => $user->createToken('login')->plainTextToken,
        ];
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(AuthRequest $request)
    {
        $request->user()->currentAccessToken()->delete();

        return [
        ];
    }
}
