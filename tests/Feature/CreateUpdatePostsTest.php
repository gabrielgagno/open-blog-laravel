<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Laravel\Passport\Passport;
use Carbon\Carbon;

class CreateUpdatePostsTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $user2;
    private $manager;
    private $admin;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed', [
            '--class' => 'TestingDatabaseSeeder'
        ]);
        $this->artisan('passport:install', ['--force' => true]);

        $this->user = factory(User::class)->states('user')->create();
        $this->user2 = factory(User::class)->states('user')->create();
        $this->manager = factory(User::class)->states('manager')->create();
        $this->admin = factory(User::class)->states('admin')->create();

        
    }

    public function testCreatePostUnauthenticated()
    {
        $response = $this->json('POST', 'api/posts');

        $response->assertStatus(401);
    }

    public function testCreatePostAuthenticated()
    {
        Passport::actingAs($this->user, ['api']);
        $response = $this->json('POST', 'api/posts', [
            'title' => 'create test',
            'body'  => 'body test',
            'status' => 'draft',
            'user_id' => $this->user->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(200);
    }

    public function testCreatePostWithoutTitle()
    {
        Passport::actingAs($this->user, ['api']);
        $response = $this->json('POST', 'api/posts', [
            'body'  => 'body test',
            'status' => 'draft',
            'user_id' => $this->user->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(422);
    }

    public function testCreatePostWithoutBody()
    {
        Passport::actingAs($this->user, ['api']);
        $response = $this->json('POST', 'api/posts', [
            'title' => 'test',
            'status' => 'draft',
            'user_id' => $this->user->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(422);
    }

    public function testCreatePostWithoutStatus()
    {
        Passport::actingAs($this->user, ['api']);
        $response = $this->json('POST', 'api/posts', [
            'title' => 'title',
            'body'  => 'body test',
            'user_id' => $this->user->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(422);
    }

    public function testCreatePostDifferentUserIdRoleUser()
    {
        Passport::actingAs($this->user, ['api']);
        $response = $this->json('POST', 'api/posts', [
            'title' => 'create test',
            'body'  => 'body test',
            'status' => 'draft',
            'user_id' => $this->user2->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(401);
    }

    public function testCreatePostDifferentUserIdRoleManager()
    {
        Passport::actingAs($this->manager, ['api']);
        $response = $this->json('POST', 'api/posts', [
            'title' => 'create test',
            'body'  => 'body test',
            'status' => 'draft',
            'user_id' => $this->user->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(200);
    }

    public function testCreatePostDifferentUserIdRoleAdmin()
    {
        Passport::actingAs($this->admin, ['api']);
        $response = $this->json('POST', 'api/posts', [
            'title' => 'create test',
            'body'  => 'body test',
            'status' => 'draft',
            'user_id' => $this->user2->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(200);
    }
}
