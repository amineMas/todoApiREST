<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class List2ControllerTest extends WebTestCase
{
    public function testgetListsAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/lists');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetListAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/lists', ['id' => '1']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetListsTasksAction()
    {
        $client = static::createClient();

        $client->request('GET', '/api/lists/1/tasks');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    // public function testDeleteListAction()
    // {
    //     $client = static::createClient();

    //     $client->request('DELETE', 'api/lists', ['id' => '2']);

    //     $this->assertEquals(204, $client->getResponse()->getStatusCode());
    // }

    // public function testpostListsAction()
    // {
    //     $client = static::createClient();

    //     $client->request('POST', '/api/lists', [
    //         'list' => 'test',
    //         ['CONTENT_TYPE' => 'application/json']
    //     ]);

    //     $this->assertEquals(200, $client->getResponse()->getStatusCode());
    // }
}