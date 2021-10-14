<?php
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Todo;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fetch_todos()
    {
        $todos = Todo::factory()->count(5)->create();

        $response = $this->getJson('/api/todos');

        $response
            ->assertStatus(200)
            ->assertJsonCount(5)
            ->assertJsonFragment([
                'id' => $todos->first()->id,
                'title' => $todos->first()->title,
                'completed' => $todos->first()->completed,
            ]);
    }
}
