<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthorApiTest extends TestCase
{
    public function testCreateAuthor()
    {
        $userInfo = [
            'name' => 'Oleg',
            'email' => 'shyherpunk@gmail.com',
            'twitter' => 'shyher',
            'github' => 'Shleif91',
            'latest_article_published' => 'Test article',
            'location' => 'Belarus',
        ];

        $this->post('/api/authors', $userInfo);

        $responseData = json_decode($this->response->getContent(), true);
        $this->assertEquals(Response::HTTP_CREATED, $this->response->getStatusCode());
        $this->assertEquals($userInfo['name'], $responseData['name']);
        $this->assertEquals($userInfo['email'], $responseData['email']);
    }
}
