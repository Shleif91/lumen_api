<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function testAuthenticate()
    {
        factory('App\User')->create([
            'email' => 'testuser@example.com'
        ]);

        $userInfo = [
            'email' => 'testuser@example.com',
            'password' => '123456',
        ];

        $this->post('/api/login', $userInfo);
        $responseAsArray = json_decode($this->response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
        $this->assertTrue(key_exists('token', $responseAsArray));
    }

    public function testAuthenticateWithWrongEmail()
    {
        factory('App\User')->create([
            'email' => 'testuser@gmail.com'
        ]);

        $userInfo = [
            'email' => 'testuser@example.com',
            'password' => '123456',
        ];

        $this->post('/api/login', $userInfo)
            ->seeJsonEquals([
                'error' => ['Email does not exist.']
            ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->response->getStatusCode());
    }

    public function testAuthenticateWithWrongPassword()
    {
        factory('App\User')->create([
            'email' => 'testuser@example.com'
        ]);

        $userInfo = [
            'email' => 'testuser@example.com',
            'password' => '654321',
        ];

        $this->post('/api/login', $userInfo)
            ->seeJsonEquals([
                'error' => ['Email or password is wrong.']
            ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->response->getStatusCode());
    }

    public function testAuthenticateAndSetLastLogin()
    {
        factory('App\User')->create([
            'email' => 'testuser@example.com'
        ]);

        $userInfo = [
            'email' => 'testuser@example.com',
            'password' => '123456',
        ];

        $this->post('/api/login', $userInfo);
        $timestamp = \Carbon\Carbon::now();
        $responseAsArray = json_decode($this->response->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
        $this->assertTrue(key_exists('token', $responseAsArray));
        $this->seeInDatabase('users', ['last_login_at' => $timestamp]);
    }
}