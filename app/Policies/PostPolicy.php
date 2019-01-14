<?php

namespace App\Policies;

use App\User;
use App\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user) {
        $permission = $user->role->permissions()->where('permission_key', 'view-posts')->first();
        return isset($permission) ? true : false;
    }

    public function manageOther(User $user) {
        $permission = $user->role->permissions()->where('permission_key', 'view-posts')->first();

        if(isset($permission) && $user->role['role'] != 'user') {
            return true;
        }
        return false;
    }

    public function create(User $user) {
        $userRole = $user->role;
    }

    public function update(User $user, Post $post) {
        $userRole = $user->role;
    }

    public function delete(User $user, Post $post) {
        $userRole = $user->role;
    }
}
