<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: UserListerner.php
 * Date: 01/04/2017
 * Time: 12:38
 */

namespace App\Listeners;


use App\Events\UserEvent;
use Jobimarklets\Logic\UserLogic;

class UserListerner
{

    /**
     * @var UserLogic
     */
    protected $repo;

    public function __construct(UserLogic $repo)
    {
        $this->repo = $repo;
    }


    public function handle(UserEvent $event)
    {
        //TODO: Add a couple of switch statements for the event types.
        // Call the respective methods to handle this.
    }

}