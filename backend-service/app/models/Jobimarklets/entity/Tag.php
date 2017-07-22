<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: Tag.php
 * Date: 22/01/2017
 * Time: 09:09
 */

namespace Jobimarklets\entity;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Jobimarklets\HasValidatorInterface;

/**
 * Class Tag - Bookmark tags.
 * @package Jobimarklets\entity
 * @property int $id - Tag ID
 * @property  int $user_id - User ID owning this tag.
 * @property  string $title - Title of tag.
 */
class Tag extends Model implements HasValidatorInterface
{
    public $timestamps = false;

    protected $fillable = ['title'];

    const RULES = [
        'title'     => 'required|alpha_num|',
        'user_id'   => 'required',
    ];

    /**
     * User that owns this tag.
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function validate()
    {
       return Validator::make($this->getAttributes(), self::RULES);
    }


}