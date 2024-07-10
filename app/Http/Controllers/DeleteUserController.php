<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DeleteUserController extends Controller
{
    /**
     * Deleting user from database
     */
    public function delete(Request $request)
    {
        $validatedFormData = $this->validateData($request);
        $originPassword = Auth::user()->password;

        if (Hash::check($validatedFormData['password'], $originPassword)) {
            $userId = Auth::user()->id;
            $affectedRow = User::where('id', $userId)->delete();

            if ($affectedRow === 1) {
                $request->session()->invalidate();
                $request->session()->regenerate(true);

                return redirect('/login', 302)->with('registerSuccess', 'Your account has been deleted permanently!');
            }

            return redirect('/user/profile', 302)->with('flashMessage', 'There is something wrong when deleting your account, please try again!');
        }

        return redirect('/user/profile', 302)->with('flashMessage', 'Your password is incorrect, please try again');
    }

    /**
     * Validate form data
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'password' => ['required', 'present', 'filled', 'string', Password::min(8)->letters()->numbers()],
            'reason_text' => ['required', 'present', 'filled', 'string', 'max:1000'],
            'advice_text' => ['nullable', 'string', 'max:2500']
        ]);
    }
}
