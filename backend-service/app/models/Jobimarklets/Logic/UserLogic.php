<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: UserLogic.php
 * Date: 29/01/2017
 * Time: 16:13
 */

namespace Jobimarklets\Logic;

use Jobimarklets\entity\User;
use Jobimarklets\Exceptions\UserCreationException;
use Jobimarklets\Exceptions\UserUpdateException;
use Jobimarklets\Repository\RepositoryInterface;
use Jobimarklets\Repository\UserDataRepository;

class UserLogic extends AbstractLogic
{

    public function __construct(UserDataRepository $repo)
    {
        parent::__construct($repo);
    }


    /**
     *  Create New User
     * @param $user
     * @return mixed
     * @throws UserCreationException
     */
    public function create(User $user)
    {
        $validator = $user->validate();

        if ($validator->fails()) {
           $errors =  array_reduce(
                $validator->errors()->all(),
                function ($context, $item) {
                    $context .= $item;

                    return $context;
                },
                ''
            );

            throw new UserCreationException(
                'Error creating user. ' .  $errors
            );
        }


        return $this->repository->save($user);
    }

    /**
     * Update User record.
     *
     * @param User $user
     * @return mixed
     * @throws UserUpdateException
     */
    public function update(User $user)
    {
        $validator = $user->validate();

        if ($validator->fails()) {
            $errors =  array_reduce(
                $validator->errors()->all(),
                function ($context, $item) {
                    $context .= $item;
                    return $context;
                },
                ''
            );

            throw new UserUpdateException(
                'Error updating user. ' .  $errors
            );
        }

        return $this->repository->update($user);
    }


    /**
     *  Simple Token generation method.
     * @return string
     */
    public function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(60));
    }
}