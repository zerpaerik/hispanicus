<?php
namespace hispanicus;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Hash;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
*/
class User extends Authenticatable
{
    use Notifiable;
    use HasRolesAndAbilities;
    use HasApiTokens;

    protected $fillable = ['name', 'email', 'password', 'remember_token'];
    
    
    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }
    
    
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }
    
    
    
}
