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
    public function index(Request $request, $id = null, $title = 'Note')
    {
        $user = Auth::user();

        return response()->view('dashboard.note', [
            'title' => $title,
            'notes' => $this->getItems($user, $request->query('order', null)),
            'view' => $this->view($id, $user->id),
            'url' => setDelimiterForOrderByUrl($request->fullUrl()),
            'queryParams' => '?' . $request->getQueryString(),
            'personalization' => json_decode($user->personalization)
        ]);
    }

    /**
     * Return all note data that related to user
     */
    private function getItems($user, $order)
    {
        return TaskNote::select(['id', 'title', 'due_date'])
            ->whereBelongsTo($user, 'user')
            ->notInTheNotebook()
            ->notTrashed()
            ->mustNote()
            ->orderedBy($this->valiatedOrderByParam($order))
            ->simplePaginate(10)
            ->withQueryString();
    }

    /**
     * Return preview of note data
     */
    private function view($id, $userId)
    {
        if (is_null($id)) {
            return null;
        }

        return TaskNote::select(['id', 'title', 'description', 'is_shortcut'])
            ->byUserAndId($id, $userId)
            ->notTrashed()
            ->notInTheNotebook()
            ->mustNote()
            ->firstOrFail();
    }

    /**
     * Add new note
     */
    public function add(Request $request)
    {
        $validatedData = $request->validate(['title' => ['required', 'string', 'max:255']]);
        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['due_date'] = time();

        $validatedData['type'] = 'note';

        TaskNote::create($validatedData);

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

        return redirect($message['previous-url'], 302)
            ->with('message', $message['message']);
    }

    /**
     * Save or update given note
     */
    private function save(Request $request)
    {
        $validatedFormData = $this->validateData($request);
        $userId = Auth::user()->id;

        $message = TaskNote::byUserAndId($validatedFormData['id'], $userId)
            ->notTrashed()
            ->notInTheList()
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
        $queryString = explode('?', $reqeust->session()->previousUrl())[1] ?? null;
        $message = TaskNote::byUserAndId($reqeust->input('id', null), Auth::user()->id)
            ->notTrashed()
            ->notInTheNotebook()
            ->mustNote()
            ->delete() === 1 ? 'Successfully deleted note "' . $reqeust->input('title', null) . '".'  : "Note not found!";

        return ['message' => $message, 'previous-url' => '/dashboard/note?' . $queryString];
    }

    /**
     * Add note to shortcut list
     */
    private function shortcut(Request $request)
    {
        $note = TaskNote::select(['id', 'is_shortcut', 'title'])
            ->byUserAndId($request->input('id'), Auth::user()->id)
            ->notTrashed()
            ->notInTheNotebook()
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
            'description' => ['nullable', 'present', 'string']
        ]);
    }

    /**
     * Validate given order by query parameters
     */
    private function valiatedOrderByParam($order)
    {
        $direction = 'asc';

        if (request()->has('direction')) {
            $direction = in_array(request()->query('direction'), ['asc', 'desc']) ? request()->query('direction') : null;
        }

        return in_array($order, ['title', 'due_date', 'priority'], true) ? ['order' => $order, 'direction' => $direction] : null;
    }
}
