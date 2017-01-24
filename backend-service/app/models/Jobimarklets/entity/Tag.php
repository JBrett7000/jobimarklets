<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: Tag.php
 * Date: 22/01/2017
 * Time: 09:09
 */

namespace Jobimarklets\entity;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag - Bookmark tags.
 * @package Jobimarklets\entity
 * @property int $id - Tag ID
 * @property  int $user_id - User ID owning this tag.
 * @property  string $title - Title of tag.
 */
class Tag extends Model
{

    /**
     * User that owns this tag.
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}