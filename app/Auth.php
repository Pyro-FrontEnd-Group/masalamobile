<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Auth extends Authenticatable
{
    use Notifiable;

    /**
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return mixed|string
     */
    public function getAuthPassword()
    {
        return $this->user_password;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->user_id;
    }
}