<?php

/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: CategoryLogicTest.php
 * Date: 11/06/2017
 * Time: 20:59
 */
class CategoryLogicTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations;

    protected $categoryLogic;

    protected $userLogic;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->categoryLogic = $this->app->make(
            \Jobimarklets\Logic\CategoryLogic::class
        );
        $this->userLogic = $this->app->make(
            \Jobimarklets\Logic\UserLogic::class
        );

        $this->userLogic->create($this->userProdiver());
        $this->user = $this->userLogic->find(1);
    }

    /**
     * @param $user
     * @param $name
     * @param $description
     * @dataProvider invalidCategoryProvider
     * @expectedException \Jobimarklets\Exceptions\CategoryException
     */
    public function testCreateInvalidCategory($user, $name, $description)
    {

        $category = new \Jobimarklets\entity\Category([
            'name' => $name,
            'description' => $description,
        ]);

        if ($user instanceof \Jobimarklets\entity\User) {
            $category->user()->associate($user);
        }

        $this->categoryLogic->create($category);
    }


    public function invalidCategoryProvider()
    {
        return [
            [$this->user, 'cat1', ''],
            [$this->user, '', 'description 1'],
            [null, 'Cat1', 'description 1'],
        ];
    }

    public function testCreateValidCategory()
    {
        $category = new \Jobimarklets\entity\Category([
            'name' => 'cat1',
            'description' => 'description 1',
        ]);

        $category->user()->associate($this->user);
        $saved = $this->categoryLogic->create($category);

        $this->assertTrue($saved);
    }


    /**
     * @expectedException \Jobimarklets\Exceptions\CategoryException
     */
    public function testCreateUpdateInvalidCategory()
    {
        $category = new \Jobimarklets\entity\Category([
            'name' => 'cat1',
            'description' => 'description 1',
        ]);
        $category->user()->associate($this->user);
        $category->name = 'name1';
        $category->description = 'description 1';
        $this->assertTrue($this->categoryLogic->create($category));

        //perform update
        $category = $this->categoryLogic->find(1);
        $category->name = '';
        $category->description = 'random description';
        $this->categoryLogic->update($category);
    }

    public function testDeleteCategory()
    {
        $category = new \Jobimarklets\entity\Category([
            'name' => 'cat1',
            'description' => 'description 1',
        ]);
        $category->user()->associate($this->user);
        $category->name = 'name1';
        $category->description = 'description 1';
        $this->assertTrue($this->categoryLogic->create($category));

        $category = $this->categoryLogic->find(1);
        $this->assertInstanceOf(
            \Jobimarklets\entity\Category::class, $category
        );

        $this->assertTrue($this->categoryLogic->delete(1));
    }


}