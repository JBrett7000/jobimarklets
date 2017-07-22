<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: TagRepository.php
 * Date: 11/06/2017
 * Time: 18:32
 */

namespace Jobimarklets\Repository;


use Jobimarklets\entity\Tag;

class TagRepository extends DataRepository
{

    public function __construct()
    {
        parent::__construct(Tag::class);
    }

    public function create(Tag $tag)
    {
        return $this->save($tag);
    }
}