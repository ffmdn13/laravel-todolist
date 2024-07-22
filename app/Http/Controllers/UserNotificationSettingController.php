<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationSettingController extends Controller
{
    /**
     * Render setting page and return user notification setting data
     */
    public function index()
    {
        $personalization = $this->getPersonalization();

        return response()->view('user.settings.notification', [
            'title' => 'Notification setting',
            'notification' => $personalization->notification,
            'theme' => $personalization->apperance->theme
        ]);
    }

    /**
     * Update user notification setting
     */
    public function update(Request $request)
    {
        $validatedFormData = $this->validateData($request);
        $personalization = json_decode(Auth::user()->personalization);
        $personalization->notification->notify_missed_task = $validatedFormData['notify_missed_task'];

        $affectedRow = User::where('id', Auth::user()->id)->update([
            'personalization' => json_encode($personalization)
        ]);

        if ($affectedRow === 1) {
            return redirect('/user/setting/notification', 302)->with('flashMessage', 'Change saved!');
        }

        return redirect('/user/setting/notification', 302)->with('flashMessage', 'Something wrong whens saving change, please try again!');
    }

    /**
     * Return user personalization data
     */
    private function getPersonalization()
    {
        return json_decode(Auth::user()->personalization);
    }

    /**
     * Validate user notification data setting
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'notify_missed_task' => ['required', 'boolean', 'present', 'filled']
        ]);
    }
}
