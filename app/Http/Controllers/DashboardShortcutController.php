<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskNote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardShortcutController extends Controller
{
    /** 
     * Render dashboard shortcut page
     */
    public function index(Request $request)
    {
        return response()->view('dashboard.shortcut', [
            'title' => 'Shortcut',
            'items' => $this->getItems(Auth::user()),
            'timeFormat' => $this->getTimeFormat(json_decode(Auth::user()->personalization, true)['time-format']),
            'queryParams' => '?' . $request->getQueryString()
        ]);
    }

    /**
     * Render given shortcut task or not
     */
    public function view(Request $request, $id, $title)
    {
        return response()->view('dashboard.table-view', [
            'title' => $title,
            'item' => $this->getViewItem($id, Auth::user()->id),
            'timeFormat' => $this->getTimeFormat(json_decode(Auth::user()->personalization, true)['time-format']),
            'queryParams' => '?' . $request->getQueryString()
        ]);
    }

    /**
     * Return shortcut items that belongs to user
     */
    private function getItems($user)
    {
        return TaskNote::with('notebook')
            ->select(['id', 'title', 'due_date', 'description', 'notebook_id'])
            ->whereBelongsTo($user, 'user')
            ->isShortcuted()
            ->notTrashed()
            ->mustNote()
            ->simplePaginate(10)
            ->withQueryString();
    }

    /**
     * Return a view shortcut item that belongs to user
     */
    private function getViewItem($id, $userId)
    {
        return TaskNote::select(['id', 'title', 'description', 'is_shortcut'])
            ->byUserAndId($id, $userId)
            ->isShortcuted()
            ->notTrashed()
            ->mustNote()
            ->firstOrFail();
    }

    /**
     * Get user tiem format based user personalization
     */
    private function getTimeFormat($format = '12hr')
    {
        return ['24hr' => 'H:i', '12hr' => ' h:i A'][$format];
    }

    /**
     * Determine the action value for the appropriate function name
     */
    public function action(Request $request)
    {
        $action = $request->validate(['action' => ['required', 'present', Rule::in(['save', 'delete', 'shortcut'])]]);
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
            ->isShortcuted()
            ->notTrashed()
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
        $id = $reqeust->input('id', null);
        $userId = Auth::user()->id;
        $currentDeletedNote = $reqeust->input('title', null);

        $message = TaskNote::byUserAndId($id, $userId)
            ->isShortcuted()
            ->notTrashed()
            ->mustNote()
            ->delete() === 1 ? "Successfully deleted note \"$currentDeletedNote\"." : "Note not found!";

        return ['message' => $message, 'previous-url' => '/dashboard/shortcut'];
    }

    /**
     * Add note to shortcut list
     */
    private function shortcut(Request $request)
    {
        $note = TaskNote::select(['id', 'is_shortcut', 'title'])
            ->byUserAndId($request->input('id'), Auth::user()->id)
            ->isShortcuted()
            ->notTrashed()
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

        return ['message' => $message, 'previous-url' => '/dashboard/shortcut'];
    }

    /**
     * Return validated data
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'id' => ['required', 'present', 'numeric', 'exists:task_notes,id'],
            'title' => ['required', 'present', 'max:255', 'string'],
            'description' => ['nullable', 'present', 'string'],
        ]);
    }
}
