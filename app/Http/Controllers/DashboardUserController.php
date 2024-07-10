<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardUserController extends Controller
{
    /**
     * Render user profile page
     */
    public function profile()
    {
        return response()->view('user.profile', [
            'title' => 'User Profile',
            'user' => Auth::user()
        ]);
    }

    /**
     * Render user setting page
     */
    public function setting()
    {
        return response()->view('user.setting');
    }

    /**
     * Render user update password page
     */
    public function updatePassword()
    {
        return response()->view('user.password', [
            'title' => 'Change your password',
            'user' => Auth::user()
        ]);
    }
}
