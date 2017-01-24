<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: Category.php
 * Date: 22/01/2017
 * Time: 08:53
 */

namespace Jobimarklets\entity;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Category - Category a Bookmarklet is under
 *
 * @package Jobimarklets\entity
 * @property int $id - Category ID
 * @property int $user_id - User ID.
 * @property string $name - Name of category
 * @property string $description - Summary description of summary
 * @property datetime $created_at - Date category was created
 * @property datetime $updated_at - Date category was updated.
 */
class Category extends Model
{
    protected $table = 'categories';

    /**
     * User that own the Category
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}