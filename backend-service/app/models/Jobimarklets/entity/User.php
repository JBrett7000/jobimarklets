<?php

namespace Jobimarklets\entity;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Jobimarklets\HasValidatorInterface;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

/**
 * Class User
 * @package Jobimarklets\entity
 * @property int $id - User ID
 * @property string $name - Name of user
 * @property string $email - Email of user
 * @property boolean $enabled - Enable flag.
 * @property string $password - Password
 * @property string $api_token - Api token for this user
 * @property datetime $api_token_expires - Time left for api_token to expire.
 */
class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    HasValidatorInterface
{
    use Authenticatable, Authorizable;

    /**
     * Validation rules
     */
    const USER_RULES = [
        'name'              => 'required|string|min:3|max:50',
        'email'             => 'required|email',
        'password'          => 'required|min:8|max:100',
        'enabled'           => 'boolean',
        'api_token'         => 'nullable|string',
        'api_token_expires' => 'nullable|date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'enabled', 'api_token', 'api_token_expires'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'api_token', 'api_token_expires', 'remember_token',
        'enabled', 'ip_address', 'failed_attempts', 'created_at', 'updated_at',
    ];

    /**
     * Bookmarks owned by the user.
     * @return Collection - Colleciton of Bookmarks
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'user_id');
    }



    /**
     * User defined categories.
     *
     * @return Collection - Collection of Category
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'user_id');
    }

    public function validate()
    {
        return Validator::make($this->getAttributes(), self::USER_RULES);
    }


}
