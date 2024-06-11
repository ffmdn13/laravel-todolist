<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        return response()->view('login');
    }

    public function doLogin()
    {
        $validatedData = $this->request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', Password::min(8)->letters()->numbers()]
        ]);

        if (Auth::attempt($validatedData)) {
            Session::regenerate(true);
            Session::regenerateToken(true);

            return redirect()->intended('/dashboard', 302);
        }

        return back(302)
            ->withErrors(['loginFailed' => 'Unable to login, please try again']);
    }
}
