<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testDate()
    {
        echo getLastEdited(20970715);

        $this->assertTrue(true);
    }

    public function testHttpClient()
    {
        $url = 'https://randomuser.me/api/?nat=in';
        $response = Http::get($url)->json();

        $gender = $response['results'][0]['gender'];
        $title = $response['results'][0]['name']['title'];
        $firstName = $response['results'][0]['name']['first'];
        $lastName = $response['results'][0]['name']['last'];

        echo "Name = " . $firstName . ' ' . $lastName;

        $this->assertTrue(true);
    }
}
