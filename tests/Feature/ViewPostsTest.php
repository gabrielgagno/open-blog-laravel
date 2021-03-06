<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Laravel\Passport\Passport;
use Carbon\Carbon;

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

    public function testPostsGetNoParamsUser()
    {
        Passport::actingAs($this->user, ['api']);

        $response = $this->json('GET', 'api/posts');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'metadata',
            'data'
        ]);
        $response->assertJsonCount(13, 'data');
    }

    public function testPostsGetNoParamsManager()
    {
        Passport::actingAs($this->manager, ['api']);

        $response = $this->json('GET', 'api/posts');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'metadata',
            'data'
        ]);
        $response->assertJsonCount(52, 'data');
    }

    public function testPostsGetCategory1Param()
    {
        Passport::actingAs($this->user, ['api']);

        $response = $this->json('GET', 'api/posts?category=category1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'metadata',
            'data'
        ]);
        $response->assertJsonCount(6, 'data');
    }

    public function testPostsGetCategory2Param()
    {
        Passport::actingAs($this->user, ['api']);

        $response = $this->json('GET', 'api/posts?category=category2');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'metadata',
            'data'
        ]);
        $response->assertJsonCount(7, 'data');
    }

    public function testPostsGetStatusPublished()
    {
        Passport::actingAs($this->user, ['api']);

        $response = $this->json('GET', 'api/posts?status=published');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'metadata',
            'data'
        ]);
        $response->assertJsonCount(8, 'data');
    }

    public function testPostsGetStatusDraft()
    {
        Passport::actingAs($this->user, ['api']);

        $response = $this->json('GET', 'api/posts?status=draft');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'metadata',
            'data'
        ]);
        $response->assertJsonCount(3, 'data');
    }

    public function testPostsGetStatusArchived()
    {
        Passport::actingAs($this->user, ['api']);

        $dateFrom = date('Y-m-d');

        $response = $this->json('GET', 'api/posts?status=archived');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'metadata',
            'data'
        ]);
        $response->assertJsonCount(2, 'data');
    }

    public function testPostsGetPubishedAtWithinRange()
    {
        Passport::actingAs($this->user, ['api']);

        $currentDate = now();
        $dateFrom = now()->subDays(8)->toDateString();
        $dateTo = now()->addDays(8)->toDateString();

        $response = $this->json('GET', "api/posts?published_date_from=$dateFrom&published_date_to=$dateTo");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'metadata',
            'data'
        ]);
        $response->assertJsonCount(8, 'data');
    }

    public function testPostsGetPubishedAtNotInRange()
    {
        Passport::actingAs($this->user, ['api']);

        $currentDate = now();
        $dateFrom = now()->subDays(8)->toDateString();
        $dateTo = now()->subDays(4)->toDateString();
        $response = $this->json('GET', "api/posts?published_date_from=$dateFrom&published_date_to=$dateTo");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'metadata',
            'data'
        ]);
        $response->assertJsonCount(0, 'data');
    }
}
