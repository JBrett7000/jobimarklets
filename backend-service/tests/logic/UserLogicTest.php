<?php

/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: UserLogicTest.php
 * Date: 12/02/2017
 * Time: 14:41
 */

use Jobimarklets\entity\User;
use MailThief\Testing\InteractsWithMail;

class UserLogicTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations, InteractsWithMail;

    protected $userLogic;


    public function setUp()
    {
        parent::setUp();
        $this->userLogic = $this->app->make(\Jobimarklets\Logic\UserLogic::class);
//            new \Jobimarklets\Logic\UserLogic(
//            $this->dataRepositoryProvider(\Jobimarklets\entity\User::class)
//        );
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
        $this->userLogic->create($user);
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
        $this->userLogic->create($user);
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
       $this->userLogic->create($user);
    }

    /**
     * @depends testUserCreationValid
     */
    public function testUpdateUser()
    {
        $user = new User([
            'name'      => 'User',
            'email'     => 'user@mail.com',
            'password'  => 'password1',
            'enabled'   => true,
        ]);

        //fail because of Username
        $this->userLogic->create($user);

        $user = $this->userLogic->find(1);
        $user->name = 'Sameuel';
        $user->enabled = false;

        $this->assertTrue($this->userLogic->update($user));
    }

    /**
     * @depends testUserCreationValid
     */
    public function testDeleteUser()
    {
        $user = new User([
            'name'      => 'User',
            'email'     => 'user@mail.com',
            'password'  => 'password1',
            'enabled'   => true,
        ]);

        //fail because of Username
        $this->userLogic->create($user);

        $this->assertTrue($this->userLogic->delete(1));
    }

    /**
     *  Test you can find user.
     */
    public function testFindUser()
    {
        $user = new User([
            'name'      => 'User',
            'email'     => 'user@mail.com',
            'password'  => 'password1',
            'enabled'   => true,
        ]);


        $this->assertTrue($this->userLogic->create($user));

        $user = $this->userLogic->find(1);
        $this->assertInstanceOf(User::class, $user);
    }
}