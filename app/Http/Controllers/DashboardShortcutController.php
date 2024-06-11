<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardShortcutController extends Controller
{
    /** 
     * Render dashboard shortcut page
     */
    public function index()
    {
        return response()->view('dashboard.shortcut', [
            'title' => $this->getPageTitle(2),
            'shortcuts' => ''
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
