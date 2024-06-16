<?php

namespace App\Http\Controllers;

use App\Models\Notebook;
use App\Models\TaskNote;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashboardNotebookController extends Controller
{
    /**
     * Render dashboard noe page
     */
    public function index(Request $request, $id, $title)
    {
        return response()->view('dashboard.notebook', [
            'title' => $this->getPageTitle(2),
            'notebookId' => $id,
            'notebookTitle' => $title,
            'notes' => $this->getNotes($id, Auth::user()->id),
            'preview' => $this->getPreview($request->query('preview', null), $id)
        ]);
    }

    private function getPreview($preview, $id)
    {
        $validator = Validator::make(['preview' => $preview], [
            'preview' => ['numeric', 'exists:task_notes,id']
        ]);

        if ($validator->fails()) {
            return null;
        }

        $previews = TaskNote::select(['id', 'title', 'description', 'is_shortcut'])
            ->where('id', $validator->getData()['preview'])
            ->byNotebookAndUser($id, Auth::user()->id)
            ->notTrashed()
            ->mustNote()
            ->first();

        return $previews;
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
    private function getNotes($id, $userId)
    {
        return TaskNote::select(['id', 'title', 'due_date'])
            ->byNotebookAndUser($id, $userId)
            ->notCompleted()
            ->notTrashed()
            ->mustNote()
            ->get();
    }

    /**
     * Add new tag
     */
    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'present', 'string', 'max:255', 'unique:notebooks,title'],
        ]);
        $validatedData['user_id'] = Auth::user()->id;

        Notebook::create($validatedData);

        return redirect('/dashboard', 302);
    }

    /**
     * Delete given list
     */
    public function delete(Request $request)
    {
        $validatedData = $request->validate(['id' => ['required', 'present', 'numeric', 'exists:notebooks,id']]);
        $userId = Auth::user()->id;

        Notebook::byUserAndId($validatedData['id'], $userId)
            ->delete();

        TaskNote::byNotebookAndUser($validatedData['id'], $userId)
            ->mustNote()
            ->forceDelete();

        return redirect('/dashboard', 302)
            ->with('message', 'Successfully delete notebook');
    }

    /**
     * Add new task to current tag
     */
    public function addNote(Request $request)
    {
        $validatedData = $request->validate([
            'id' => ['required', 'present', 'numeric', 'exists:notebooks,id'],
            'title' => ['required', 'present', 'string', 'max:255'],
        ]);
        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['notebook_id'] = $validatedData['id'];

        $validatedData['type'] = 'note';
        $validatedData['due_date'] = (string) time();

        TaskNote::create($validatedData);

        return redirect($request->session()->previousUrl(), 302)
            ->with('message', 'Successfully added note "' . $validatedData['title'] . '"');
    }

    /**
     * Determine the action value for the appropriate function name
     */
    public function action(Request $request)
    {
        $action = $request->validate([
            'action' => ['required', 'present', Rule::in(['saveNote', 'deleteNote', 'shortcut'])]
        ]);
        $message = call_user_func([__CLASS__, $action['action']], $request);

        return redirect($message['previous-url'], 302)
            ->with('message', $message['message']);
    }

    /**
     * Save or update given note
     */
    private function saveNote(Request $request)
    {
        $rules = [
            'id' => ['required', 'present', 'numeric', 'exists:task_notes,id'],
            'title' => ['required', 'present', 'max:255', 'string'],
            'description' => ['nullable', 'present', 'string'],
        ];

        $validatedFormData = $request->validate($rules);
        $userId = Auth::user()->id;

        $message = TaskNote::byUserAndId($validatedFormData['id'], $userId)
            ->mustNote()
            ->update([
                'title' => $validatedFormData['title'],
                'description' => $validatedFormData['description'],
            ]) === 1 ? 'Successfully updated note "' . $validatedFormData['title'] . '"' : 'Note not found!';

        return ['message' => $message, 'previous-url' => $request->session()->previousUrl()];
    }

    /**
     * Delete given task related to current tag
     */
    private function deleteNote(Request $request)
    {
        $id = $request->input('id', null);
        $userId = Auth::user()->id;
        $currentDeletedTask = $request->input('title', null);

        $message = TaskNote::byUserAndId($id, $userId)
            ->delete() === 1 ? "Successfully delete note \"$currentDeletedTask\"." : "Note not found!";
        $previousUrl = explode('?', $request->session()->previousUrl())[0];

        return ['message' => $message, 'previous-url' => $previousUrl];
    }

    /**
     * Add given task releated to current tag to shortcut list
     */
    private function shortcut(Request $request)
    {
        $task = TaskNote::select(['id', 'is_shortcut', 'title'])
            ->byUserAndId($request->input('id'), Auth::user()->id)
            ->firstOrFail();

        if ($task->is_shortcut === 0) {
            $task->is_shortcut = 1;
            $message = 'Task "' . $task->title . '" added to shortcut';
        } else {
            $task->is_shortcut = 0;
            $message = 'Task "' . $task->title . '" removed from shortcut';
        }

        $task->save();

        return ['message' => $message, 'previous-url' => $request->session()->previousUrl()];
    }
}
