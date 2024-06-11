<?php

namespace App\Http\Controllers;

use App\Models\Lists;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardListController extends Controller
{
    /**
     * Render dashboard list page with given id parameter
     */
    public function index($id = 0)
    {
        return response()->view('dashboard.list', [
            'title' => $this->getPageTitle(2),
            'tasks' => ''
        ]);
    }

    /**
     * Get page name that user currently visit
     */
    private function getPageTitle($index = 1)
    {
        return ucfirst(explode('/', request()->getRequestUri())[$index]);
    }

    /**
     * Add new list
     */
    public function add(Request $request)
    {
        $validatedData = $request->validate(['title' => ['required', 'present', 'string', 'max:255']]);
        $validatedData['user_id'] = Auth::user()->id;

        Lists::create($validatedData);

        return redirect('/dashboard', 302)
            ->with('message', 'Successfully added list "' . $validatedData['title'] . '"');
    }
}
