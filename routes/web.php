<?php

use App\Http\Controllers\DashboardCompleteController;
use App\Http\Controllers\DashboardHomeController;
use App\Http\Controllers\DashboardListController;
use App\Http\Controllers\DashboardNext7DaysController;
use App\Http\Controllers\DashboardNotebookController;
use App\Http\Controllers\DashboardNoteController;
use App\Http\Controllers\DashboardShortcutController;
use App\Http\Controllers\DashboardTagsController;
use App\Http\Controllers\DashboardTaskController;
use App\Http\Controllers\DashboardTrashController;
use App\Http\Controllers\DashboardTodayController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * If use try navigate to / route, it will redirect to /dashboard route
 */
Route::redirect('/', '/dashboard', 302);

/**
 * Login route
 */
Route::get('/login', [LoginController::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'doLogin'])->middleware('guest');

/**
 * Account registration route
 */
Route::get('/register', [RegisterController::class, 'index'])->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'doRegister'])->middleware('guest');

/**
 * Logout route
 */
Route::get('/logout', [LogoutController::class, 'logout'])->middleware('auth');

/**
 * Todolist dashboard route
 */
Route::get('/dashboard', [DashboardHomeController::class, 'index'])->middleware('auth');
Route::prefix('/dashboard')->middleware('auth')->group(function () {

    /**
     * Controller for task dashboard page
     */
    Route::get('/task/{id?}', [DashboardTaskController::class, 'index'])
        ->whereNumber('id');
    Route::post('/task/add', [DashboardTaskController::class, 'add']);
    Route::post('/task/action', [DashboardTaskController::class, 'action']);

    /**
     * Controller for note dashboard page
     */
    Route::get('/note/{id?}', [DashboardNoteController::class, 'index'])
        ->whereNumber('id');
    Route::post('note/add', [DashboardNoteController::class, 'add']);
    Route::post('/note/action', [DashboardNoteController::class, 'action']);

    /**
     * Controller for shortcut dashboard page
     */
    Route::get('/shortcut/', [DashboardShortcutController::class, 'index']);

    /**
     * Controller for list dashboard page
     */
    Route::get('/lists/{id}/{title}', [DashboardListController::class, 'index'])
        ->whereNumber('id');
    Route::post('/lists/add', [DashboardListController::class, 'add']);
    Route::post('/lists/add/task', [DashboardListController::class, 'addTask']);
    Route::post('/lists/delete', [DashboardListController::class, 'delete']);
    Route::post('/lists/action', [DashboardListController::class, 'action']);

    /**
     * Controller for tag dashboard page
     */
    Route::get('/tag/{id}/{title}', [DashboardTagsController::class, 'index'])
        ->whereAlphaNumeric('id');
    Route::post('/tag/add', [DashboardTagsController::class, 'add']);
    Route::post('/tag/add/task', [DashboardTagsController::class, 'addTask']);
    Route::post('/tag/delete', [DashboardTagsController::class, 'delete']);
    Route::post('/tag/action', [DashboardTagsController::class, 'action']);

    /**
     * Controller for notebook dashboard page
     */
    Route::get('/notebooks/{id}', [DashboardNotebookController::class, 'index']);

    /**
     * Controller fot today dashboard page
     */
    Route::get('/today', [DashboardTodayController::class, 'index']);

    /**
     * Controller next7days dashboard page
     */
    Route::get('/next7days', [DashboardNext7DaysController::class, 'index']);

    /**
     * Controller for trash dashboard page
     */
    Route::get('/trash', [DashboardTrashController::class, 'index']);

    /**
     * Controller for complete dsahboard page
     */
    Route::get('/complete', [DashboardCompleteController::class, 'index']);
});

/**
 * Session route for debugging and flush session data
 */
Route::prefix('/session')->group(function () {
    Route::get('/view', function () {
        dd(Session::all());
    });

    Route::get('/delete', function () {
        Session::flush();
    });

    Route::get('/flash', function () {
        dd(session()->get('registerSuccess'));
    });
});
