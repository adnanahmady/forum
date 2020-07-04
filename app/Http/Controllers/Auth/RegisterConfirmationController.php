<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function update()
    {
        try {
            User::where('confirm_token', request('token'))
                ->firstOrFail()
                ->confirm();
        } catch (\Exception $e) {
            return redirect('/threads')
                ->with('flash', 'Token is invalid!')
                ->with('level', 'danger');
        }

        return redirect('/threads')
            ->with('flash', 'Your account is successfully confirmed!');
    }
}
