<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $primaryKey = 'user_id';

    public function tokens()
    {
        return $this->hasMany('App\Models\UserToken', 'user_id');
    }

    /**
     * @param $deviceId
     * @param $deviceType
     * @return UserToken
     */
    public function createToken($deviceId, $deviceType, $token = null)
    {
        if (!$deviceId) {
            $deviceId = md5(str_random()) . uniqid();
        }
        $tokenData = [
            'token' => $token ? $token : md5(str_random()) . uniqid() . sha1(str_random()),
            'device_id' => $deviceId,
            'device_type' => $deviceType
        ];
        return $this->tokens()->create($tokenData);
    }
}
