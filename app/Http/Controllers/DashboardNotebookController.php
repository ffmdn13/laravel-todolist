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
    public function index(Request $request, $id, $title = 'Notebook')
    {
        $user = Auth::user();

        return response()->view('dashboard.notebook', [
            'title' => $title,
            'notebookId' => $id,
            'notebookTitle' => $title,
            'notes' => $this->getItems($id, $user->id, $request->query('order', null)),
            'view' => $this->view($request->query('view', null), $id, $user->id),
            'url' => setDelimiterForOrderByUrl($request->fullUrl()),
            'queryParams' => $this->getQueryParameters($request, '&'),
            'theme' => json_decode($user->personalization)->apperance->theme
        ]);
    }

    private function getItems($id, $userId, $order)
    {
        return TaskNote::select(['id', 'title', 'due_date'])
            ->byNotebookAndUser($id, $userId)
            ->notTrashed()
            ->orderedBy($this->valiatedOrderByParam($order))
            ->simplePaginate(10)
            ->withQueryString();
    }

    private function view($id, $notebookId, $userId)
    {
        $validator = Validator::make(['view' => $id], [
            'view' => ['numeric', 'exists:task_notes,id']
        ]);

        if ($validator->fails()) {
            return null;
        }

        return TaskNote::select(['id', 'title', 'description', 'is_shortcut'])
            ->where('id', $validator->getData()['view'])
            ->byNotebookAndUser($notebookId, $userId)
            ->notTrashed()
            ->firstOrFail();
    }

    /**
     * Add new notebook
     */
    public function add(Request $request)
    {
        $validatedData = $request->validate(['title' => ['required', 'present', 'string', 'max:255', 'unique:notebooks,title']]);
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

        Notebook::byUserAndId($validatedData['id'], $userId)->delete();
        TaskNote::byNotebookAndUser($validatedData['id'], $userId)
            ->mustNote()
            ->forceDelete();

        return redirect('/dashboard', 302)->with('message', 'Successfully delete notebook');
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
        $validatedData['due_date'] = time();

        TaskNote::create($validatedData);

        return redirect($request->session()->previousUrl(), 302)
            ->with('message', 'Successfully added note "' . $validatedData['title'] . '"');
    }

    /**
     * Determine the action value for the appropriate function name
     */
    public function action(Request $request)
    {
        $action = $request->validate(['action' => ['required', 'present', Rule::in(['saveNote', 'deleteNote', 'shortcut'])]]);
        $message = call_user_func([__CLASS__, $action['action']], $request);

        return redirect($message['previous-url'], 302)
            ->with('message', $message['message']);
    }

    /**
     * Save or update given note
     */
    private function saveNote(Request $request)
    {
        $validatedFormData = $this->validateData($request);
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
        $message = TaskNote::byNotebookAndUser($request->input('notebook_id', 0), Auth::user()->id)
            ->where('id', $request->input('id', null))
            ->delete() === 1 ? 'Succesfully deleted note ' . $request->input('title', null) : "Note not found!";

        preg_match_all('/(?<=\?|\&)(?!view=\d+\b)[^&]+/', $request->session()->previousUrl(), $match);
        $queryString = implode('&', $match[0]);

        $notebookId = $request->input('notebook_id', null);
        $notebookTitle = $request->input('notebook_title', null);

        return ['message' => $message, 'previous-url' => "/dashboard/notebook/$notebookId/$notebookTitle/?$queryString"];
    }

    /**
     * Add given task releated to current tag to shortcut list
     */
    private function shortcut(Request $request)
    {
        $task = TaskNote::select(['id', 'is_shortcut', 'title'])
            ->byNotebookAndUser($request->input('notebook_id', 0), Auth::user()->id)
            ->where('id', $request->input('id', null))
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

    /**
     * Get url query paramsters
     */
    private function getQueryParameters(Request $request, $delimiter = '?')
    {
        preg_match('/\?([a-zA-Z0-9=\&_]+)/', $request->fullUrlWithoutQuery(['view']), $match);

        $queryParams = $match[1] ?? null;
        return $delimiter . $queryParams;
    }
}
