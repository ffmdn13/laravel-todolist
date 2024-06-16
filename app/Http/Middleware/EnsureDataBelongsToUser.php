<?php

namespace App\Http\Middleware;

use App\Models\Lists;
use App\Models\Notebook;
use App\Models\Tag;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EnsureDataBelongsToUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $table)
    {
        $tables = [
            'lists' => Lists::class,
            'tags' => Tag::class,
            'notebooks' => Notebook::class
        ];

        $id = explode('/', $request->getRequestUri())[3];
        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'present', 'numeric']
        ]);
        $userIdFromDB = $tables[$table]::select(['user_id'])
            ->findOrFail($validator->getData()['id'])->user_id;

        if ($validator->fails() || $userIdFromDB !== Auth::user()->id) {
            abort(404);
        }

        return $next($request);
    }
}
