<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: Bookmark.php
 * Date: 22/01/2017
 * Time: 08:35
 */

namespace Jobimarklets\entity;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Jobimarklets\HasValidatorInterface;

/**
 * Class Bookmark - A simple Bookmark class.
 *
 * @package Jobimarklets\entity
 *
 * @property int $id - Bookmark ID.
 * @property int $user_id - Id of the user who owns this.
 * @property string $title - Title of the bookmark.
 * @property string $image - Blob of the image.
 * @property string $url - Url of the bookmarked article.
 * @property string $description - Summary description of the bookmark.
 * @property datetime $created_at - Date bookmark was created.
 * @property datetime $updated_at - Date bookmark was updated.
 * @property datetime $deleted_at - Date bookmark was deleted.
 */
class Bookmark extends Model implements HasValidatorInterface
{
    protected $table = 'bookmarks';

    protected $fillable = [
        'title', 'url', 'image', 'description',
    ];

    const RULES = [
        'title'         => 'required|max:150',
        'url'           => 'required|url',
        'description'   => 'required|max:255|min:50',
        'image'         => 'alpha_num',
    ];


    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'bookmarks_tags',
            'bookmark_id',
            'tag_id'
        );
    }

    /**
     *  Get the user that owns this bookmark.
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Validate this Bookmark.
     * @return mixed
     */
    public function validate()
    {
        return Validator::make($this->getAttributes(), self::RULES);
    }

}

