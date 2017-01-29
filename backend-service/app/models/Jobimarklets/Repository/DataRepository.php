<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: DataRepository.php
 * Date: 28/01/2017
 * Time: 13:54
 */

namespace Jobimarklets\Repository;


use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DataRepository
 *
 * @package Jobimarklets\Repository
 */
class DataRepository implements RepositoryInterface
{
    private $model;

    /**
     * DataRepository constructor.
     * @param $model
     */
    public function __construct($model)
    {
        $this->makeModel($model);
    }

    /**
     *  Save entity.
     *
     * @param Model $model
     */
    public function save(Model $model)
    {
        return $model->saveOrFail();
    }


    /**
     * Find by column values..
     * @param array $attribute - Collection of CriteriaInterface instances.
     * @param array $columns
     * @return mixed
     */
    public function findBy(array $attribute, array $columns = array ('*'))
    {
        $model = $this->model()->query();

        foreach ($attribute as $key => $value) {
            $model->where($key, '=', $value);
        }
        return $model->get();
    }

    /**
     *  Find Entity by Primary Key. You can pass an Array of Ids and fetch
     *  multiple items. Returns a Single Model or a Collection of models of the
     * same type.
     *
     * @param mixed $id - Id(s) of the Model you looking for.
     * @param array $columns -> List of columns to retrieve.
     * @return mixed
     */
    public function find($id, array $columns = array('*'))
    {
        return $this->model()->findOrFail($id, $columns);
    }


    /**
     * Delete an entity by providing it's ID.
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    /**
     *  Update the model.
     *
     * @param Model $model
     * @return bool
     */
    public function update(Model $model)
    {
        return $model->update();
    }

    /**
     * Get the Model Associated with this Repository.
     *
     * @return Model
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * Make model instance.
     * @param $model
     */
    private function makeModel($model)
    {
        if (!is_string($model)) {
            throw new InvalidArgumentException('Invalid model passed');
        }

        if (!class_exists($model)) {
            throw new InvalidArgumentException("class $model does not exists");
        }

        $this->model = new $model();
    }

    /** Get the Query Builder.
     *
     * @return Builder
     */
    public function builder()
    {
        return $this->model()->query();
    }
}