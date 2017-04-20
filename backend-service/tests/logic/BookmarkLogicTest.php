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
        $this->bmLogic =
            new Jobimarklets\Logic\BookmarkLogic(
                new Jobimarklets\Repository\DataRepository(
                    Jobimarklets\entity\Bookmark::class
                )
            );
        $this->userLogic = new \Jobimarklets\Logic\UserLogic(
            $this->dataRepositoryProvider(\Jobimarklets\entity\User::class)
        );

        $this->userLogic->createUser($this->userProdiver());
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
}


