<?php

/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: TagLogicTest.php
 * Date: 11/06/2017
 * Time: 19:09
 */
class TagLogicTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations;

    protected $tagLogic;

    protected $userLogic;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->tagLogic = $this->app->make(\Jobimarklets\Logic\TagLogic::class);

        $this->userLogic = $this->app->make(\Jobimarklets\Logic\UserLogic::class);

        $this->userLogic->create($this->userProdiver());

        $this->user = $this->userLogic->find(1);
    }

    /**
     * @expectedException \Jobimarklets\Exceptions\TagException
     */
    public function testCreateNewTagWithEmptyTitle()
    {
        $tag = new \Jobimarklets\entity\Tag();

        $tag->user()->associate($this->user);

        $this->tagLogic->create($tag);
    }

    /**
     * @expectedException \Jobimarklets\Exceptions\TagException
     */
    public function testCreateNewTagWithoutUser()
    {

        $tag = new \Jobimarklets\entity\Tag(['title' => 'title1']);
        $this->tagLogic->create($tag);
    }

    public function testCreateValidTag()
    {
        $tag = new \Jobimarklets\entity\Tag(['title' => 'Title1']);
        $tag->user()->associate($this->user);

        $saved = $this->tagLogic->create($tag);

        $this->assertTrue($saved);
    }

    public function testUpdatingTag()
    {
        $tag = new \Jobimarklets\entity\Tag(['title' => 'Title1']);
        $tag->user()->associate($this->user);
        $saved = $this->tagLogic->create($tag);
        $this->assertTrue($saved);

        $tag = $this->tagLogic->find(1);
        $tag->title = 'title2';

        $saved = $this->tagLogic->update($tag);
        $this->assertTrue($saved);
    }

    /**
     * @expectedException \Jobimarklets\Exceptions\TagException
     */
    public function testCantUpdateTagWithEmptyTitle()
    {
        $tag = new \Jobimarklets\entity\Tag(['title' => 'Title1']);
        $tag->user()->associate($this->user);
        $saved = $this->tagLogic->create($tag);
        $this->assertTrue($saved);

        $tag = $this->tagLogic->find(1);
        $tag->title = '';

        $this->tagLogic->update($tag);
    }

    public function testDeleteTag()
    {
        $tag = new \Jobimarklets\entity\Tag(['title' => 'Title1']);
        $tag->user()->associate($this->user);
        $saved = $this->tagLogic->create($tag);
        $this->assertTrue($saved);

        $tag = $this->tagLogic->find(1);
        $this->assertInstanceOf(\Jobimarklets\entity\Tag::class, $tag);
        $deleted = $this->tagLogic->delete($tag->id);

        $this->assertTrue($deleted);
    }
}