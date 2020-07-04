<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAvatarRequest;
use App\User;

class UserAvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(UserAvatarRequest $request, User $user)
    {
        $user->update([
            'avatar_path' => $avatar = $request->file('avatar')->store('avatars', 'public')
        ]);

        return $request->expectsJson() ?
            response(['path' => $avatar], 200) :
            back()->with('flash', 'your avatar has been updated successfully');
    }
}
