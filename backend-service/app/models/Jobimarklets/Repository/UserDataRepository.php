<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: UserDataRepository.php
 * Date: 22/04/2017
 * Time: 10:45
 */

namespace Jobimarklets\Repository;


use Jobimarklets\entity\User;

class UserDataRepository extends DataRepository
{
    public function __construct()
    {
        parent::__construct(User::class);
    }
}