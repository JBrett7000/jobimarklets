<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: AuthController.php
 * Date: 31/03/2017
 * Time: 16:00
 */

namespace App\Http\Controllers;


use App\Events\AuthenticationEvent;
use App\Events\UserEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Jobimarklets\entity\User;
use Jobimarklets\Exceptions\AuthenticationException;
use Jobimarklets\Exceptions\UserCreationException;
use Jobimarklets\Exceptions\UserUpdateException;
use Jobimarklets\Logic\UserLogic;
use Laravel\Lumen\Routing\Controller;

class AuthController extends Controller
{
    /**
     *  User Logic
     * @var
     */
    protected $userLogic;

    public function __construct(UserLogic $logic)
    {
        $this->userLogic = $logic;
    }

    /**
     *  Get the user data based on access token.
     */
    public function index(Request $req)
    {
        $data = Cache::get($req->header('api_token'));

        $user = $this->userLogic->find($data['userid']);

        return response()->json(['data' => $user->toArray()], 200);
    }

    /**
     *  Authenticate a user.
     * @throws AuthenticationException
     * @param Request $req - HTTP Request.
     * @return Response - Access token returned
     */
    public function authenticate(Request $req)
    {
        if (!$this->userExists($req)) {
            throw new AuthenticationException('User account does not exist.');
        }

        $user = $this->userLogic->findBy([
            'email'     => ['operator' => '=', 'value' => $req->input('email')],
            'enabled'   => ['operator' => '=', 'value' => true],
        ])->first();

        if (! $user instanceof User) {
            throw new AuthenticationException('User does not exist or account is not available.');
        }

        if (Hash::check($req->input('password'), $user->password)){
            return $this->loginUser($user);
        }

        // We got here, this means, password is likely wrong.
        event(
            new AuthenticationEvent(
                $user,
                AuthenticationEvent::AUTH_EVENT_FAILED_LOGIN
            )
        );

        return response('false', 401);
    }


    /**
     *  Login user.
     * @param User $user
     * @return Response
     */
    private function loginUser(User $user)
    {
        //TODO: See if you can update this code to use Carbon date class instead.
        if (empty($user->api_token)
            || (new \DateTime('NOW')) > (new \DateTime($user->api_token_expires))
        ) {
            $token = $this->updateToken($user);
        } else {
            if (is_null(Cache::get($user->api_token))) {
                $token = $this->updateToken($user);
            } else {
                return $user->api_token;
            }
        }

        Cache::put(
            $user->api_token,
            ['userid' => $user->id, 'loggedin' => true],
            Carbon::now()->addDay(1)
        );

        return response($token, 200);
    }

    /**
     *  Update user token.
     *
     * @param User $user
     * @return mixed|string
     */
    private function updateToken(User $user)
    {
        $currTime = new \DateTime('NOW');
        $expireDate = date_add($currTime, new \DateInterval('P1W'));

        $user->api_token = $this->userLogic->generateToken();
        $user->api_token_expires = $expireDate;

        $this->userLogic->update($user);

        return $user->api_token;
    }


    /**
     *  Reset user password.
     */
    public function reset()
    {
        //TODO: take the username and captcha token.
        // - Validate the Captcha with Google. Implement when you can.
        // - Send an email with assigned token for reset.
    }

    public function validateCaptcha($token)
    {
        //TODO: validate token against google
    }

    public function logout(Request $req)
    {
        $data = Cache::get($req->header('api_token'));

        if (!empty($data)) {
            // For some reason, even when the token is invalid, we not going to notify the user.
            $user = $this->userLogic->find($data['userid']);
            $user->api_token = null;
            $user->api_token_expires = null;

            $this->userLogic->update($user);

            Cache::forget($req->header('api_token'));
        }

        return response('successful', 200);
    }

