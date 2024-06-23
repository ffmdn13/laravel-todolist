<?php

namespace App\Http\Controllers;

use App\Models\Lists;
use App\Models\TaskNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DashboardListController extends Controller
{
    /**
     * Render dashboard list page with given id parameter
     */
    public function index(Request $request, $id, $title)
    {
        return response()->view('dashboard.list', [
            'title' => $this->getPageTitle(2),
            'listId' => $id,
            'listTitle' => $title,
            'tasks' => $this->getTasks($id, Auth::user()->id),
            'preview' => $this->preview($request->query('preview', null), $id)
        ]);
    }

    private function preview($preview, $listId)
    {
        $validator = Validator::make(['preview' => $preview], [
            'preview' => ['numeric', 'exists:task_notes,id']
        ]);

        if ($validator->fails()) {
            return null;
        }

        $previews['preview'] = TaskNote::select(['id', 'title', 'description', 'priority', 'due_date', 'reminder', 'is_complete', 'is_shortcut'])
            ->where('id', $validator->getData()['preview'])
            ->byListAndUser($listId, Auth::user()->id)
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
     * Get user related list task
     */
    private function getTasks($id, $userId)
    {
        return TaskNote::select(['id', 'title', 'priority', 'due_date', 'reminder'])
            ->byListAndUser($id, $userId)
            ->notCompleted()
            ->notTrashed()
            ->mustTask()
            ->get();
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
        $validatedData = $request->validate(['title' => ['required', 'present', 'string', 'max:255', 'unique:lists,title']]);
        $validatedData['user_id'] = Auth::user()->id;

        Lists::create($validatedData);

        return redirect('/dashboard', 302);
    }

    /**
     * Add new task to current list
     */
    public function addTask(Request $request)
    {
        $validatedData = $request->validate([
            'id' => ['required', 'present', 'numeric', 'exists:lists,id'],
            'title' => ['required', 'present', 'string', 'max:255'],
            'priority' => ['required', 'present', Rule::in(['0', '1', '2', '3'])],
        ]);

        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['list_id'] = $validatedData['id'];
        $validatedData['tag_id'] = null;
        $validatedData['notebook_id'] = null;

        TaskNote::create($validatedData);

        return redirect($request->session()->previousUrl(), 302)
            ->with('message', 'Successfully added task "' . $validatedData['title'] . '"');
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

        TaskNote::byListAndUser($validatedData['id'], $userId)
            ->forceDelete();

        return redirect('/dashboard', 302)
            ->with('message', 'Successfully delete list');
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
     * Update the given task
     */
    private function saveTask(Request $request)
    {
        $rules = [
            'id' => ['required', 'present', 'numeric', 'exists:task_notes,id'],
            'due_date' => ['nullable', 'present', 'date', 'date_format:Y-m-d'],
            'time' => ['nullable', 'present', 'date_format:H:i'],
            'reminder' => ['nullable', 'present', 'date_format:H:i'],
            'title' => ['required', 'present', 'max:255', 'string'],
            'description' => ['nullable', 'present', 'string'],
        ];

        if ($request->has('is_complete') === true) {
            $rules['is_complete'] = ['required', 'present', 'boolean'];
        }

        $validatedFormData = $request->validate($rules);
        $id = $validatedFormData['id'];
        $userId = Auth::user()->id;

        /**
         * Time format to use :
         * 1. 24hr : l, M j Y H:i
         * 2. 12hr : l, M j Y h:i A
         */
        if ($validatedFormData['due_date'] == true || $validatedFormData['time'] == true) {
            $validatedFormData['due_date'] = $this->getDueDate(
                $validatedFormData['due_date'],
                $validatedFormData['time']
            );
        }

        $message = TaskNote::byUserAndId($id, $userId)
            ->update([
                'due_date' => $validatedFormData['due_date'],
                'reminder' => $validatedFormData['reminder'],
                'title' => $validatedFormData['title'],
                'description' => $validatedFormData['description'],
                'is_complete' => $validatedFormData['is_complete'] ?? 0
            ]) === 1 ? 'Successfully updated task "' . $validatedFormData['title'] . '"' : 'Task not found!';

        return ['message' => $message, 'previous-uri' => $request->session()->previousUrl()];
    }

    /**
     * Delete task in current list
     */
    private function deleteTask(Request $request)
    {
        $id = $request->input('id', null);
        $userId = Auth::user()->id;
        $currentDeletedTask = $request->input('title', null);

        $message = TaskNote::byUserAndId($id, $userId)
            ->delete() === 1 ? "Successfully delete task \"$currentDeletedTask\"." : "Task not found!";
        $previousUri = explode('?', $request->session()->previousUrl())[0];

        return ['message' => $message, 'previous-uri' => $previousUri];
    }

    /**
     * Set and return task due date
     */
    private function getDueDate(?string $dueDate, ?string $time)
    {
        $userTimeFormat = null;

        if ($time == true) {
            $timeFormat = ['24hr' => ' H:i', '12hr' => ' h:i A'];
            $userTimeFormat = $timeFormat[json_decode(Auth::user()->personalization, true)['time-format']];
        }

        $timestamp = is_null($dueDate) ?
            strtotime(date('M j Y') . "$time") :
            strtotime("$dueDate $time");

        return Carbon::now()->setTimestamp($timestamp)->format('l, M j Y' . $userTimeFormat);
    }
}
