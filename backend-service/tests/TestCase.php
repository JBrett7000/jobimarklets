<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function dataRepositoryProvider($class)
    {
        return new \Jobimarklets\Repository\DataRepository($class);
    }

    protected function userProdiver()
    {
        $user = new \Jobimarklets\entity\User();
        $user->name = 'User';
        $user->email = 'user@gmail.com';
        $user->password = 'password1';
        $user->enabled = true;

        return $user;
    }

    public function tearDown()
    {
        \Illuminate\Support\Facades\DB::delete('delete from bookmarks_categories');
        \Illuminate\Support\Facades\DB::delete('delete from categories');
        \Illuminate\Support\Facades\DB::delete('delete from bookmarks_tags');
        \Illuminate\Support\Facades\DB::delete('delete from tags');
        \Illuminate\Support\Facades\DB::delete('delete from bookmarks');
        \Illuminate\Support\Facades\DB::delete('delete from users');


        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE bookmarks AUTO_INCREMENT = 1');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE tags AUTO_INCREMENT = 1');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1');



       parent::tearDown();
    }


}
