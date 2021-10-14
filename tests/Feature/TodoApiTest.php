<?php
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Todo;
use Spectator\Spectator;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_todos()
    {
        Spectator::using('TodoMVC.yaml');
        $this->withoutExceptionHandling();
        $todos = Todo::factory()->count(5)->create();

        $response = $this->getJson('/api/todos');

        $response
            ->assertValidRequest()
            ->assertValidResponse(200)
            ->assertJsonCount(5)
            ->assertJsonFragment([
                'id' => $todos->first()->id,
                'title' => $todos->first()->title,
                'completed' => $todos->first()->completed,
            ]);
    }

    public function test_create_todo()
    {
        Spectator::using('TodoMVC.yaml');
        $this->withoutExceptionHandling();

        $todo = ['completed' => true, 'title' => 'Turpis a adipiscing'];

        $response = $this->postJson('/api/todos', $todo);

        $response
            ->assertValidRequest()
            ->assertValidResponse(201);

        $this->assertDatabaseHas('todos', $todo);
    }

    public function test_update_todo()
    {
        Spectator::using('TodoMVC.yaml');
        $this->withoutExceptionHandling();

        $todo = Todo::factory()->create();

        $response = $this->patchJson("/api/todos/{$todo->id}", [
            'title' => $todo->title . 'abc',
        ]);

        $response
            ->assertValidRequest()
            ->assertValidResponse(200);

        $this->assertDatabaseHas('todos', [
            'title' => $todo->title . 'abc',
        ]);
    }

    public function test_delete_todo()
    {
        Spectator::using('TodoMVC.yaml');
        $this->withoutExceptionHandling();

        $todo = Todo::factory()->create();

        $response = $this->deleteJson("/api/todos/{$todo->id}");

        $response
            ->assertValidRequest()
            ->assertValidResponse(204);

        $this->assertDatabaseMissing('todos', [
            'title' => $todo->title,
        ]);
    }

    public function test_completed_must_be_boolean()
    {
        $todo = ['completed' => 'abc', 'title' => 'Donec est sed'];

        $response = $this->postJson('/api/todos', $todo);
        $response->assertStatus(422);

        $this->assertDatabaseMissing('todos', ['title' =>  'Donec est sed']);
    }
}
