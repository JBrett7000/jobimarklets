<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: Category.php
 * Date: 22/01/2017
 * Time: 08:53
 */

namespace Jobimarklets\entity;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Jobimarklets\HasValidatorInterface;

/**
 * Class Category - Category a Bookmarklet is under
 *
 * @package Jobimarklets\entity
 * @property int $id - Category ID
 * @property int $user_id - User ID.
 * @property string $name - Name of category
 * @property string $description - Summary description of summary
 * @property \DateTime $created_at - Date category was created
 * @property \DateTime $updated_at - Date category was updated.
 */
class Category extends Model implements HasValidatorInterface
{
    protected $table = 'categories';

    protected $fillable = ['name', 'description'];

    const RULES = [
        'user_id' => 'required',
        'name'    => 'required|string|max:50',
        'description' => 'string|max:255',
    ];

    /**
     * User that own the Category
     *
     * @return mixed
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