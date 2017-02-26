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
        $user->name = 'User 1';
        $user->email = 'user@gmail.com';
        $user->password = 'password1';
        $user->enabled = true;

        return $user;
    }
}
