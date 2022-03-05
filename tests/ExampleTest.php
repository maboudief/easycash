<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }

    public function testListTransactions()
    {
        $this->json('GET', '/api/v1/transactaions')
             ->seeJson([
                'message' => 'Retrieved successfully',
             ]);
    }

}
