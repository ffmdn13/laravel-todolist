<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardTagsController extends Controller
{
    /**
     * Render dashboard task page
     */
    public function index(Request $request, $id, $title)
    {

        // make method validation to validate request id

        return response()->view('dashboard.tags', [
            'title' => $this->getPageTitle(2),
            'tagId' => $id,
            'tagTitle' => $title,
            'tasks' => $this->getTasks($id, Auth::user()->id),
            'preview' => $this->preview(),
            'color' => $request->query('clr', null)
        ]);
    }

    /**
     * Get page title from route request uri
     */
    private function getPageTitle($index = 0)
    {
        return ucfirst(explode('/', request()->getRequestUri())[$index]);
    }

    /**
     * Return tasks related to current tag
     */
    private function getTasks($tagId, $userId)
    {
        return TaskNote::select(['id', 'title', 'priority', 'reminder', 'due_date'])
            ->byTagAndUser($tagId, $userId)
            ->notCompleted()
            ->notTrashed()
            ->mustTask()
            ->get();
    }

    /**
     * Preview a given task that related to tag id
     */
    private function preview()
    {
        return null;
    }

    /**
     * Add new tag
     */
    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'present', 'string', 'max:255', 'unique:tags,title'],
            'color' => ['required', 'present', 'string', Rule::in(['black', 'blue', 'green', 'red', 'cyan', 'purple', 'orange'])]
        ]);
        $validatedData['user_id'] = Auth::user()->id;

        Tag::create($validatedData);

        return redirect('/dashboard', 302);
    }

    /**
     * Add new task to current tag
     */
    public function addTask(Request $request)
    {
        $validatedData = $request->validate([
            'id' => ['required', 'present', 'numeric', 'exists:tags,id'],
            'title' => ['required', 'present', 'string', 'max:255'],
            'priority' => ['required', 'present', Rule::in(['0', '1', '2', '3'])],
        ]);
        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['tag_id'] = $validatedData['id'];

        TaskNote::create($validatedData);

        return redirect($request->session()->previousUrl(), 302)
            ->with('message', 'Successfully added task "' . $validatedData['title'] . '"');
    }
}
