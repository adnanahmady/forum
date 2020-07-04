<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * check for having permission to upload files
     *
     * @param User $user
     * @param User $profileUser
     *
     * @return bool
     */
    public function upload(User $user, User $profileUser)
    {
        return $user->id === $profileUser->id;
    }
}
