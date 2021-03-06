<?php

namespace Armincms\Api\Http\Controllers\Auth;

use Armincms\Api\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    { 
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/');
        }

        $request->user()->sendEmailVerificationNotification(); 

        return back()->with('status', 'verification-link-sent');
    }
}
