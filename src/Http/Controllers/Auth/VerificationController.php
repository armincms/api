<?php

namespace Armincms\Api\Http\Controllers\Auth;

use Armincms\Api\Http\Controllers\Controller; 
use Armincms\Api\Http\Requests\Auth\VerificationRequest;  
use Armincms\Api\Models\VerificationToken;
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

        app('verification.broker')->notify($user, $code);

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
        $token = VerificationToken::with('auth')->where([ 
            'token' => $request->token 
        ])->first();

        if(! optional($token)->check($request->verification_code)) { 
            throw ValidationException::withMessages([
                'verification_code' => __('Invalid verification code'),
            ]);
        }  

        return response()->json([
            'status'=> 'verified',
            'token' => $token->auth->createToken('login')->plainTextToken,
        ]);
    }
}
