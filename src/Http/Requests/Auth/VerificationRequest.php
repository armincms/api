<?php

namespace Armincms\Api\Http\Requests\Auth;

use Armincms\Contract\Models\User;
use Illuminate\Auth\Events\Lockout; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException; 

class VerificationRequest extends AuthRequest
{ 
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [ 
            'broker' => 'required|in:mobile,email',
            'credential' => 'required',
        ];
    } 

    public function retreiveOrCreateUser()
    { 
        if ($user = Auth::getProvider()->retrieveByCredentials($this->credentials())) {
            return $user;
        }  

        return User::create([
            'name' => $this->credential,
            'email' => $this->credential,
            'password' => app('hash')->make($this->password ?? $this->credential), 
            'metadata::firstname' => $this->firstname,
            'metadata::lastname' => $this->lastname, 
            "metadata::mobile" => $this->broker === 'mobile' ? $this->credential : null 
        ]);
    }

    /**
     * Get the login credentials from the request.
     *
     * @return string
     */
    public function credentials()
    {
        if ($this->broker === 'email') {
            return [ 'email' => $this->email ];
        }

        return [ 
            'mobile' => function($query) {
                $query->whereHas('metadatas', function($query) {
                    $query
                        ->where('key', 'mobile')
                        ->where('value', $this->credential)
                        ->whereNotNull('value');
                });
            }
        ];
    }  
}
