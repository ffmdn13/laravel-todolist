<?php

namespace App\Http\Controllers;

use App\Models\Lists;
use App\Models\TaskNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardTaskController extends Controller
{
    /**
     * Render dashboard task page
     */
    public function index($id = null)
    {
        return response()->view('dashboard.task', [
            'title' => $this->getPageTitle(2),
            'tasks' => $this->getTaskNotes(Auth::user()),
            'preview' => $this->getTaskPreview($id),
            'makanbang' => 'MAKANBANG'
        ]);
    }

    /**
     * Get user related list task
     */
    private function getTaskNotes($user)
    {
        return TaskNote::select(['id', 'title', 'priority', 'due_date', 'reminder'])
            ->whereBelongsTo($user, 'user')
            ->notCompleted()
            ->notTrashed()
            ->mustTask()
            ->get();
    }

    /**
     * Return required parametes for index method
     */
    private function getTaskPreview($id)
    {
        if (is_null($id)) {
            return null;
        }

        $preview['preview'] = TaskNote::select(['id', 'title', 'priority', 'due_date', 'description', 'is_complete', 'is_shortcut'])
            ->byUserAndId($id, Auth::user()->id)
            ->notTrashed()
            ->notCompleted()
            ->mustTask()
            ->firstOrFail();

        if (is_null($preview['preview'])) {
            return null;
        }

        $preview['inputDateValue'] = '';
        $preview['inputTimeValue'] = '';

        if (isset($preview['preview']['due_date'])) {
            $timestamp = strtotime($preview['preview']['due_date']);
            $preview['inputDateValue'] = date('Y-m-d', $timestamp);
            $preview['inputTimeValue'] = preg_match('/:/', $preview['preview']['due_date']) ? date('h:i', $timestamp) : '';
        }

        return $preview;
    }

    /**
     * Get page title from route request uri
     */
    private function getPageTitle($index = 0)
    {
        return ucfirst(explode('/', request()->getRequestUri())[$index]);
    }

    /**
     * Add task data to task_notes table
     */
    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['required', Rule::in(['0', '1', '2', '3'])],
        ]);

        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['list_id'] = null;
        $validatedData['tag_id'] = null;
        $validatedData['notebook_id'] = null;

        TaskNote::create($validatedData);

        return redirect('/dashboard/task', 302)
            ->with('message', 'Successfully added task "' . $validatedData['title'] . '"');
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
        $rules = [
            'id' => ['required', 'present', 'numeric'],
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

        return ['message' => $message, 'previous-url' => $request->session()->previousUrl()];
    }

    /**
     * Delete the task record
     */
    private function delete(Request $reqeust)
    {
        $id = $reqeust->input('id', null);
        $userId = Auth::user()->id;
        $currentDeletedTask = $reqeust->input('title', null);

        // delete task using soft delete method
        $message = TaskNote::byUserAndId($id, $userId)
            ->delete() === 1 ? "Successfully deleted task \"$currentDeletedTask\"." : "Task not found!";
        $previousUrl = explode("/$id", $reqeust->session()->previousUrl())[0];

        return ['message' => $message, 'previous-url' => $previousUrl];
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

    /**
     * Set and return task reminder
     */
    private function setReminder(string $reminder)
    {
    }
}
