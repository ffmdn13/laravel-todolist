<?php

namespace App\Http\Controllers;

use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardCompleteController extends Controller
{
    /**
     * Render dashboard complete page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $personalization = $this->getPersonalization($user);

        return response()->view('dashboard.complete', [
            'title' => 'Complete',
            'items' => $this->getItems($user),
            'timeFormat' => $this->getTimeFormat($personalization->datetime->time_format),
            'priority' => ['0' => '-', '1' => 'Low', '2' => 'Medium', '3' => 'High'],
            'queryParams' => '?' . $request->getQueryString(),
            'theme' => $personalization->apperance->theme
        ]);
    }

    /**
     * Render given completed task
     */
    public function view(Request $request, $id, $title)
    {
        $user = Auth::user();
        $personalization = $this->getPersonalization($user);

        return response()->view('dashboard.completed-view', [
            'title' => $title,
            'item' => $this->getViewItem($id, $user->id),
            'timeFormat' => $this->getTimeFormat($personalization->datetime->time_format),
            'queryParams' => '?' . $request->getQueryString(),
            'theme' => $personalization->apperance->theme
        ]);
    }

    /**
     * Get complete task that belongs to user
     */
    private function getItems($user)
    {
        return TaskNote::with(['list', 'tag'])
            ->select(['id', 'title', 'type', 'due_date', 'time', 'priority', 'list_id', 'tag_id'])
            ->whereBelongsTo($user, 'user')
            ->isCompleted()
            ->mustTask()
            ->simplePaginate(10)
            ->withQueryString();
    }

    /**
     * Return a view shortcut item that belongs to user
     */
    private function getViewItem($id, $userId)
    {
        return TaskNote::select(['id', 'title', 'description', 'priority', 'due_date', 'time', 'type', 'is_complete'])
            ->byUserAndId($id, $userId)
            ->isCompleted()
            ->mustTask()
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
     * Reopen or uncomplete given task
     */
    public function reopen($id)
    {
        $taskNotes = TaskNote::select(['is_complete', 'title'])
            ->byUserAndId($id, Auth::user()->id)
            ->isCompleted()
            ->mustTask()
            ->firstOrFail();

        $taskNotes->id = $id;
        $taskNotes->is_complete = 0;
        $taskNotes->save();

        return back(302)
            ->with('message', 'Succesfully reopen task "' . $taskNotes->title . '"');
    }

    /**
     * Delete given task id
     */
    public function deleteTask($id)
    {
        TaskNote::byUserAndId($id, Auth::user()->id)
            ->isCompleted()
            ->mustTask()
            ->forceDelete();

        return back(302)
            ->with('message', 'Succesfully deleted task');
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

        if ($validatedFormData['due_date'] === null && isset($validatedFormData['time'])) {
            $validatedFormData['due_date'] = $this->setDefaultDate();
        } else {
            $validatedFormData['due_date'] = $this->getTimestamp($validatedFormData['due_date']);
        }

        $validatedFormData['time'] = isset($validatedFormData['due_date']) ? $this->getTimestamp($validatedFormData['time']) : null;

        $previousUrl = $request->missing('is_complete') ? '/dashboard/complete' : $request->session()->previousUrl();
        $message = TaskNote::byUserAndId($validatedFormData['id'], Auth::user()->id)
            ->isCompleted()
            ->mustTask()
            ->update([
                'due_date' => $validatedFormData['due_date'],
                'time' => $validatedFormData['time'],
                'reminder' => $validatedFormData['reminder'],
                'title' => $validatedFormData['title'],
                'description' => $validatedFormData['description'],
                'is_complete' => $validatedFormData['is_complete'] ?? 0
            ]) === 1 ? 'Successfully updated task "' . $validatedFormData['title'] . '"' : 'Task not found!';

        return ['message' => $message, 'previous-url' => $previousUrl];
    }

    /**
     * Mark note as trashed
     */
    private function delete(Request $request)
    {
        $message = TaskNote::byUserAndId($request->input('id', null), Auth::user()->id)
            ->isCompleted()
            ->notTrashed()
            ->mustTask()
            ->forceDelete() === 1 ? 'Successfully deleted task "' . $request->input('title', null) . '".' : "Task not found!";
        $queryString = explode('?', $request->session()->previousUrl())[1] ?? null;

        return ['message' => $message, 'previous-url' => '/dashboard/complete?' . $queryString];
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

    private function getTimestamp($timestamp = null)
    {
        return is_string($timestamp) ? strtotime($timestamp) : null;
    }

    /**
     * Return user personalization data
     */
    private function getPersonalization($user)
    {
        return json_decode($user->personalization);
    }

    /**
     * Set task defualt schedule based on user default date
     */
    private function setDefaultDate()
    {
        $personalization = $this->getPersonalization(Auth::user());
        $defaultDates = [
            'today' => 0,
            'tomorrow' => 86400,
            'day_after_tomorrow' =>  172800
        ];
        return time() + $defaultDates[$personalization->datetime->default_date];
    }
}
