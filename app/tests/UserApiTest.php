<?php

use App\Services\JWTService;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserApiTest extends TestCase
{
    use DatabaseTransactions;

    private $token;

    public function setUp()
    {
        parent::setUp();

        $user = factory(\App\User::class)->create();

        $this->token = app(JWTService::class)->getJWT($user);
    }

    public function testCreateUser()
    {
        $userInfo = [
            'name' => 'Shleif91',
            'email' => 'shyherpunk@gmail.com',
            'password' => '123456'
        ];

        $this->post('/api/users', $userInfo, ['token' => $this->token])
            ->seeJsonContains([
                'name' => 'Shleif91',
                'email' => 'shyherpunk@gmail.com',
            ]);

        $this->assertEquals(Response::HTTP_CREATED, $this->response->getStatusCode());
    }

    public function testCreateUserWithoutName()
    {
        $userInfo = [
            'email' => 'shyherpunk@gmail.com',
            'password' => '123456',
        ];

        $this->post('/api/users', $userInfo, ['token' => $this->token])
            ->seeJsonEquals([
                'name' => ['The name field is required.']
            ]);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response->getStatusCode());
    }

    public function testGetUserById()
    {
        $user = factory('App\User')->create();
        $this->get(sprintf('/api/users/', $user->id), ['token' => $this->token])
            ->seeJsonContains([
                'name' => $user->name,
                'email' => $user->email,
                'last_login_at' => $user->last_login_at,
            ]);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }
}