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

    /**
     * Find Tag by Id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Delete Tag by Id.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Find user by attributes. [key => ['operator' => '<,>,=,<>', 'value' => mixed]
     * @param array $attributes
     * @return mixed
     */
    public function findBy(array $attributes)
    {
        return $this->repository->findBy($attributes);
    }
}