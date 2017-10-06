<?php

use App\Listeners\UserListerner;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Jobimarklets\Logic\UserLogic;
use MailThief\Message;
use MailThief\Testing\InteractsWithMail;

/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: AuthenticationTest.php
 * Date: 22/04/2017
 * Time: 03:25
 */
class AuthenticationTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations, InteractsWithMail;

    public function testApplicationLoads()
    {
        $this->get('/')
            ->assertResponseOk();
    }

    private function getUserListenerMock()
    {
        $listener = $this->getMockBuilder(UserListerner::class)
            ->setMethods(['generateChecksum'])
            ->setConstructorArgs([$this->app->make(UserLogic::class)])
            ->getMock();
        $listener->method('generateChecksum')
            ->willReturn('XXX');

        return $listener;
    }

    /**
     * @param $name
     * @param $email
     * @param $password
     * @param $code
     * @dataProvider registrationDataWithCode
     */
    public function testUserRegistrationReturnsExpectedCode($name, $email, $password, $code)
    {
        $user = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'enabled' => true,
        ];

        $this->json('POST', '/api/auth/create', $user)->seeStatusCode($code);
    }

    public function registrationDataWithCode()
    {
        return [
            ['', 'user1@mail.com', 'password1d', 401],
            ['', '', '', 401],
            ['user', 'user2@mail.com', '', 401],
            ['user', '', 'password1sadf', 401],
            ['user', 'user3@mail.com', 'password1', 200]
        ];
    }

    public function testAuthentication()
    {
        $users = [
            'name' => 'user',
            'email' => 'user@mail.com',
            'password' => 'password1',
            'enabled' => true,
        ];

        $this->json('POST', '/api/auth/create', $users)->assertResponseOk();

        $this->json(
            'POST',
            '/api/auth',
            ['email' => 'user@mail.com', 'password' => 'password1']
        )->assertNotEmpty($this->response->getContent());
    }

    public function testFetchingDetailsOfAuthenticatedUser()
    {
        $users = [
            'name' => 'user',
            'email' => 'user@mail.com',
            'password' => 'password1',
            'enabled' => true,
        ];

        $this->json('POST', '/api/auth/create', $users)->assertResponseOk();

        $this->json(
            'POST',
            '/api/auth',
            ['email' => 'user@mail.com', 'password' => 'password1']
        )->assertResponseOk();

        $this->json('GET', '/api/auth', [], ['api_token' => $this->response->getContent()])
        ->seeJson([
                'id' => 1,
                'email' => 'user@mail.com',
                'name' => 'user',
            ]
        );
    }

    public function testLogout()
    {
        $users = [
            'name' => 'user',
            'email' => 'user@mail.com',
            'password' => 'password1',
            'enabled' => true,
        ];

        $this->json('POST', '/api/auth/create', $users)->assertResponseOk();

        $this->json(
            'POST',
            '/api/auth',
            ['email' => 'user@mail.com', 'password' => 'password1']
        )->assertResponseOk();

        $token = $this->response->getContent();

        $this->json('GET', '/api/auth', [], [
            'api_token' => $token
            ]
        )
        ->seeJson([
            'id' => 1,
            'email' => 'user@mail.com',
            'name' => 'user',
        ])
        ->json('GET', '/api/auth/logout', [],
            ['api_token' => $token]
        )
        ->assertResponseOk();
    }

    public function testUnregisterUser()
    {
        $users = [
            'name' => 'user',
            'email' => 'user@mail.com',
            'password' => 'password1',
            'enabled' => true,
        ];

        $this->json('POST', '/api/auth/create', $users)->assertResponseOk();

        $this->json(
            'POST',
            '/api/auth',
            ['email' => 'user@mail.com', 'password' => 'password1']
        )->assertResponseOk();

        $token = $this->response->getContent();

        $this->json('DELETE', '/api/auth/delete', [],
            ['api_token' => $token]
        )->assertResponseStatus(204);

        $this->seeMessageFor($users['email']);
        $this->seeInSubjects('Account Deleted Notification');

    }

    public function testUpdateUserPassword()
    {
        $users = [
            'name' => 'user',
            'email' => 'user@mail.com',
            'password' => 'password1',
            'enabled' => true,
        ];

        $this->json('POST', '/api/auth/create', $users)->assertResponseOk();

        $this->json(
            'POST',
            '/api/auth',
            ['email' => 'user@mail.com', 'password' => 'password1']
        )->assertResponseOk();

        $token = $this->response->getContent();

        $this->json('GET', '/api/auth', [], [
                'api_token' => $token
            ]
        )->assertResponseOk();

        $this->json('POST', '/api/auth/update/1',
            ['password' => 'password2'],
            ['api_token' => $token]
        )
        ->assertResponseOk();
    }

    public function testEmailSentAfterRegistration()
    {
        $user = [
            'name'     => 'user',
            'email'    => 'user@gmail.com',
            'password' => 'password1',
            'enabled'  => false,
        ];

        $this->json('POST', '/api/auth/create', $user)->assertResponseOk();
        $this->seeMessageFor('user@gmail.com');
        $this->seeInSubjects('Welcome to JobiMarklets');
    }

    public function testEmailActivationCodeWorks()
    {
        $user = [
            'name'     => 'user',
            'email'    => 'user@gmail.com',
            'password' => 'password1',
            'enabled'  => false,
        ];

        $listener = $this->getUserListenerMock();

        $this->app->instance(UserListerner::class, $listener);

        $this->json('POST', '/api/auth/create', $user)->assertResponseOk();
        $this->seeMessageFor('user@gmail.com');

        $this->assertNotNull(Cache::get('XXX'));
        $this->assertArrayHasKey('user', Cache::get('XXX'));
        $cachedData = Cache::get('XXX');
        $this->assertSame(1, $cachedData['user']);
    }

    public function testEmailActivationLinkWorks()
    {
        $user = [
            'name'     => 'user',
            'email'    => 'user@gmail.com',
            'password' => 'password1',
            'enabled'  => false,
        ];

        $listener = $this->getUserListenerMock();

        $this->app->instance(UserListerner::class, $listener);

        $this->json('POST', '/api/auth/create', $user)->assertResponseOk();
        $this->seeMessageFor('user@gmail.com');

        $this->get('/account/activate/XXX')->assertContains(
            'activated', $this->response->content()
        );
    }

    public function testEmailActivationExpiredLink()
    {
        $user = [
            'name'     => 'user',
            'email'    => 'user@gmail.com',
            'password' => 'password1',
            'enabled'  => false,
        ];

        $listener = $this->getUserListenerMock();

        $this->app->instance(UserListerner::class, $listener);

        $this->json('POST', '/api/auth/create', $user)->assertResponseOk();
        Cache::forget('XXX');

        $this->get('/account/activate/XXX')->assertContains(
            'expired', $this->response->content()
        );
    }

    public function testResendActivationAfterFailedActivation()
    {
        $user = [
            'name'     => 'user',
            'email'    => 'user@gmail.com',
            'password' => 'password1',
            'enabled'  => false,
        ];

        $listener = $this->getUserListenerMock();

        $this->app->instance(UserListerner::class, $listener);

        $this->json('POST', '/api/auth/create', $user)->assertResponseOk();
        Cache::forget('XXX');

        $this->get('/account/activate/XXX')->assertContains(
            'invalid', $this->response->content()
        );

        $this->post('/account/reactivate', ['email' => 'user@gmail.com']);
        $this->seeMessageFor('user@gmail.com');
    }

    public function testResendActivationWorks()
    {
        $user = [
            'name'     => 'user',
            'email'    => 'user@gmail.com',
            'password' => 'password1',
            'enabled'  => false,
        ];

        $listener = $this->getUserListenerMock();
        $this->app->instance(UserListerner::class, $listener);

        $this->json('POST', '/api/auth/create', $user)
            ->assertResponseOk();

        Cache::forget('XXX');

        $this->get('/account/activate/XXX')->assertContains(
            'invalid', $this->response->content()
        );

        $this->post('/account/reactivate', ['email' => 'user@gmail.com'])
            ->assertContains('email sent', $this->response->content());
    }
}