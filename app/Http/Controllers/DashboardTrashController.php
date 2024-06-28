<?php

namespace App\Http\Controllers;

use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardTrashController extends Controller
{
    /**
     * Render dashboard noe page
     */
    public function index()
    {
        $user = Auth::user();

        return response()->view('dashboard.trash', [
            'title' => 'Trash',
            'items' => $this->getItems($user),
            'timeFormat' => $this->getTimeFormat(json_decode($user->personalization, true)['time-format']),
            'priority' => ['0' => '-', '1' => 'Low', '2' => 'Medium', '3' => 'High']
        ]);
    }

    /**
     * Render given completed task
     */
    public function view($id, $title)
    {
        $user = Auth::user();

        return response()->view('dashboard.trashed-view', [
            'title' => $title,
            'item' => $this->getViewItem($id, $user->id),
            'timeFormat' => $this->getTimeFormat(json_decode($user->personalization, true)['time-format'])
        ]);
    }

    /**
     * Get complete task that belongs to user
     */
    private function getItems($user)
    {
        return TaskNote::with(['notebook'])
            ->select(['id', 'title', 'description', 'due_date', 'notebook_id'])
            ->whereBelongsTo($user, 'user')
            ->onlyTrashed()
            ->mustNote()
            ->get();
    }

    /**
     * Return a view shortcut item that belongs to user
     */
    private function getViewItem($id, $userId)
    {
        return TaskNote::select(['id', 'title', 'description', 'is_shortcut'])
            ->byUserAndId($id, $userId)
            ->onlyTrashed()
            ->mustNote()
            ->firstOrFail();
    }

    /**
     * Get user tiem format based user personalization
     */
    private function getTimeFormat($format = '24hr')
    {
        return ['24hr' => 'H:i', '12hr' => ' h:i A'][$format];
    }

    /**
     * Reopen or uncomplete given task
     */
    public function reopen($id)
    {
        TaskNote::byUserAndId($id, Auth::user()->id)
            ->onlyTrashed()
            ->mustNote()
            ->restore();

        return back(302)
            ->with('message', 'Successfully restore deleted note');
    }

    /**
     * Delete given task id
     */
    public function deleteNote($id)
    {
        TaskNote::byUserAndId($id, Auth::user()->id)
            ->onlyTrashed()
            ->mustNote()
            ->forceDelete();

        return back(302)
            ->with('message', 'Succesfully deleted note');
    }

    /**
     * Determine the action value for the appropriate function name
     */
    public function action(Request $request)
    {
        $action = $request->validate(['action' => ['required', 'present', Rule::in(['save', 'delete'])]]);
        $message = call_user_func([__CLASS__, $action['action']], $request);

        return redirect($message['previous-url'], 302)
            ->with('message', $message['message']);
    }

    /**
     * Update the given task
     */
    private function save(Request $request)
    {
        $validatedFormData = $this->validateData($request);
        $message = TaskNote::byUserAndId($validatedFormData['id'], Auth::user()->id)
            ->onlyTrashed()
            ->mustNote()
            ->update([
                'title' => $validatedFormData['title'],
                'description' => $validatedFormData['description'],
                'due_date' => time()
            ]) === 1 ? 'Successfully updated note "' . $validatedFormData['title'] . '"' : 'Note not found!';

        return ['message' => $message, 'previous-url' => $request->session()->previousUrl()];
    }

    /**
     * Mark note as trashed
     */
    private function delete(Request $reqeust)
    {
        $message = TaskNote::byUserAndId($reqeust->input('id', null), Auth::user()->id)
            ->onlyTrashed()
            ->mustNote()
            ->forceDelete() === 1 ? 'Successfully deleted note "' . $reqeust->input('title', null) . '".' : "Note not found!";

        return ['message' => $message, 'previous-url' => '/dashboard/trash'];
    }

    /**
     * Add note to shortcut list
     */
    private function shortcut(Request $request)
    {
        $note = TaskNote::select(['id', 'is_shortcut', 'title'])
            ->byUserAndId($request->input('id'), Auth::user()->id)
            ->onlyTrashed()
            ->mustNote()
            ->firstOrFail();

        if ($note->is_shortcut === 0) {
            $note->is_shortcut = 1;
            $message = 'Note "' . $note->title . '" added to shortcut';
        } else {
            $note->is_shortcut = 0;
            $message = 'Note "' . $note->title . '" removed from shortcut';
        }

        $note->save();

        return ['message' => $message, 'previous-url' => $request->session()->previousUrl()];
    }

    /**
     * Return validated data
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'id' => ['required', 'present', 'numeric'],
            'title' => ['required', 'present', 'max:255', 'string'],
            'description' => ['nullable', 'present', 'string'],
        ]);
    }
}
