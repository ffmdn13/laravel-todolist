<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardListController extends Controller
{
    /**
     * Render dashboard list page with given id parameter
     */
    public function index($id = 0)
    {
        return response()->view('dashboard.list', [
            'title' => $this->getPageTitle(2),
            'tasks' => ''
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
