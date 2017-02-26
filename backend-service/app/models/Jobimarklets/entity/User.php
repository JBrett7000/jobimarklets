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
        'name'  => 'required|alpha|min:3|max:50',
        'email' => 'required|email',
        'password' => 'required|alpha_num|min:8|max:20',
        'enabled' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'enabled',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
//        'password',
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
