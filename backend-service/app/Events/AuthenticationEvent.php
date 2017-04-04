<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: AuthenticationEvent.php
 * Date: 04/04/2017
 * Time: 19:49
 */

namespace App\Events;


use Jobimarklets\entity\User;

class AuthenticationEvent extends Event
{
    /**
     *  Used to identify the event when a user enters wrong login.
     */
    const AUTH_EVENT_FAILED_LOGIN = 'failed login';

    /**
     *  Used to identify the password update event.
     */
    const AUTH_EVENT_PASSWORD_UPDATE = 'password update';

    /**
     *  Used when a user request a delete account action.
     */
    const AUTH_EVENT_ACCOUNT_DELETE = 'delete account';


    /**
     * @var string
     */
    protected $eventType;

    /**
     * @var User
     */
    protected $user;


    /**
     * AuthenticationEvent constructor.
     * @param User $user
     * @param $eventType
     */
    public function __construct(User $user, $eventType)
    {
        $this->eventType = $eventType;
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function event()
    {
        return $this->eventType;
    }
}
