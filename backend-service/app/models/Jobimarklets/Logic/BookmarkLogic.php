<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: BookmarkLogic.php
 * Date: 28/01/2017
 * Time: 14:22
 */

namespace Jobimarklets\Logic;
use Jobimarklets\entity\Bookmark;
use Jobimarklets\entity\User;
use Jobimarklets\Exceptions\BookmarkCreationException;


/**
 * Class BookmarkLogic
 * This class is used to perform all database operations related to Bookmarks.
 *
 * @package Jobimarklets\Logic
 */
class BookmarkLogic extends AbstractLogic
{

    /**
     * Find Bookmark.
     * @param $id
     * @return Bookmark
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     *  Delete Bookmarks.
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
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
}

