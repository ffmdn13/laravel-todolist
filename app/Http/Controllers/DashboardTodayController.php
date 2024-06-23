<?php

namespace App\Http\Controllers;

use App\Models\TaskNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DashboardTodayController extends Controller
{
    /**
     * Render dashboard noe page
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        return response()->view('dashboard.today', [
            'title' => 'Today',
            'tasks' => $this->getTasks(Auth::user()),
            'preview' => $this->preview($request->query('preview', null), $user->id),
            'timeFormat' => json_decode($user->personalization, true)['time-format']
        ]);
    }

    /**
     * Get user related list task
     */
    private function getTasks($user)
    {
        return TaskNote::select(['id', 'title', 'priority', 'due_date', 'reminder'])
            ->whereBelongsTo($user, 'user')
            ->notCompleted()
            ->notTrashed()
            ->mustTask()
            ->byToday()
            ->get();
    }

    private function preview($preview, $userId)
    {
        $validator = Validator::make(['preview' => $preview], [
            'preview' => ['numeric', 'exists:task_notes,id']
        ]);

        if ($validator->fails()) {
            return null;
        }

        $previews = TaskNote::select(['id', 'title', 'description', 'priority', 'due_date', 'time', 'reminder', 'is_complete', 'is_shortcut'])
            ->byUserAndId($validator->getData()['preview'], $userId)
            ->notTrashed()
            ->notCompleted()
            ->mustTask()
            ->byToday()
            ->first();

        return $previews;
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
        $action = $request->validate([
            'action' => ['required', 'present', Rule::in(['save', 'delete'])]
        ]);
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

        if (is_null($validatedFormData['due_date'])) {
            $validatedFormData['time'] = null;
        } else {
            $validatedFormData['due_date'] = strtotime($validatedFormData['due_date']);
            $validatedFormData['time'] = $validatedFormData['time'] == true ? strtotime($validatedFormData['time']) : null;
        }

        $message = TaskNote::byUserAndId($validatedFormData['id'], Auth::user()->id)
            ->update([
                'due_date' => $validatedFormData['due_date'],
                'time' => $validatedFormData['time'],
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
        $message = TaskNote::byUserAndId($id, Auth::user()->id)
            ->delete() === 1 ? "Successfully deleted task \"" . $reqeust->input('title', null) . "\"." : "Task not found!";

        $previousUrl = explode("/$id", $reqeust->session()->previousUrl())[0];

        return ['message' => $message, 'previous-url' => $previousUrl];
    }
}
