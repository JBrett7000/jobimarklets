<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: BookmarkDataRepository.php
 * Date: 22/04/2017
 * Time: 10:51
 */

namespace Jobimarklets\Repository;


use Jobimarklets\entity\Bookmark;

class BookmarkDataRepository extends DataRepository
{

    public function __construct()
    {
        parent::__construct(Bookmark::class);
    }


}