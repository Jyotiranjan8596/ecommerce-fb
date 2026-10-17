<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('id="collapseOne" class="accordion-collapse collapse"', false)
            ->assertSee('data-bs-target="#collapseOne" aria-expanded="false"', false)
            ->assertSee('data-bs-target="#collapseTwo" aria-expanded="false"', false)
            ->assertSee('data-bs-target="#collapseThree" aria-expanded="false"', false)
            ->assertSee('data-bs-target="#collapseFour"', false)
            ->assertDontSee('freebazar-about-menu', false)
            ->assertDontSee('openAboutSection', false);
    }
}
