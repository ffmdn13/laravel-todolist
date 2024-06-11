<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardNotebookController extends Controller
{
    /**
     * Render dashboard noe page
     */
    public function index()
    {
        return response()->view('dashboard.notebook', [
            'title' => $this->getPageTitle(2),
            'notes' => ''
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
