<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Laravel\Passport\Passport;
use Carbon\Carbon;

class DeletePostsTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $user2;
    private $manager;
    private $admin;
    private $postUser;
    private $postUser2;
    private $postManager;

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
        $this->postManager = $this->manager->posts()->first();
    }

    public function testDeleteUnauthorized()
    {
        $id = $this->postUser->id;
        $response = $this->json('DELETE', "api/posts/$id");

        $response->assertStatus(401);
    }

    public function testDeleteAuthorizedUser()
    {
        Passport::actingAs($this->user, ['api']);
        $id = $this->postUser->id;
        $response = $this->json('DELETE', "api/posts/$id");
        $response->assertStatus(200);
    }

    public function testDeleteAuthorizedManager()
    {
        Passport::actingAs($this->manager, ['api']);
        $id = $this->postManager->id;
        $response = $this->json('DELETE', "api/posts/$id");

        $response->assertStatus(200);
    }

    public function testDeletePostOfOtherUserRoleManager()
    {
        Passport::actingAs($this->manager, ['api']);
        $id = $this->postUser->id;
        $response = $this->json('DELETE', "api/posts/$id");

        $response->assertStatus(200);
    }

    public function testDeletePostOfOtherUserRoleAdmin()
    {
        Passport::actingAs($this->admin, ['api']);
        $id = $this->postUser->id;
        $response = $this->json('DELETE', "api/posts/$id");

        $response->assertStatus(200);
    }

    public function testDeletePostOfOtherUserRoleUser()
    {
        Passport::actingAs($this->user, ['api']);
        $id = $this->postUser2->id;
        $response = $this->json('DELETE', "api/posts/$id");

        $response->assertStatus(401);
    }
}
