<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserApperanceSettingController extends Controller
{
    /**
     * Render setting page and return user personalization setting data
     */
    public function index()
    {
        return response()->view('user.settings.apperance', [
            'title' => 'Apperance setting',
            'apperance' => $this->getApperanceData()
        ]);
    }

    /**
     * Update user apperance setting
     */
    public function update(Request $request)
    {
        $validatedFormData = $this->validateData($request);
        $personalization = json_decode(Auth::user()->personalization);
        $personalization->apperance->theme = $validatedFormData['apperance'];

        $affectedRow = User::where('id', Auth::user()->id)->update([
            'personalization' => json_encode($personalization)
        ]);

        if ($affectedRow === 1) {
            return redirect('/user/setting/apperance', 302)->with('flashMessage', 'Change saved!');
        }

        return redirect('/user/setting/apperance', 302)->with('flashMessage', 'Something wrong when save change, please try again');
    }

    /**
     * Return apperance setting data
     */
    private function getApperanceData()
    {
        return json_decode(Auth::user()->personalization, true)['apperance'];
    }

    /**
     * Validate apperance setting data
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'apperance' => ['required', 'present', 'filled', 'string', Rule::in(['dark', 'light'])]
        ]);
    }
}
