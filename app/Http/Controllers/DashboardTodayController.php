<?php

namespace App\Http\Controllers;

use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DashboardTodayController extends Controller
{
    /**
     * Render dashboard noe page
     */
    public function index($id = null, $title = 'Today Task')
    {
        $user = Auth::user();

        return response()->view('dashboard.today', [
            'title' => $title,
            'tasks' => $this->getItems($user),
            'view' => $this->view($id, $user->id),
            'timeFormat' => $this->getTimeFormat(json_decode($user->personalization, true)['time-format'])
        ]);
    }

    /**
     * Get user related list task
     */
    private function getItems($user)
    {
        return TaskNote::select(['id', 'title', 'priority', 'due_date', 'reminder'])
            ->whereBelongsTo($user, 'user')
            ->notCompleted()
            ->mustTask()
            ->byToday()
            ->get();
    }

    private function view($id, $userId)
    {
        if (is_null($id)) {
            return null;
        }

        return TaskNote::select(['id', 'title', 'description', 'priority', 'due_date', 'time', 'reminder', 'is_complete'])
            ->byUserAndId($id, $userId)
            ->notCompleted()
            ->mustTask()
            ->byToday()
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
     * Add task data to task_notes table
     */
    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['required', Rule::in(['0', '1', '2', '3']), 'numeric'],
        ]);
        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['due_date'] = time();

        TaskNote::create($validatedData);

        return redirect('/dashboard/today', 302)
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
        $validatedFormData = $this->validateData($request);
        $validatedFormData['due_date'] = $this->getTimestamp($validatedFormData['due_date']);
        $validatedFormData['time'] = isset($validatedFormData['due_date']) ? $this->getTimestamp($validatedFormData['time']) : null;

        $message = TaskNote::byUserAndId($validatedFormData['id'], Auth::user()->id)
            ->notCompleted()
            ->mustTask()
            ->byToday()
            ->update([
                'due_date' => $validatedFormData['due_date'],
                'time' => $validatedFormData['time'],
                'reminder' => $validatedFormData['reminder'],
                'title' => $validatedFormData['title'],
                'description' => $validatedFormData['description'],
                'is_complete' => $validatedFormData['is_complete'] ?? 0
            ]) === 1 ? 'Successfully updated task "' . $validatedFormData['title'] . '"' : 'Task not found!';

        return ['message' => $message, 'previous-url' => $this->getPreviousUrl($request, $validatedFormData['due_date'])];
    }

    /**
     * Delete the task record
     */
    private function delete(Request $request)
    {
        $message = TaskNote::byUserAndId($request->input('id', null), Auth::user()->id)
            ->notCompleted()
            ->mustTask()
            ->byToday()
            ->forceDelete() === 1 ? "Successfully deleted task \"" . $request->input('title', null) . "\"." : "Task not found!";

        return ['message' => $message, 'previous-url' => '/dashboard/today'];
    }

    /**
     * Return validated data
     */
    private function validateData(Request $request)
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

        return $request->validate($rules);
    }

    /**
     * Get timestamp for given date or time
     */
    private function getTimestamp($timestamp = null)
    {
        return is_string($timestamp) ? strtotime($timestamp) : null;
    }

    private function getPreviousUrl(Request $request, $date)
    {
        $date = date('Y-m-d', is_null($date) ? time() + 86400 : $date);
        $currentDate = date('Y-m-d', time());

        return $date === $currentDate ? $request->session()->previousUrl() : '/dashboard/today';
    }
}
