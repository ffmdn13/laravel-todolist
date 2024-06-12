<?php

namespace App\Http\Controllers;

use App\Models\Lists;
use App\Models\TaskNote;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardListController extends Controller
{
    /**
     * Render dashboard list page with given id parameter
     */
    public function index($title, $id)
    {
        return response()->view('dashboard.list', [
            'title' => $this->getPageTitle(2),
            'listTitle' => $title,
            'list' => Lists::select(['id'])
                ->byUserAndId($id, Auth::user()->id)
                ->where('title', $title)
                ->firstOrFail(),
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

        return redirect('/dashboard', 302);
    }

    /**
     * Delete given list
     */
    public function delete(Request $request)
    {
        $validatedData = $request->validate(['id' => ['required', 'present', 'numeric', 'exists:lists,id']]);
        $userId = Auth::user()->id;

        Lists::byUserAndId($validatedData['id'], $userId)
            ->delete();

        TaskNote::where('user_id', $userId)
            ->where('list_id', $validatedData['id'])
            ->forceDelete();

        return redirect('/dashboard', 302);
    }
}
