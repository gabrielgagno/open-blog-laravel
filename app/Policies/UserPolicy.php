<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        return $user->hasAccess('view-users');
    }

    public function manageOther(User $user) {
        return $user->role->role == 'admin';
    }

    public function create(User $user) {
        $hasAccess = $user->hasAccess('create-users');
        if($this->manageOther($user)) {
            return $hasAccess;
        }
        return false;
    }

    public function update(User $user, User $userSubject) {
        $hasAccess = $user->hasAccess('update-users');
        if($this->manageOther($user)) {
            return $hasAccess;
        }

        return false;
    }

    public function delete(User $user, Post $post) {
        $hasAccess = $user->hasAccess('delete-users');
        if($user->can('manage-other')) {
            return $hasAccess;
        }

        return false;
    }
}
