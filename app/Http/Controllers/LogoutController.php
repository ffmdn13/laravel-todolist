<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Logout user from application and flush all session data and
     * and regenreate csrf and session token along invalidating session data
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerate(true);
        $request->session()->regenerateToken();

        return redirect('/login', 302);
    }
}
