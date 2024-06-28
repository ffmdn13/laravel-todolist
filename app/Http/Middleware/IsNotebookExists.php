<?php

namespace App\Http\Middleware;

use App\Models\Notebook;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsNotebookExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $listId = explode('/', $request->getRequestUri())[3];

        Notebook::byUserAndId($listId, Auth::user()->id)
            ->firstOrFail();

        return $next($request);
    }
}
