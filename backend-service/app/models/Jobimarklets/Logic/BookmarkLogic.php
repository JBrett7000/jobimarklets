<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: BookmarkLogic.php
 * Date: 28/01/2017
 * Time: 14:22
 */

namespace Jobimarklets\Logic;


use Jobimarklets\Repository\RepositoryInterface;

/**
 * Class BookmarkLogic
 * This class is used to perform all database operations related to Bookmarks.
 *
 * @package Jobimarklets\Logic
 */
class BookmarkLogic implements LogicInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * BookmarkLogic constructor.
     * @param RepositoryInterface $repo
     */
    public function __construct(RepositoryInterface $repo)
    {
        $this->repository = $repo;
    }
}