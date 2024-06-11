<?php

namespace App\Http\Controllers;

use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardNoteController extends Controller
{
    /**
     * Render dashboard note page
     */
    public function index($id = null)
    {
        return response()->view('dashboard.note', [
            'title' => $this->getPageTitle(2),
            'notes' => TaskNote::whereBelongsTo(Auth::user(), 'user')
                ->notTrashed()
                ->mustNote()
                ->get(['id', 'title', 'due_date', 'notebook_id']),
            'preview' => $this->getNotePreview($id)
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
     * Return preview of note data
     */
    private function getNotePreview($id)
    {
        if (is_null($id)) {
            return null;
        }

        $preview = TaskNote::select(['id', 'title', 'description', 'is_shortcut'])
            ->byUserAndId($id, Auth::user()->id)
            ->notTrashed()
            ->notCompleted()
            ->mustNote()
            ->first();

        if (is_null($preview)) {
            return null;
        }

        return $preview;
    }

    /**
     * Add new note
     */
    public function add(Request $request)
    {
        $validatedData = $request->validate(['title' => ['required', 'string', 'max:255']]);

        TaskNote::create([
            'title' => $validatedData['title'],
            'description' => null,
            'due_date' => (string) time(),
            'reminder' => null,
            'priority' => 0,
            'type' => 'note',
            'is_complete' => 0,
            'is_trash' => 0,
            'is_shortcut' => 0,
            'list_id' => null,
            'notebook_id' => null,
            'tag_id' => null,
            'user_id' => Auth::user()->id
        ]);

        return redirect('/dashboard/note/', 302)
            ->with('message', 'Successfully added note "' . $validatedData['title'] . '"');
    }

    /**
     * Determine the action value for the appropriate function name
     */
    public function action(Request $request)
    {
        $action = $request->validate(['action' => ['required', 'present', Rule::in(['save', 'delete', 'shortcut'])]]);
        $message = call_user_func([__CLASS__, $action['action']], $request);

        return redirect($request->session()->previousUrl(), 302)
            ->with('message', $message);
    }

    /**
     * Save or update given note
     */
    private function save(Request $request)
    {
        $rules = [
            'id' => ['required', 'present', 'numeric'],
            'title' => ['required', 'present', 'max:255', 'string'],
            'description' => ['nullable', 'present', 'string'],
        ];

        $validatedFormData = $request->validate($rules);
        $userId = Auth::user()->id;

        return TaskNote::byUserAndId($validatedFormData['id'], $userId)
            ->mustNote()
            ->update([
                'title' => $validatedFormData['title'],
                'description' => $validatedFormData['description'],
            ]) === 1 ? 'Successfully updated task "' . $validatedFormData['title'] . '"' : 'Task not found!';
    }

    /**
     * Mark note as trashed
     */
    private function delete(Request $reqeust)
    {
        $id = $reqeust->input('id', null);
        $userId = Auth::user()->id;
        $currentDeletedNote = $reqeust->input('title', null);

        // delete task using soft delete method
        return TaskNote::byUserAndId($id, $userId)
            ->mustNote()
            ->delete() === 1 ? "Successfully deleted note \"$currentDeletedNote\"." : "Note not found!";
    }

    /**
     * Add note to shortcut list
     */
    private function shortcut(Request $request)
    {
        $note = TaskNote::select(['id', 'is_shortcut', 'title'])
            ->byUserAndId($request->input('id'), Auth::user()->id)
            ->mustNote()
            ->first();

        if ($note->is_shortcut === 0) {
            $note->is_shortcut = 1;
            $message = 'Note "' . $note->title . '" added to shortcut';
        } else {
            $note->is_shortcut = 0;
            $message = 'Note "' . $note->title . '" removed from shortcut';
        }

        $note->save();

        return $message;
    }
}
