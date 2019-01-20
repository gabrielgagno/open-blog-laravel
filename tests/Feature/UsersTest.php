<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Laravel\Passport\Passport;
use Carbon\Carbon;

class UsersTest extends TestCase
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

    public function testUsersViewUnauthenticated()
    {
        $response = $this->json('GET', "api/users/");

        $response->assertStatus(401);
    }

    public function testUsersViewAuthenticatedRoleUser()
    {
        Passport::actingAs($this->user, ['api']);
        $response = $this->json('GET', "api/users/");

        $response->assertStatus(401);
    }

    public function testUsersViewAuthenticatedRoleManager()
    {
        Passport::actingAs($this->manager, ['api']);
        $response = $this->json('GET', "api/users/");

        $response->assertStatus(401);
    }

    public function testUsersViewAuthenticatedRoleAdmin()
    {
        Passport::actingAs($this->admin, ['api']);
        $response = $this->json('GET', "api/users/");

        $response->assertStatus(200);
    }

    public function testUsersCreateRoleUser()
    {
        Passport::actingAs($this->user, ['api']);
        $response = $this->json('POST', "api/users/", [
            'email' => 'pink@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'name' => 'test user',
            'role_id' => 1
        ]);
        $response->assertStatus(401);
    }

    public function testUsersCreateRoleManager()
    {
        Passport::actingAs($this->manager, ['api']);
        $response = $this->json('POST', "api/users/", [
            'email' => 'pink@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'name' => 'test user',
            'role_id' => 1
        ]);
        $response->assertStatus(401);
    }

    public function testUsersCreateRoleAdmin()
    {
        Passport::actingAs($this->admin, ['api']);
        $response = $this->json('POST', "api/users/", [
            'email' => 'pink@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'name' => 'test user',
            'role_id' => 1
        ]);
        
        $response->assertStatus(200);
    }

    public function testUsersUpdateRoleUser()
    {
        Passport::actingAs($this->user, ['api']);
        $id = $this->user->id;
        $response = $this->json('PUT', "api/users/$id", [
            'email' => 'pink@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'name' => 'test user',
            'role_id' => 1
        ]);
        $response->assertStatus(401);
    }

    public function testUsersUpdateRoleManager()
    {
        Passport::actingAs($this->manager, ['api']);
        $id = $this->user->id;
        $response = $this->json('PUT', "api/users/$id", [
            'email' => 'pink@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'name' => 'test user',
            'role_id' => 1
        ]);
        $response->assertStatus(401);
    }

    public function testUsersUpdateRoleAdmin()
    {
        Passport::actingAs($this->admin, ['api']);
        $id = $this->user->id;
        $response = $this->json('PUT', "api/users/$id", [
            'email' => 'pink@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'name' => 'test user',
            'role_id' => 1
        ]);
        
        $response->assertStatus(200);
    }
}
