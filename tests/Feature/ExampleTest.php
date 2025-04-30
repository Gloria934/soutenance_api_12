<?php

namespace Tests\Feature;

<<<<<<< HEAD
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
=======
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
<<<<<<< HEAD
     *
     * @return void
     */
    public function testBasicTest()
=======
     */
    public function test_the_application_returns_a_successful_response(): void
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
