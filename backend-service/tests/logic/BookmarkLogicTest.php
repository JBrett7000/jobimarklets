<?php

/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: BookmarkLogicTest.php
 * Date: 09/02/2017
 * Time: 22:14
 */

/**
 * Class BookmarkLogicTest
 */
class BookmarkLogicTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations;

    protected $bmLogic;

    protected $userLogic;

    protected $user;

    protected $categoryLogic;

    protected function bookmarkProvider()
    {
        return new \Jobimarklets\entity\Bookmark([
            'title' => 'Bookmark 1',
            'url'   => 'http://google.com',
            'image' => 'xxxxxx',
            'description' => 'example text and we have a lot more txt here.
            This is just crazy. asdfa nasdf a asdfasd asdf asdf asdf asdf',
        ]);
    }

    public function setUp()
    {
        parent::setUp();
        $this->bmLogic = $this->app->make(
            \Jobimarklets\Logic\BookmarkLogic::class
        );

        $this->userLogic = $this->app->make(
            \Jobimarklets\Logic\UserLogic::class
        );

        $this->userLogic->create($this->userProdiver());

        $this->categoryLogic = $this->app->make(
            \Jobimarklets\Logic\CategoryLogic::class
        );
    }

    public function testCreateValidBookmark()
    {
        $user = $this->userLogic->find(1);

        $this->assertInstanceOf(\Jobimarklets\entity\User::class, $user);

        $bookmark = $this->bookmarkProvider();

        $this->assertTrue($this->bmLogic->createBookmark($user, $bookmark));
    }

    /**
     * @
     * @expectedException \Jobimarklets\Exceptions\BookmarkCreationException
     */
    public function testCreateBookmarkWithInvalidDescription ()
    {
        $user = $this->userLogic->find(1);

        $this->assertInstanceOf(\Jobimarklets\entity\User::class, $user);

        $bookmark = $this->bookmarkProvider();
        $bookmark->description = 'Short description';

        $this->assertTrue($this->bmLogic->createBookmark($user, $bookmark));
    }

    /**
     * @depends testCreateValidBookmark
     */
    public function testFindBookmark()
    {
        $bookmark = $this->bookmarkProvider();
        $user = $this->userLogic->find(1);

        $this->bmLogic->createBookmark($user, $bookmark);

        $bookmark = $this->bmLogic->find(1);
        $this->assertInstanceOf(
            \Jobimarklets\entity\Bookmark::class,
            $bookmark
        );
    }

    /**
     * @depends testCreateValidBookmark
     */
    public function testDeleteBookmark()
    {
        $bookmark = $this->bookmarkProvider();
        $user = $this->userLogic->find(1);

        $this->bmLogic->createBookmark($user, $bookmark);
        $bookmark = $this->bmLogic->find(1);

        $this->assertInstanceOf(
            \Jobimarklets\entity\Bookmark::class,
            $bookmark
        );

        $this->bmLogic->delete(1);
    }

    /**
     * @depends testCreateValidBookmark
     */
    public function testUpdateBookmark()
    {
        $bookmark = $this->bookmarkProvider();
        $user = $this->userLogic->find(1);

        $this->bmLogic->createBookmark($user, $bookmark);

        $bookmark = $this->bmLogic->find(1);
        $this->assertInstanceOf(
            \Jobimarklets\entity\Bookmark::class,
            $bookmark
        );

        $bookmark->title = 'Second title';
        $this->assertTrue($this->bmLogic->updateBookmark($bookmark));
    }

    public function testAddSingleTag()
    {
        $bookmark = $this->bookmarkProvider();
        $user = $this->userLogic->find(1);
        $this->bmLogic->createBookmark($user, $bookmark);

        $bookmark = $this->bmLogic->find(1);
        $this->assertInstanceOf(\Jobimarklets\entity\Bookmark::class, $bookmark);

        $tag = new \Jobimarklets\entity\Tag([
            'title' => 'Tag1'
        ]);
        $tag->user()->associate($user);

        $bookmark = $this->bmLogic->addTags(1, [$tag]);
        $this->assertInstanceOf(\Jobimarklets\entity\Bookmark::class, $bookmark);

        $this->assertCount(1, $bookmark->tags()->getResults());
    }

    public function testAddMultipleTag()
    {
        $bookmark = $this->bookmarkProvider();
        $user = $this->userLogic->find(1);
        $this->bmLogic->createBookmark($user, $bookmark);

        $bookmark = $this->bmLogic->find(1);
        $this->assertInstanceOf(\Jobimarklets\entity\Bookmark::class, $bookmark);

        $tag = new \Jobimarklets\entity\Tag([
            'title' => 'Tag1'
        ]);
        $tag->user()->associate($user);

        $tag2 = new \Jobimarklets\entity\Tag([
           'title' => 'Tag2',
        ]);
        $tag2->user()->associate($user);

        $tag3 = new \Jobimarklets\entity\Tag([
            'title' => 'Tag3',
        ]);
        $tag3->user()->associate($user);

        $bookmark = $this->bmLogic->addTags(1, [$tag, $tag2, $tag3]);
        $this->assertInstanceOf(\Jobimarklets\entity\Bookmark::class, $bookmark);

        $this->assertCount(3, $bookmark->tags()->getResults());
    }

    public function testRemovingSingleTag()
    {
        $bookmark = $this->bookmarkProvider();
        $user = $this->userLogic->find(1);
        $this->bmLogic->createBookmark($user, $bookmark);

        $bookmark = $this->bmLogic->find(1);
        $this->assertInstanceOf(\Jobimarklets\entity\Bookmark::class, $bookmark);

        $tag = new \Jobimarklets\entity\Tag([
            'title' => 'Tag1'
        ]);
        $tag->user()->associate($user);

        $tag2 = new \Jobimarklets\entity\Tag([
            'title' => 'Tag2',
        ]);
        $tag2->user()->associate($user);

        $bookmark = $this->bmLogic->addTags(1, [$tag, $tag2,]);
        $this->assertInstanceOf(\Jobimarklets\entity\Bookmark::class, $bookmark);

        // Remove Tag 2 and save
        $tagToRemove = \Jobimarklets\entity\Tag::find(2);
        $this->assertTrue($this->bmLogic->removeTag(1, $tagToRemove));
    }

    public function testAddingCategoryToBookmark()
    {
        $user = $this->userLogic->find(1);

        $category = new \Jobimarklets\entity\Category([
            'name' => 'cat1',
            'description' => 'description 1',
        ]);
        $category->user()->associate($user);
        $saved = $this->categoryLogic->create($category);
        $this->assertTrue($saved);

        $bookmark = $this->bookmarkProvider();
        $bookmark->user()->associate($user);
        $this->bmLogic->createBookmark($user, $bookmark);

        $category = $this->categoryLogic->find(1);
        $this->bmLogic->addCategory(1, [$category]);

        $bookmark = $this->bmLogic->find(1);
        $this->assertCount(1, $bookmark->categories);
    }

    public function testRemovingCategoryFromBookmark()
    {
        $user = $this->userLogic->find(1);

        $category = new \Jobimarklets\entity\Category([
            'name' => 'cat1',
            'description' => 'description 1',
        ]);
        $category->user()->associate($user);
        $saved = $this->categoryLogic->create($category);
        $this->assertTrue($saved);

        $bookmark = $this->bookmarkProvider();
        $bookmark->user()->associate($user);
        $this->bmLogic->createBookmark($user, $bookmark);

        $category = $this->categoryLogic->find(1);
        $this->bmLogic->addCategory(1, [$category]);

        $this->assertTrue($this->bmLogic->removeCategory(1, $category));
    }

    public function testDeletingCategoryAndBookmarkCategoryIsNull()
    {
        $user = $this->userLogic->find(1);

        $category = new \Jobimarklets\entity\Category([
            'name' => 'cat1',
            'description' => 'description 1',
        ]);
        $category->user()->associate($user);
        $saved = $this->categoryLogic->create($category);
        $this->assertTrue($saved);

        $bookmark = $this->bookmarkProvider();
        $bookmark->user()->associate($user);
        $this->bmLogic->createBookmark($user, $bookmark);

        $this->categoryLogic->delete(1);
        $bookmark = $this->bmLogic->find(1);
        $this->assertCount(0, $bookmark->categories);
    }
}


