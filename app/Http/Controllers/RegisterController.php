<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Render register page
     */
    public function index()
    {
        return response()->view('register');
    }

    /**
     * Register new user account to database
     */
    public function doRegister()
    {
        $credentials = $this->validateData();

        $credentials['profile'] = 'default.jpg';
        $credentials['password'] = Hash::make($credentials['password']);
        $credentials['personalization'] = $this->userPersonalization();

        $credentials['date_created'] = now();
        User::create($credentials);

        return redirect()->route('login')->with('registerSuccess', 'Your account successfully created!');
    }

    /**
     * Validate form date
     */
    private function validateData()
    {
        return $this->request->validate([
            'nickname' => ['nullable', 'min:3', 'max:64'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
            'password_confirmation' => ['required', Password::min(8)->letters()->numbers()]
        ]);
    }

    /**
     * Get user default personalization
     */
    protected function userPersonalization()
    {
        $apperance = ['theme' => ['light', 'dark']];

        $datetime = [
            'time-format' => ['24-hr', '12-hour'],
            'time-zone' => 1
        ];
        $notification = [
            'daily' => 1,
            'alert-time' => '09:00',
            'email-notification' => 0
        ];

        $more = [
            'default-date' => ['today', 'tomorrow', 'day-after-tomorrow'],
            'default-reminder' => ['on-time', '5-minutes-ahead', '30-minutes-head'],
            'default-list' => 'inbox'
        ];

        return json_encode([
            'apperance' => ['theme' => ['light', 'dark']],
            'datetime' => $datetime,
            'notification' => $notification,
            'more' => $more
        ]);
    }
}
