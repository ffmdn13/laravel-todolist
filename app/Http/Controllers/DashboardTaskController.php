<?php

namespace App\Http\Controllers;

use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardTaskController extends Controller
{
    /**
     * Render dashboard task page
     */
    public function index(Request $request, $id = null, $title = 'Task')
    {
        $user = Auth::user();
        $personalization = $this->getPersonalization($user);

        return response()->view('dashboard.task', [
            'title' => $title,
            'tasks' => $this->getItems($user, $request->query('order', null)),
            'view' => $this->view($id, $user->id),
            'timeFormat' => $this->getTimeFormat($personalization->datetime->time_format),
            'url' => getSortByDelimiter($request->fullUrl()),
            'queryParams' => '?' . $request->getQueryString(),
            'personalization' => $personalization
        ]);
    }

    /**
     * Get user related list task
     */
    private function getItems($user, $order)
    {
        return TaskNote::select(['id', 'title', 'priority', 'due_date', 'reminder'])
            ->whereBelongsTo($user, 'user')
            ->notInTheList()
            ->notCompleted()
            ->mustTask()
            ->orderedBy($this->valiatedOrderByParam($order))
            ->simplePaginate(10)
            ->withQueryString();
    }

    /**
     * Return required parametes for index method
     */
    private function view($id, $userId)
    {
        if (is_null($id)) {
            return null;
        }

        return TaskNote::select(['id', 'title', 'priority', 'due_date', 'time', 'description', 'is_complete'])
            ->byUserAndId($id, $userId)
            ->notCompleted()
            ->notInTheList()
            ->mustTask()
            ->firstOrFail();
    }

    /**
     * Get user tiem format based user personalization
     */
    private function getTimeFormat($format = '12hr')
    {
        return ['24hr' => ' H:i', '12hr' => ' h:i A'][$format];
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

        TaskNote::create($validatedData);
        return redirect('/dashboard/task', 302)->with('message', 'Successfully added task "' . $validatedData['title'] . '"');
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
            $validatedFormData['due_date'] = $this->setDefultDate();
        } else {
            $validatedFormData['due_date'] = $this->getTimestamp($validatedFormData['due_date']);
        }

        $validatedFormData['time'] = isset($validatedFormData['due_date']) ? $this->getTimestamp($validatedFormData['time']) : null;

        $message = TaskNote::byUserAndId($validatedFormData['id'], Auth::user()->id)
            ->notCompleted()
            ->notInTheList()
            ->mustTask()
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
        $queryString = explode('?', $reqeust->session()->previousUrl())[1] ?? null;
        $message = TaskNote::byUserAndId($reqeust->input('id', null), Auth::user()->id)
            ->notCompleted()
            ->notInTheList()
            ->mustTask()
            ->forceDelete() === 1 ? 'Successfully delete task "' . $reqeust->input('title', null) . '".' : "Task not found!";

        return ['message' => $message, 'previous-url' => '/dashboard/task?' . $queryString];
    }

    /**
     * Set and return task reminder
     */
    private function setReminder(string $reminder)
    {
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
     * Return user personalization setting
     */
    private function getPersonalization($user)
    {
        return json_decode($user->personalization);
    }

    /**
     * Return user default date setting
     */
    private function setDefultDate()
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
