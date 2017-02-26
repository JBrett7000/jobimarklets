<?php

/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: UserLogicTest.php
 * Date: 12/02/2017
 * Time: 14:41
 */

use Jobimarklets\entity\User;

class UserLogicTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations;

    protected $userLogic;


    public function setUp()
    {
        parent::setUp();
        $this->userLogic = new \Jobimarklets\Logic\UserLogic(
            $this->dataRepositoryProvider(\Jobimarklets\entity\User::class)
        );
    }

    /**
     * @expectedException \Jobimarklets\Exceptions\UserCreationException
     */
    public function testUserCreationWithNumberInName()
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'user1@mail.com',
            'password' => 'password1',
        ]);

        //fail because of Username
        $this->userLogic->createUser($user);
    }

    /**
     * @expectedException \Jobimarklets\Exceptions\UserCreationException
     */
    public function testUserCreationWithEmptyEmail()
    {
        $user = new User([
            'name' => 'User',
            'email' => '',
            'password' => 'password1',
        ]);

        //fail because of Username
        $this->userLogic->createUser($user);
    }

    /**
     * @expectedException \Jobimarklets\Exceptions\UserCreationException
     */
    public function testUserCreationWithEmptyPassword()
    {
        $user = new User([
            'name' => 'User',
            'email' => '',

        ]);

        //fail because of Username
        $this->userLogic->createUser($user);
    }

    public function testUserCreationValid()
    {
        $user = new User([
            'name'      => 'User',
            'email'     => 'user@mail.com',
            'password'  => 'password1',
            'enabled'   => true,
        ]);

        //fail because of Username
       $this->userLogic->createUser($user);
    }

    /**
     * @depends testUserCreationValid
     */
    public function testUpdateUser()
    {
        $user = [
            'name'      => 'User',
            'email'     => 'user@mail.com',
            'password'  => 'password1',
            'enabled'   => true,
        ];

        $this->assertTrue($this->userLogic->updateUser(1, $user));
    }

    /**
     * @depends testUserCreationValid
     */
    public function testDeleteUser()
    {
        $this->assertTrue($this->userLogic->deleteUser(1));
    }
}