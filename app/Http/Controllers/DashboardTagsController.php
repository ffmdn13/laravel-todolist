<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
            'preview' => $this->preview($request->query('preview', null), $id),
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

    private function preview($preview, $tagId)
    {
        $validator = Validator::make(['preview' => $preview], [
            'preview' => ['numeric', 'exists:task_notes,id']
        ]);

        if ($validator->fails()) {
            return null;
        }

        $previews['preview'] = TaskNote::select(['id', 'title', 'description', 'priority', 'due_date', 'reminder', 'is_complete', 'is_shortcut'])
            ->where('id', $validator->getData()['preview'])
            ->byTagAndUser($tagId, Auth::user()->id)
            ->notTrashed()
            ->notCompleted()
            ->mustTask()
            ->firstOrFail();
        $previews['inputDateValue'] = '';
        $previews['inputTimeValue'] = '';

        if (isset($previews['preview']['due_date'])) {
            $timestamp = strtotime($previews['preview']['due_date']);
            $previews['inputDateValue'] = date('Y-m-d', $timestamp);
            $previews['inputTimeValue'] = preg_match('/:/', $previews['preview']['due_date']) ? date('h:i', $timestamp) : '';
        }

        return $previews;
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
     * Delete given list
     */
    public function delete(Request $request)
    {
        $validatedData = $request->validate(['id' => ['required', 'present', 'numeric', 'exists:tags,id']]);
        $userId = Auth::user()->id;

        Tag::byUserAndId($validatedData['id'], $userId)
            ->delete();

        TaskNote::byTagAndUser($validatedData['id'], $userId)
            ->forceDelete();

        return redirect('/dashboard', 302)
            ->with('message', 'Successfully delete tag');
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

    /**
     * Determine the action value for the appropriate function name
     */
    public function action(Request $request)
    {
        $action = $request->validate([
            'action' => ['required', 'present', Rule::in(['saveTask', 'deleteTask', 'shortcut'])]
        ]);
        $message = call_user_func([__CLASS__, $action['action']], $request);

        return redirect($message['previous-uri'], 302)
            ->with('message', $message['message']);
    }

    /**
     * Update or save task related to current tag
     */
    private function saveTask(Request $request)
    {
        //
    }

    /**
     * Delete given task related to current tag
     */
    private function deleteTask(Request $request)
    {
        //
    }

    /**
     * Add given task releated to current tag to shortcut list
     */
    private function shortcut(Request $request)
    {
        //
    }
}
