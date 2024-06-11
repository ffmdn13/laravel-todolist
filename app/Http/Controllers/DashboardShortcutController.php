<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskNote;
use Illuminate\Support\Facades\Auth;

class DashboardShortcutController extends Controller
{
    /** 
     * Render dashboard shortcut page
     */
    public function index($id = null)
    {
        return response()->view('dashboard.shortcut', [
            'title' => $this->getPageTitle(2),
            'shortcuts' => TaskNote::whereBelongsTo(Auth::user(), 'user')
                ->notTrashed()
                ->isShortcuted()
                ->get(['id', 'title', 'type', 'due_date', 'priority']),
        ]);
    }

    /**
     * Get page name that user currently visit
     */
    private function getPageTitle($index = 1)
    {
        return ucfirst(explode('/', request()->getRequestUri())[$index]);
    }
}
