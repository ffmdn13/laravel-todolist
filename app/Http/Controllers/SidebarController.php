<?php

namespace App\Http\Controllers;

use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SidebarController extends Controller
{
    /**
     * Add new task
     */
    public function add(Request $request)
    {
        $validatedData = $this->validateData($request);
        $validatedData['type'] = 'task';
        $validatedData['user_id'] = Auth::user()->id;

        TaskNote::create($validatedData);

        return redirect($request->session()->previousUrl(), 302)->with('flashMessage', 'New task successfully added');
    }

    /**
     * Validate form submission data
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['required', Rule::in(['0', '1', '2', '3'])],
        ]);
    }
}
