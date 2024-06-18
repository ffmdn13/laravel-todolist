<?php

namespace App\Http\Middleware;

use App\Models\Lists;
use App\Models\Notebook;
use App\Models\Tag;
use App\Models\TaskNote;
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
    public function handle(Request $request, Closure $next, $type, $isWithTrash)
    {
        $tables = [
            'lists' => Lists::class,
            'tags' => Tag::class,
            'notebooks' => Notebook::class,
            'task_notes' => TaskNote::class
        ];

        [$table, $index] = explode(';', $type);

        $id = explode('/', $request->getRequestUri())[$index];
        $table = $tables[$table];
        $validator = Validator::make(['id' => $id], ['id' => 'required|present|numeric']);

        if ($validator->fails()) {
            return abort(404);
        }

        if ($isWithTrash === 'true') {
            $table::select(['user_id'])
                ->withTrashed()
                ->byUserAndId($id, Auth::user()->id)
                ->firstOrFail();
        } else {
            $table::select(['user_id'])
                ->byUserAndId($id, Auth::user()->id)
                ->firstOrFail();
        }

        return $next($request);
    }
}
