<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: UserEvent.php
 * Date: 01/04/2017
 * Time: 12:24
 */

namespace App\Events;


use Jobimarklets\entity\User;

class UserEvent extends Event
{
    const EVENT_TYPE_CREATED = 'created';

    const EVENT_TYPE_UPDATED = 'updated';

    const EVENT_TYPE_DELETE_ACCOUNT = 'remove';

    /**
     *  Associated user.
     * @var User
     */
    private $user;

    /**
     * Type of event this instance is.
     * @var string
     */
    private $eventType;

    public function __construct(User $user, $eventType = 'created')
    {
        $this->user = $user;
        $this->eventType = $eventType;
    }

    /**
     * The user associated with this event.
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     *  The type of user event triggered.
     * @return string
     */
    public function type()
    {
        return $this->eventType;
    }
}