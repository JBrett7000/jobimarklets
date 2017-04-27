<?php

/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: AuthenticationTest.php
 * Date: 22/04/2017
 * Time: 03:25
 */
class AuthenticationTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations;

    public function testApplicationLoads()
    {
        $this->get('/')
            ->assertResponseOk();
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

        $this->json('POST', '/auth/create', $user)->seeStatusCode($code);
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

        $this->json('POST', '/auth/create', $users)->assertResponseOk();

        $this->json(
            'POST',
            '/auth',
            ['email' => 'user@mail.com', 'password' => 'password1']
        )->assertNotEmpty($this->response->getContent());
    }

    public function testFetchingDetailsOfAuthenticatedUser()
    {
        $this->markTestIncomplete();
    }

    public function testLogout()
    {
        $this->markTestIncomplete();
    }

    public function testUnregisterUser()
    {
        $this->markTestIncomplete();
    }

    public function testUpdateUserPassword()
    {
        $this->markTestIncomplete();
    }
}