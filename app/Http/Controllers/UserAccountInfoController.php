<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAccountInfoController extends Controller
{
    /**
     * Render user profile page
     */
    public function index()
    {
        return response()->view('user.profile', [
            'title' => 'User Profile',
            'user' => Auth::user(),
            'theme' => json_decode(Auth::user()->personalization)->apperance->theme
        ]);
    }

    /**
     * Update user account info
     */
    public function updateAccountInfo(Request $request)
    {
        $validatedFormData = $request->validate([
            'nickname' => ['nullable', 'string', 'present', 'alpha_num']
        ]);
        $validatedFormData['new_profile'] = $this->handleUplodedNewProfile($request->file('new_profile', null));
        $userId = Auth::user()->id;

        if ($validatedFormData['new_profile']['valid'] === false) {
            return redirect($request->session()->previousUrl(), 302)->with('flashMessage', $validatedFormData['new_profile']['message']);
        }

        $affectedRow = User::where('id', $userId)
            ->update([
                'nickname' => $validatedFormData['nickname'],
                'profile' => $validatedFormData['new_profile']['path'] ?? Auth::user()->profile
            ]);

        if ($affectedRow === 1) {
            return redirect($request->session()->previousUrl(), 302)->with('flashMessage', 'Succesfully changing account info.');
        }

        return redirect($request->session()->previousUrl(), 302)->with('flashMessage', 'Something went wrong when uploading your profile');
    }

    /**
     * Validate new profile extension
     */
    private function validateNewProfileExtension($extension)
    {
        return in_array(strtolower($extension), ['image/jpeg', 'image/jpg', 'image/png']);
    }

    /**
     * Handle uploaded new profile
     */
    private function handleUplodedNewProfile($profile)
    {
        $responseTemplate = ['path' => null, 'valid' => false, 'message' => null];

        if (is_null($profile)) {
            $responseTemplate['valid'] = true;
            return $responseTemplate;
        }

        $extension = $this->validateNewProfileExtension($profile->getMimeType());
        $size = $profile->getSize();

        if ($extension === false) {
            $responseTemplate['message'] = 'Image extension must be jpeg, jpg or png';
            return $responseTemplate;
        }

        if ($size > 2000000) {
            $responseTemplate['message'] = 'Image size must be lower than 2MB';
            return $responseTemplate;
        }

        $responseTemplate['path'] = $profile->store('profiles', 'public');
        $responseTemplate['valid'] = true;
        return $responseTemplate;
    }
}
