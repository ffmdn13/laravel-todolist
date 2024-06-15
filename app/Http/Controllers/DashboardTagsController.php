<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardTagsController extends Controller
{
    /**
     * Render dashboard task page
     */
    public function index(Request $request, Tag $tag, $id, $title)
    {
        return response()->view('dashboard.tags', [
            'title' => $this->getPageTitle(2),
            'tagId' => $id,
            'tagTitle' => $title,
            'tasks' => $this->getTasks($tag),
            'preview' => $this->preview(),
            'color' => $request->query('clr', null)
        ]);
    }

    /**
     * Get page title from route request uri
     */
    private function getPageTitle($index = 0)
    {
        return ucfirst(explode('/', request()->getRequestUri())[$index]);
    }

    /**
     * Return tasks related to current tag
     */
    private function getTasks(Tag $tag)
    {
        return $tag->taskNotes()
            ->select(['id', 'title', 'priority', 'due_date', 'reminder'])
            ->get();
    }

    /**
     * Preview a given task that related to tag id
     */
    private function preview()
    {
        return null;
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
}
