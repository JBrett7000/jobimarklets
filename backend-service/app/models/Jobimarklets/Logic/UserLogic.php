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

class UserLogic extends AbstractLogic
{

    /**
     * Find the user by ID
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     *  Create New User
     * @param $user
     * @return mixed
     * @throws UserCreationException
     */
    public function createUser(User $user)
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

    public function updateUser(User $user)
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

    public function deleteUser($id)
    {
        return $this->repository->delete($id);
    }

    /**
     *  Simple Token generation method.
     * @return string
     */
    public function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(60));
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