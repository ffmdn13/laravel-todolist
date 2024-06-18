<?php

namespace App\Http\Controllers;

use App\Models\TaskNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardTrashController extends Controller
{
    /**
     * Render dashboard noe page
     */
    public function index()
    {
        $user = Auth::user();

        return response()->view('dashboard.trash', [
            'title' => 'Trash',
            'items' => $this->getItems($user),
            'timeFormat' => $this->getTimeFormat(json_decode($user->personalization, true)['time-format'])
        ]);
    }

    /**
     * Get complete task that belongs to user
     */
    private function getItems($user)
    {
        return TaskNote::with(['list', 'tag', 'notebook'])
            ->select(['id', 'title', 'type', 'due_date', 'time', 'priority', 'list_id', 'tag_id', 'notebook_id'])
            ->whereBelongsTo($user, 'user')
            ->onlyTrashed()
            ->get();
    }

    /**
     * Get user tiem format based user personalization
     */
    private function getTimeFormat($format = '24hr')
    {
        return ['24hr' => 'H:i', '12hr' => ' h:i A'][$format];
    }

    /**
     * Reopen or uncomplete given task
     */
    public function reopen($id)
    {
        TaskNote::byUserAndId($id, Auth::user()->id)
            ->onlyTrashed()
            ->restore();

        return back(302)
            ->with('message', 'Successfully restore deleted task/note');
    }

    /**
     * Delete given task id
     */
    public function delete($id)
    {
        TaskNote::byUserAndId($id, Auth::user()->id)
            ->forceDelete();

        return back(302)
            ->with('message', 'Succesfully deleted task/note');
    }
}
