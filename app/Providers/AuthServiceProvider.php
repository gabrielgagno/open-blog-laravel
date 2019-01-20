<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\Post'  => 'App\Policies\PostPolicy',
        'App\User'  => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Gate::define('posts', 'App\Policies\PostPolicy', [
            'manage-other' => 'manageOther',
            'create-for-other-user' => 'createForOtherUser'
        ]);

        Gate::define('user', 'App\Policies\UserPolicy', [
            'manage-other' => 'manageOther',
        ]);
    }
}
