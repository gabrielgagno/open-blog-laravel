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
        return $user->hasAccess('view-posts');
    }

    public function manageOther(User $user) {
        return $user->role['role'] != 'user';
    }

    public function create(User $user) {
        $hasAccess = $user->hasAccess('create-posts');
        return $hasAccess;
    }

    public function createForOtherUser(User $user, Post $post) {
        if($this->manageOther($user)) {
            return true;
        }
        return $user->id == $post->user_id;
    }

    public function update(User $user, Post $post) {
        $hasAccess = $user->hasAccess('update-posts');
        if($this->manageOther($user)) {
            return $hasAccess;
        }

        return $user->id == $post->user_id;
    }

    public function delete(User $user, Post $post) {
        $hasAccess = $user->hasAccess('delete-posts');
        if($user->can('manage-other')) {
            return $hasAccess;
        }

        return $user->id == $post->user_id;
    }
}
