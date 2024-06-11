<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardTrashController extends Controller
{
    /**
     * Render dashboard noe page
     */
    public function index()
    {
        return response()->view('dashboard.trash', [
            'title' => $this->getPageTitle(2),
            'items' => ''
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
