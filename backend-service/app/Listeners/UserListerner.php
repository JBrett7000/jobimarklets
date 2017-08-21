<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: UserListerner.php
 * Date: 01/04/2017
 * Time: 12:38
 */

namespace App\Listeners;


use App\Events\UserEvent;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Jobimarklets\Logic\UserLogic;

class UserListerner implements ShouldQueue
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
        switch ($event->type()) {
            case UserEvent::EVENT_TYPE_CREATED :
                $this->sendSignupEmail($event);
                break;
        }
    }

    public function sendSignupEmail(UserEvent $event)
    {
        $hosts = Request::capture()->root();

        $userString = $event->user()->name . $event->user()->email;

        $checksum = Hash::make($hosts . $userString . (time() * 0.234));
        $checksum = preg_replace('/\/|\$|\.|\|/', '', $checksum);

        Cache::forever($checksum, ['user' => $event->user()->id]);


        Mail::send('emails.new_signup', [
            'user' => $event->user(),
            'host' => Request::capture()->root(),
            'checksum' => $checksum,
        ], function ($message) use ($event) {
            $message->from('support@gmail.com');
            $message->to($event->user()->email);
            $message->subject('Welcome to JobiMarklets');
        });
    }

}