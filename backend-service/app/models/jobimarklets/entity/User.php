<?php

namespace jobimarklets\entity;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use jobimarklets\entity\Bookmark;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
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
}
