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
                    return $context .= $item;
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
                    return $context .= $item;
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


}