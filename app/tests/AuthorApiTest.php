<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthorApiTest extends TestCase
{
    use DatabaseTransactions;

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

        $this->post('/api/authors', $userInfo)
            ->seeJsonContains($userInfo);

        $this->assertEquals(Response::HTTP_CREATED, $this->response->getStatusCode());
    }

    public function testCreateAuthorWithoutName()
    {
        $userInfo = [
            'email' => 'shyherpunk@gmail.com',
            'location' => 'Belarus',
        ];

        $this->post('/api/authors', $userInfo)
            ->seeJsonEquals([
                'name' => ['The name field is required.']
            ]);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response->getStatusCode());
    }

    public function testGetAuthorById()
    {
        $author = factory('App\Author')->create();
        $this->get(sprintf('/api/authors/', $author->id))
            ->seeJsonContains([
                'name' => $author->name,
                'email' => $author->email,
                'location' => $author->location,
            ]);

        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }
}
