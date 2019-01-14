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
// TODO: call this->manageOther in these methods
    public function view(User $user) {
        return $user->hasAccess('view-posts');
    }

    public function manageOther(User $user) {
        return $user->role['role'] != 'user';
    }

    public function create(User $user) {
        $hasAccess = $user->hasAccess('create-posts');
        return $hasAccess;
    }

    public function update(User $user, Post $post) {
        $hasAccess = $user->hasAccess('update-posts');
        return $hasAccess;
    }

    public function delete(User $user, Post $post) {
        $hasAccess = $user->hasAccess('update-posts');
        return $hasAccess;
    }
}
