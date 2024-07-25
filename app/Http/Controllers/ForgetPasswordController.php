<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ForgetPasswordController extends Controller
{
    /**
     * Render user forget password form
     */
    public function index()
    {
        return view('user.forget-password.forget-password');
    }

    /**
     * Handle forget password form subsmission
     */
    public function reset(Request $request)
    {
        $validatedData = $this->validatedData($request);
        $affectedRow = User::where('email', $validatedData['email'])->update([
            'password' => Hash::make($validatedData['password'])
        ]);

        if ($affectedRow === 1) {
            return redirect()->route('login')->with('flashMessage', 'Your email password successfully reset!');
        }

        return redirect()->route('login')->with('flashMessage', 'There was an error occur');
    }

    /**
     * Validate form submission data
     */
    private function validatedData(Request $request)
    {
        return $request->validate([
            'email' => ['required', 'string', 'max:255', 'present', 'filled', 'exists:users,email'],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers(), 'present', 'filled', 'confirmed'],
            'password_confirmation' => ['required', 'string', Password::min(8)->letters()->numbers(), 'present', 'filled']
        ]);
    }
}
