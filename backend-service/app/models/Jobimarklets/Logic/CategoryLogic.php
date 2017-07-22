<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: CategoryLogic.php
 * Date: 11/06/2017
 * Time: 20:30
 */

namespace Jobimarklets\Logic;

use Jobimarklets\entity\Category;
use Jobimarklets\Exceptions\CategoryException;
use Jobimarklets\Repository\CategoryRepository;

class CategoryLogic extends AbstractLogic
{
    public function __construct(CategoryRepository $repo)
    {
        parent::__construct($repo);
    }

    /**
     * @param Category $category
     * @return mixed
     * @throws CategoryException
     */
    public function create(Category $category)
    {
        $validator = $category->validate();

        if ($validator->fails()) {
            $errors =  array_reduce(
                $validator->errors()->all(),
                function ($context, $item) {
                    $context .= $item;

                    return $context;
                },
                ''
            );

            throw new CategoryException('Error creating Category - ' . $errors);
        }

        return $this->repository->save($category);
    }

    public function update(Category $category)
    {
        $validator = $category->validate();

        if ($validator->fails()) {
            $errors =  array_reduce(
                $validator->errors()->all(),
                function ($context, $item) {
                    $context .= $item;

                    return $context;
                },
                ''
            );

            throw new CategoryException('Error creating Category - ' . $errors);
        }

        return $this->repository->update($category);
    }



}