    /**
     * Create new user. If user is successfully created, then a *successful*
     * message is sent in the body. HTTP 200 response code is sent.
     * If an error occurs, then a HTTP 401 Bad Request with an accompanying
     * message description is returned.
     *
     * @param Request $req
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws UserCreationException
     * @return Response
     */
    public function create(Request $req)
    {

        if ($this->userExists($req)) {
            throw new UserCreationException(
                "User with the email exists."
            );
        }

        $user = new User($req->all());

        //validate data
        $validator = $user->validate();

        if ($validator->fails()) {
            $errors =  array_reduce(
                $validator->errors()->all(),
                function ($context, $item) {
                    $context .= $item;
                    return $context;
                },
                ''
            );
            throw new UserCreationException(
                $errors
            );
        }

        $user->password = Hash::make($req->input('password'));
        $this->userLogic->create($user);

        event(new UserEvent($user, UserEvent::EVENT_TYPE_CREATED));

        return response('successful', 200);
    }

    /**
     * Update user details. We can Update just the password field.
     *
     * @param Request $req
     * @param $id
     * @throws UserUpdateException
     * @return Response
     */
    public function update(Request $req, $id)
    {
        try {
            $user = $this->userLogic->find($id);
            $user->password = $req->input('password');

            $validator = $user->validate();
            if ($validator->fails()) {
                $errors =  array_reduce(
                    $validator->errors()->all(),
                    function ($context, $item) {
                        $context .= $item;
                        return $context;
                    },
                    ''
                );
                throw new UserUpdateException(
                    $errors
                );
            }

            $user->password = Hash::make($req->input('password'));
            $this->userLogic->update($user);
            event(new UserEvent($user, UserEvent::EVENT_TYPE_UPDATED));

        } catch (ModelNotFoundException $ex) {
            throw new UserUpdateException(
                'Update failed. User does not exists'
            );
        }

        return response('true', 200);
    }

    /**
     *  Check if the user exists.
     * @param Request $req
     * @return bool
     */
    protected function userExists(Request $req)
    {
        $model = $this->userLogic->findBy([
            'email' => ['value' => $req->input('email'), 'operator' => '=']
        ]);

        return !$model->isEmpty();
    }

    public function unregister(Request $request)
    {
        $token = $request->header('api_token');
        $cache = Cache::get($token);
        $user = $this->userLogic->find($cache['userid']);

        $this->userLogic->delete($user->id);

        return response('successful', 204);
    }

    /**
     * Activate account.
     *
     * @param $checksum
     * @return \Illuminate\View\View
     */
    public function activate($checksum)
    {
        $checkToken = Cache::get($checksum);

        if ($checkToken == null) {
            return view('accountactivate',
                [
                    'message' => "Activation is invalid or has expired.",
                    'success' => false
                ]
            );
        }

        $this->userLogic->activateAccount($checkToken['user']);
        Cache::forget($checksum);
        return view('accountactivate',
            ['message' => "Account activated.", 'success' => true]
        );
    }

    /**
     * Send activation email if failed previously.
     *
     * @param Request $request - Pass 'email' value
     * @return \Illuminate\View\View
     */
    public function reactivate(Request $request)
    {
        $email = $request->input('email');
        $message = '';
        $success = false;
        $user = null;
        try {
            $user = $this->userLogic->findBy([
               'email' => [
                   'operator' => '=',
                   'value' => $request->input('email')
               ],
               'enabled' => ['operator' => '!=', 'value' => 1]
            ]);

            if ($user->isEmpty()) {
               throw new ModelNotFoundException("can't find user $email");
            }

            event(new UserEvent($user->first(), UserEvent::EVENT_TYPE_CREATED));
            $message = 'Account activated';
            $success = true;

        } catch (ModelNotFoundException $ex) {
            if (is_null($user)) {
               $message = 'Activation link is not valid.';
            } else {
               $message = "Account with email $email is already activated.";
            }
        }

        return view('accountactivate', [
           'message' => $message,
           'success' => $success
        ]);
    }
}