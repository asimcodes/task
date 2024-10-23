<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_the_task_listing_page()
    {
        // Make a GET request to the root URL
        $response = $this->get('/');

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);
    }
}
