<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DashboardTagsController extends Controller
{
    /**
     * Render dashboard task page
     */
    public function index(Request $request, $id, $title = 'Tag')
    {
        $user = Auth::user();
        $personalization = $this->getPersonalization($user);

        return response()->view('dashboard.tag', [
            'title' => $title,
            'tagId' => $id,
            'tagTitle' => $title,
            'tasks' => $this->getItems($id, $user->id, $request->query('order', null)),
            'view' => $this->view($request->query('view', null), $id, $user->id),
            'color' => $request->query('clr', null),
            'timeFormat' => $this->getTimeFormat($personalization->datetime->time_format),
            'url' => getSortByDelimiter($request->fullUrl()),
            'queryParams' => $this->getQueryParameters($request, '&'),
            'theme' => $personalization->apperance->theme
        ]);
    }

    /**
     * Return tasks related to current tag
     */
    private function getItems($tagId, $userId, $order)
    {
        return TaskNote::select(['id', 'title', 'priority', 'reminder', 'due_date'])
            ->byTagAndUser($tagId, $userId)
            ->notCompleted()
            ->mustTask()
            ->orderedBy($this->valiatedOrderByParam($order))
            ->simplePaginate(10)
            ->withQueryString();
    }

    private function view($view, $tagId, $userId)
    {
        $validator = Validator::make(['view' => $view], [
            'view' => ['numeric', 'exists:task_notes,id']
        ]);

        if ($validator->fails()) {
            return null;
        }

        return TaskNote::select(['id', 'title', 'description', 'priority', 'due_date', 'time', 'reminder', 'is_complete'])
            ->where('id', $validator->getData()['view'])
            ->byTagAndUser($tagId, $userId)
            ->notCompleted()
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
     * Add new tag
     */
    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'present', 'string', 'max:255', 'unique:tags,title'],
            'color' => ['required', 'present', 'string', Rule::in(['black', 'blue', 'green', 'red', 'cyan', 'purple', 'orange'])]
        ]);
        $validatedData['user_id'] = Auth::user()->id;

        Tag::create($validatedData);

        return redirect('/dashboard', 302);
    }

    /**
     * Add new task to current tag
     */
    public function addTask(Request $request)
    {
        $validatedData = $request->validate([
            'id' => ['required', 'present', 'numeric', 'exists:tags,id'],
            'title' => ['required', 'present', 'string', 'max:255'],
            'priority' => ['required', 'present', Rule::in(['0', '1', '2', '3'])],
        ]);
        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['tag_id'] = $validatedData['id'];

        TaskNote::create($validatedData);

        return redirect($request->session()->previousUrl(), 302)
            ->with('message', 'Successfully added task "' . $validatedData['title'] . '"');
    }

    /**
     * Delete given list
     */
    public function delete(Request $request)
    {
        $validatedData = $request->validate(['id' => ['required', 'present', 'numeric', 'exists:tags,id']]);
        $userId = Auth::user()->id;

        Tag::byUserAndId($validatedData['id'], $userId)->delete();
        TaskNote::byTagAndUser($validatedData['id'], $userId)
            ->notCompleted()
            ->forceDelete();

        return redirect('/dashboard', 302)
            ->with('message', 'Successfully delete tag');
    }

    /**
     * Determine the action value for the appropriate function name
     */
    public function action(Request $request)
    {
        $action = $request->validate(['action' => ['required', 'present', Rule::in(['saveTask', 'deleteTask'])]]);
        $message = call_user_func([__CLASS__, $action['action']], $request);

        return redirect($message['previous-url'], 302)
            ->with('message', $message['message']);
    }

    /**
     * Update or save task related to current tag
     */
    private function saveTask(Request $request)
    {
        $validatedFormData = $this->validateData($request);

        if ($validatedFormData['due_date'] === null && isset($validatedFormData['time'])) {
            $validatedFormData['due_date'] = $this->setDefaultDate();
        } else {
            $validatedFormData['due_date'] = $this->getTimestamp($validatedFormData['due_date']);
        }

        $validatedFormData['time'] = isset($validatedFormData['due_date']) ? $this->getTimestamp($validatedFormData['time']) : null;

        $message = TaskNote::byTagAndUser($validatedFormData['tag_id'], Auth::user()->id)
            ->where('id', $validatedFormData['id'])
            ->notCompleted()
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
     * Delete given task related to current tag
     */
    private function deleteTask(Request $request)
    {
        $message = TaskNote::byTagAndUser($request->input('tag_id', 0), Auth::user()->id)
            ->where('id', $request->input('id', null))
            ->notCompleted()
            ->mustTask()
            ->forceDelete() === 1 ? 'Successfully deleted task "' . $request->input('title', null) . '".' : "Task not found!";

        preg_match_all('/(?<=\?|\&)(?!view=\d+\b)[^&]+/', $request->session()->previousUrl(), $match);
        $queryString = implode('&', $match[0]);

        $tagId = $request->input('tag_id', null);
        $tagTitle = $request->input('tag_title', null);
        $previousUrl = "/dashboard/tag/$tagId/$tagTitle?$queryString";

        return ['message' => $message, 'previous-url' => $previousUrl];
    }

    /**
     * Return validated data
     */
    private function validateData(Request $request)
    {
        $rules = [
            'id' => ['required', 'present', 'numeric', 'exists:task_notes,id'],
            'tag_id' => ['required', 'present', 'numeric', 'exists:tags,id'],
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
     * Get url query paramters
     */
    private function getQueryParameters(Request $request, $delimiter = '?')
    {
        preg_match('/\?([a-zA-Z0-9=\&_]+)/', $request->fullUrlWithoutQuery(['view', 'clr']), $match);

        $queryParams = $match[1] ?? null;
        return $delimiter . $queryParams;
    }

    /**
     * Return user personalization setting
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
