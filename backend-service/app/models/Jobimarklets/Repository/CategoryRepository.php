<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: CategoryRepository.php
 * Date: 11/06/2017
 * Time: 20:28
 */

namespace Jobimarklets\Repository;


use Jobimarklets\entity\Category;

class CategoryRepository extends DataRepository
{
    public function __construct()
    {
        parent::__construct(Category::class);
    }
}