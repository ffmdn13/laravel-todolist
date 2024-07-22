<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordController extends Controller
{
    /**
     * Render user update password page
     */
    public function updatePassword()
    {
        return response()->view('user.password', [
            'title' => 'Change your password',
            'user' => Auth::user(),
            'theme' => json_decode(Auth::user()->personalization)->apperance->theme
        ]);
    }
    /**
     * Update old password by the given password
     */
    public function update(Request $request)
    {
        $validatedFormData = $this->validateData($request);
        $oldPassword = Auth::user()->password;

        if (Hash::check($validatedFormData['old_password'], $oldPassword)) {
            $validatedFormData['new_password'] = Hash::make($validatedFormData['new_password']);
            $userId = Auth::user()->id;
            $affectedRow = User::where('id', $userId)->update([
                'password' => $validatedFormData['new_password']
            ]);

            if ($affectedRow === 1) {
                return redirect('/user/profile', 302)->with('flashMessage', 'Successfully change password');
            }

            return redirect('/user/profile', 302)->with('flashMessage', 'There is something wrong when changing your password, please try again');
        }

        return redirect($request->session()->previousUrl(), 302)->with('flashMessage', 'Your old password is incorrect');
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            'old_password' => ['required', 'present', 'filled', 'string', Password::min(8)->letters()->numbers()],
            'new_password' => ['required', 'present', 'filled', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
            'new_password_confirmation' => ['required', Password::min(8)->letters()->numbers()]
        ]);
    }
}
