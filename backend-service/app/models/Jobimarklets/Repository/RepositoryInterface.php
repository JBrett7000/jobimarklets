<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: RepositoryInterface.php
 * Date: 28/01/2017
 * Time: 13:46
 */

namespace Jobimarklets\Repository;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface RepositoryInterface
 * A simple Interface defining common data access methods to be implemented.
 *
 * @package Jobimarklets\Repository
 */
interface RepositoryInterface
{
    /**
     *  Find Entity by ID.
     *
     * @param $id
     * @return Model
     */
    public function find($id);

    /**
     * Find by attribute.
     * @return mixed
     */
    public function findBy(array $attribute);

    /**
     * Delete an Entity by specifying its ID.
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Update an Entity in the Database.
     *
     * @param Model $model
     * @return mixed
     */
    public function update(Model $model);

    /**
     * Gets the Registered Model with this Repository.
     *
     * @return Model
     */
    public function model();

    /**
     *  Get the Query Builder. Used to create more complicated queries.
     * @return Builder
     */
    public function builder();

    /**
     *  Save an Entity.
     *
     * @param Model $model
     * @return mixed
     */
    public function save(Model $model);

}