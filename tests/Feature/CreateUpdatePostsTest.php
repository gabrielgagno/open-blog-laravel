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
    private $postUser;
    private $postUser2;

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

        $this->postUser = $this->user->posts()->first();
        $this->postUser2 = $this->user2->posts()->first();
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

    public function testUpdatePostUnauthenticated()
    {
        $id = $this->postUser->id;
        $response = $this->json('PUT', "api/posts/$id");

        $response->assertStatus(401);
    }

    public function testUpdatePostAuthenticated()
    {
        Passport::actingAs($this->user, ['api']);
        $id = $this->postUser->id;
        $response = $this->json('PUT', "api/posts/$id", [
            'title' => 'create test',
            'body'  => 'body test',
            'status' => 'draft',
            'user_id' => $this->user->id,
            'category' => 'samplecat',
        ]);
        $response->assertStatus(200);
    }

    public function testUpdatePostWithoutTitle()
    {
        Passport::actingAs($this->user, ['api']);
        $id = $this->postUser->id;
        $response = $this->json('PUT', "api/posts/$id", [
            'body'  => 'body test',
            'status' => 'draft',
            'user_id' => $this->user->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(422);
    }

    public function testUpdatePostWithoutBody()
    {
        Passport::actingAs($this->user, ['api']);
        $id = $this->postUser->id;
        $response = $this->json('PUT', "api/posts/$id", [
            'title' => 'test',
            'status' => 'draft',
            'user_id' => $this->user->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(422);
    }

    public function testUpdatePostWithoutStatus()
    {
        Passport::actingAs($this->user, ['api']);
        $id = $this->postUser->id;
        $response = $this->json('PUT', "api/posts/$id", [
            'title' => 'title',
            'body'  => 'body test',
            'user_id' => $this->user->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(422);
    }

    public function testUpdatePostDifferentUserIdRoleUser()
    {
        Passport::actingAs($this->user, ['api']);
        $id = $this->postUser2->id;
        $response = $this->json('PUT', "api/posts/$id", [
            'title' => 'create test',
            'body'  => 'body test',
            'status' => 'draft',
            'user_id' => $this->user2->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(401);
    }

    public function testUpdatePostDifferentUserIdRoleManager()
    {
        Passport::actingAs($this->manager, ['api']);
        $id = $this->postUser->id;
        $response = $this->json('PUT', "api/posts/$id", [
            'title' => 'create test',
            'body'  => 'body test',
            'status' => 'draft',
            'user_id' => $this->user->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(200);
    }

    public function testUpdatePostDifferentUserIdRoleAdmin()
    {
        Passport::actingAs($this->admin, ['api']);
        $id = $this->postUser->id;
        $response = $this->json('PUT', "api/posts/$id", [
            'title' => 'create test',
            'body'  => 'body test',
            'status' => 'draft',
            'user_id' => $this->user2->id,
            'category' => 'samplecat',
        ]);

        $response->assertStatus(200);
    }
}
