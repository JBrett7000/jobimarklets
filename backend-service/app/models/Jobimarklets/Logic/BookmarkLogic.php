<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: BookmarkLogic.php
 * Date: 28/01/2017
 * Time: 14:22
 */

namespace Jobimarklets\Logic;
use Jobimarklets\entity\Bookmark;
use Jobimarklets\entity\Category;
use Jobimarklets\entity\Tag;
use Jobimarklets\entity\User;
use Jobimarklets\Exceptions\BookmarkCreationException;
use Jobimarklets\Exceptions\CategoryException;
use Jobimarklets\Exceptions\TagException;
use Jobimarklets\Repository\BookmarkDataRepository;
use Jobimarklets\Repository\RepositoryInterface;


/**
 * Class BookmarkLogic
 * This class is used to perform all database operations related to Bookmarks.
 *
 * @package Jobimarklets\Logic
 */
class BookmarkLogic extends AbstractLogic
{

    public function __construct(BookmarkDataRepository $repo)
    {
        parent::__construct($repo);
    }


    /**
     *  Create Bookmark.
     *
     * @param User $user
     * @param Bookmark $bookmark
     * @throws BookmarkCreationException
     * @return mixed
     */
    public function createBookmark(User $user, Bookmark $bookmark)
    {
        $validator = $bookmark->validate();

        if ($validator->fails()) {
            $errors =  array_reduce(
                $validator->errors()->all(),
                function ($context, $item) {
                    return $context .= $item;
                },
                ''
            );

            throw new BookmarkCreationException(
                'Error creating bookmark ' . $errors
            );
        }

        $bookmark->user()->associate($user);
        return $this->repository->save($bookmark);
    }

    /**
     *  Update bookmark.
     *
     * Update Bookmark
     * @param Bookmark $bookmark
     * @throws BookmarkCreationException
     * @return boolean
     */
    public function updateBookmark(Bookmark $bookmark)
    {
        $validator = $bookmark->validate();

        if ($validator->fails()) {
            $errors =  array_reduce(
                $validator->errors()->all(),
                function ($context, $item) {
                    return $context .= $item;
                },
                ''
            );

            throw new BookmarkCreationException(
                'Error creating bookmark ' . $errors
            );
        }

        return $this->repository->update($bookmark);
    }

    /**
     *  Add Tags to the Bookmark
     *
     * @param $id - Bookmark ID
     * @param array $tags - An array of Tag
     * @return Bookmark
     */
    public function addTags($id, array $tags)
    {
        if (!is_int($id)) {
            throw new \InvalidArgumentException('Id is not an integer');
        }

        $bookmark = $this->find($id);
        $saved = $bookmark->tags()->saveMany($tags);

        return $saved ? $this->find($id) : false;
    }


    /**
     * Remove a Tag from the Bookmark
     * @param $bookmarkId
     * @param Tag $tag
     * @throws TagException
     * @return Bookmark
     */
    public function removeTag($bookmarkId, Tag $tag)
    {
        if (!is_numeric($bookmarkId)) {
            throw new \InvalidArgumentException(
                'parameter passed to $bookmarkId should be numeric'
            );
        }
        $bookmark = $this->find($bookmarkId);
        $key = $bookmark->tags->filter(function($item) use ($tag) {
            return $item->id == $tag->id;
        })->keys()->first();

        if (!is_numeric($key)) {
            throw new TagException('Tag cannot be found in bookmark.');
        }

        $bookmark->tags->forget($key);

        return $this->updateBookmark($bookmark);
    }

    /**
     *  Add a category to a Bookmark
     * @param $bookmarkId
     * @param array $categories
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function addCategory($bookmarkId, array $categories)
    {
        if (!is_numeric($bookmarkId)) {
            throw new \InvalidArgumentException('$bookmarkId must be numeric');
        }

        $bookmark = $this->find($bookmarkId);
        $saved = $bookmark->categories()->saveMany($categories);

        return $saved ? $this->find($bookmarkId) : false;
    }

    public function removeCategory($bookmarkId, Category $category)
    {
        if (!is_numeric($bookmarkId)) {
            throw new \InvalidArgumentException(
                'Invalid bookmark Id. Pass integer values.'
            );
        }

        $bookmark = $this->find($bookmarkId);
        $key = $bookmark->categories->each(function ($item) use ($category) {
            return $item->id === $category->id;
        })->keys()->first();

        if (!is_numeric($key)) {
            throw new TagException('Tag cannot be found in bookmark.');
        }

        $bookmark->categories->forget($key);

        return $this->updateBookmark($bookmark);
    }

}

