<?php

namespace App\Http\Controllers;

use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashboardCompleteController extends Controller
{
    /**
     * Render dashboard complete page
     */
    public function index()
    {
        $user = Auth::user();

        return response()->view('dashboard.complete', [
            'title' => 'Complete',
            'items' => $this->getItems($user),
            'timeFormat' => $this->getTimeFormat(json_decode($user->personalization, true)['time-format'])
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
            ->get();;
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
            ->first();

        $taskNotes->id = $id;
        $taskNotes->is_complete = 0;
        $taskNotes->save();

        return back(302)->with('message', 'Succesfully reopen task "' . $taskNotes->title . '"');
    }

    /**
     * Delete given task id
     */
    public function delete($id)
    {
        TaskNote::byUserAndId($id, Auth::user()->id)
            ->delete();

        return back(302)
            ->with('message', 'Succesfully deleted task');
    }
}
