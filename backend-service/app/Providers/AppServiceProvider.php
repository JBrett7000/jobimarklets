<?php

namespace App\Providers;

use App\Events\UserEvent;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Support\ServiceProvider;
use Jobimarklets\entity\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //configure Mailing class.
        $this->app->singleton('mailer', function ($app) {
            return $app->loadComponent('mail', MailServiceProvider::class, 'mailer');
        });

        //Fire Event On User Account Deletion.
        User::deleting(function ($user) {
            event(new UserEvent($user, UserEvent::EVENT_TYPE_DELETE_ACCOUNT));
        });
    }
}
