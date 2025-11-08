<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Get token
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->token = $response->json('token');
    }

    public function test_can_list_tasks_with_pagination_and_filter()
    {
        // Create 20 tasks
        Task::factory(20)->create(['user_id' => $this->user->id]);

        // Create 5 completed tasks
        Task::factory(5)->create([
            'user_id' => $this->user->id,
            'status' => 'completed'
        ]);

        // Test pagination (10 per page)
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/tasks?per_page=10&page=1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'pagination' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page',
                ]
            ])
            ->assertJsonCount(10, 'data');

        // Test filter by status
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/tasks?status=completed');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_can_create_task()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/tasks', [
                'title' => 'New Task',
                'status' => 'pending',
                'description' => 'Test description'
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'New Task',
                'status' => 'pending'
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'user_id' => $this->user->id
        ]);
    }
}