<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: bindings.php
 * Date: 01/03/2017
 * Time: 01:20
 */

/**
 * This file is just a neat place to store my IOC bindings.
 * This is injected into the app.php bootstrapped file.
 */

use Jobimarklets\Logic\UserLogic;
use Jobimarklets\Repository\DataRepository;
use Jobimarklets\entity\User;
use Jobimarklets\Logic\BookmarkLogic;
use Jobimarklets\entity\Bookmark;


//// Register Logic Layer dependencies ////

$app->bind('\Jobimarklets\Logic\UserLogic', function () {
    return new UserLogic(
        new DataRepository(User::class)
    );
});

$app->bind('\Jobimarklets\Logic\Bookmark', function () {
    return new BookmarkLogic(
        new DataRepository(
            Bookmark::class
        )
    );
});

