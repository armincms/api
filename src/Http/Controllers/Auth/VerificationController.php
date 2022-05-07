<?php

namespace Armincms\Api\Http\Controllers\Auth;

use Armincms\Api\Http\Controllers\Controller; 
use Armincms\Api\Http\Requests\Auth\VerificationRequest;  
use Armincms\Api\Models\VerificationToken;
use Armincms\Api\Nova\Api;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(VerificationRequest $request)
    {    
        $token = VerificationToken::createForUser(
            $user = $request->retreiveOrCreateUser(),
            $code = rand(99999,999999)
        ); 

        // send verification code
        $message = str_replace('[CODE]', $code, Api::verificationMessage());
        app('verification.broker')->notify($user, $message);

        return response()->json([
            'status' => 'verification-code-sent',
            'token'  => $token->token,
        ]);
    }

    /**
     * Verify user token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {  
        $token = VerificationToken::with('auth')    
            ->whereToken($request->token)
            ->where('created_at', '>=', (string) now()->subMinute(5))
            ->first();

        if(! optional($token)->check($request->verification_code)) { 
            throw ValidationException::withMessages([
                'verification_code' => __('Invalid verification code'),
            ]);
        } 

        VerificationToken::resetForUser($token->auth); 

        // send welcome message
        $message = str_replace('[USER]', $token->auth->name, Api::welcomeMessage());
        app('verification.broker')->notify($token->auth, $message);

        return response()->json([
            'status'=> 'verified',
            'token' => $token->auth->createToken('login')->plainTextToken,
        ]);
    }
}
