<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardNext7DaysController extends Controller
{
    /**
     * Render dashboard task page
     */
    public function index()
    {
        return response()->view('dashboard.next7days', [
            'title' => $this->getPageTitle(2),
            'tasks' => ''
        ]);
    }

    /**
     * Get page title from route request uri
     */
    private function getPageTitle($index = 0)
    {
        return ucfirst(explode('/', request()->getRequestUri())[$index]);
    }
}
