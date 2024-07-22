<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserDatetimeSettingController extends Controller
{
    /**
     * Render setting page and return user datetime setting data
     */
    public function index()
    {
        $personalization = $this->getPersonalization();

        return response()->view('user.settings.datetime', [
            'title' => 'Datetime setting',
            'datetime' => $personalization->datetime,
            'theme' => $personalization->apperance->theme
        ]);
    }

    /**
     * Update user datetime setting
     */
    public function update(Request $request)
    {
        $validatedFormData = $this->validateData($request);
        $personalization = json_decode(Auth::user()->personalization);

        $personalization->datetime->time_format = $validatedFormData['time_format'];
        $personalization->datetime->default_date = $validatedFormData['default_date'];
        $personalization->datetime->timezone = $validatedFormData['timezone'];

        $affectedRow = User::where('id', Auth::user()->id)->update([
            'personalization' => json_encode($personalization)
        ]);

        if ($affectedRow === 1) {
            return redirect('/user/setting/datetime', 302)->with('flashMessage', 'Change saved!');
        }

        return redirect('/user/setting/datetime', 302)->with('flashMessage', 'Something wrong when saving, please try again!');
    }

    /**
     * Return user personlization data
     */
    private function getPersonalization()
    {
        return json_decode(Auth::user()->personalization);
    }

    /**
     * Validate datetime data setting
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'time_format' => ['required', 'present', 'filled', 'string', Rule::in(['24hr', '12hr'])],
            'default_date' => ['required', 'present', 'filled', 'string', Rule::in(['today', 'tomorrow', 'day_after_tomorrow'])],
            'timezone' => ['required', 'present', 'filled', 'string', Rule::in(['Asia/Jakarta', 'Asia/Seoul', 'Asia/Pontianak', 'Asia/Singapore', 'UTC'])]
        ]);
    }
}
