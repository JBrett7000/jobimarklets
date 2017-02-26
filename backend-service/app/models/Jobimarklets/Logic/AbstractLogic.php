<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: AbstractLogic.php
 * Date: 12/02/2017
 * Time: 14:52
 */

namespace Jobimarklets\Logic;


use Jobimarklets\Repository\RepositoryInterface;

class AbstractLogic implements LogicInterface
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