<?php

/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: DataRespositoryTest.php
 * Date: 29/01/2017
 * Time: 11:17
 */

use Jobimarklets\entity\Bookmark;
use Jobimarklets\entity\Category;
use Jobimarklets\entity\Tag;
use Jobimarklets\entity\User;
use Laravel\Lumen\Testing\DatabaseMigrations;


class DataRespositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage does not exists
     */
    public function testCantCreateNonExistentClassOfDataRepository()
    {
        $class = 'App\\NoneExistingClass';
        $this->dataRepositoryProvider($class);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid model passed
     */
    public function testOnlyStringCanBePassedIntoDataRepository()
    {
        $class = new \stdClass();
        $this->dataRepositoryProvider($class);
    }

    /**
     * @dataProvider  repositoryProvider
     */
    public function testEntityInstance($entity, $repository)
    {
        $this->assertInstanceOf($entity, $repository->model());
    }

    /**
     *  Test if we can find entities by an attribute.
     */
    public function testFindByAttribute()
    {
        $repo = $this->dataRepositoryProvider(User::class);

        $user1 = $this->userProdiver();
        $repo->save($user1);

        $user2 = new User();
        $user2->name = 'user 2';
        $user2->email = 'user2@mail.com';
        $user2->password = 'password1';
        $user2->enabled = true;
        $repo->save($user2);

        //find by attribute
        $user = $repo->findBy(['name' => 'user 2']);
        $this->assertEquals('user 2', $user->get(0)->name);

        $user = $repo->findBy(['email' => 'user@gmail.com']);
        $this->assertEquals('user@gmail.com', $user->get(0)->email);
    }

    /**
     * Testing creating an entity.
     */
    public function testCreatingEntity()
    {
        $user =$this->userProdiver();
        //Insert Entity
        $repo = $this->dataRepositoryProvider(User::class);
        $this->assertTrue($repo->save($user));
    }

    /**
     * @depends testCreatingEntity
     */
    public function testFindingEntity()
    {
        //find the Entity just created.
        $repo = $this->dataRepositoryProvider(User::class);
        $user = $this->userProdiver();
        $repo->save($user);

        $user = $repo->find(1);
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     *  Test that Entities can be deleted.
     */
    public function testDeletingEntity()
    {
        $repo = $this->dataRepositoryProvider(User::class);
        $user = $this->userProdiver();
        $repo->save($user);
        $this->assertTrue($repo->delete(1));
    }

    /**
     *  Test that exception is thrown when Entity is not found.
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function testWhenEntityNotFound()
    {
        $user = $this->userProdiver();
        $repo = $this->dataRepositoryProvider(User::class);
        $repo->save($user);

        $repo->find(2);
    }

    /**
     *  Test that Entity can be update.
     */
    public function testEntityCanBeUpdate()
    {
        $repo = $this->dataRepositoryProvider(User::class);
        $user = $this->userProdiver();
        $this->assertTrue($repo->save($user));

        $user = $repo->find(1);
        $this->assertInstanceOf(User::class, $user);

        $user->name = 'New Nmae';
        $this->assertTrue($repo->update($user));
    }


    public function repositoryProvider()
    {
        return [
            [User::class, $this->dataRepositoryProvider(User::class)],
            [Bookmark::class, $this->dataRepositoryProvider(Bookmark::class)],
            [Tag::class, $this->dataRepositoryProvider(Tag::class)],
            [Category::class, $this->dataRepositoryProvider(Category::class)],
        ];
    }

    protected function userProdiver()
    {
        $user = new User();
        $user->name = 'User 1';
        $user->email = 'user@gmail.com';
        $user->password = 'password1';
        $user->enabled = true;

        return $user;
    }
}