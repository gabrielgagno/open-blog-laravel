<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Laravel\Passport\Passport;

class ViewPostsTest extends TestCase
{
    private $user;
    private $user2;
    private $manager;
    private $admin;

    use RefreshDatabase;

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

    public function testPostsGetUnauthenticated()
    {
        $response = $this->json('GET', 'api/posts');

        $response->assertStatus(401);
    }

    public function testPostsGetAuthenticatedUser()
    {
        Passport::actingAs($this->user, ['api']);

        $response = $this->json('GET', 'api/posts');

        $response->assertStatus(200);
    }

    public function testPostsGetAuthenticatedManager()
    {
        Passport::actingAs($this->manager, ['api']);

        $response = $this->json('GET', 'api/posts');

        $response->assertStatus(200);
    }

    public function testPostsGetAuthenticatedAdmin()
    {
        Passport::actingAs($this->admin, ['api']);

        $response = $this->json('GET', 'api/posts');

        $response->assertStatus(200);
    }

    public function testPostsGetNoParams()
    {
        Passport::actingAs($this->user, ['api']);

        $response = $this->json('GET', 'api/posts');

        $response->assertStatus(200);
    }
}
