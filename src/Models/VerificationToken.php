<?php

namespace Armincms\Api\Models;

use Armincms\Contract\Concerns\Authorizable;
use Illuminate\Database\Eloquent\Model;  

class VerificationToken extends Model  
{    
    use Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [ 
    ];  

    /**
     * Create new token for the user by given code.
     * 
     * @param  \Illuminate\Database\Eloqeunt\Model $user 
     * @param  string $code 
     * @return \Illuminate\Database\Eloqeunt\Model       
     */
    public static function createForUser($user, $code)
    {
        static::resetForUser($user);

        return static::unguarded(function() use ($user, $code) { 
            return static::firstOrCreate([
                'auth_id'   => $user->getKey(),
                'auth_type' => $user->getMorphClass(),
                'token'     => md5($code.time().$user->getKey()),
                'hash'      => app('hash')->make($code),
            ]); 
        });
    }

    /**
     * Create new token for the user by given code.
     * 
     * @param  \Illuminate\Database\Eloqeunt\Model $user 
     * @param  string $code 
     * @return \Illuminate\Database\Eloqeunt\Model       
     */
    public static function resetForUser($user)
    {
        static::authorize($user)->delete();

        return new static;
    }

    public function check($code)
    { 
        return app('hash')->check($code, $this->hash);  
    }
}
