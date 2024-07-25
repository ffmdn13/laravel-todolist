<?php

use App\Http\Controllers\DashboardCompleteController;
use App\Http\Controllers\DashboardListController;
use App\Http\Controllers\DashboardNotebookController;
use App\Http\Controllers\DashboardNoteController;
use App\Http\Controllers\DashboardShortcutController;
use App\Http\Controllers\DashboardTagsController;
use App\Http\Controllers\DashboardTaskController;
use App\Http\Controllers\DashboardTrashController;
use App\Http\Controllers\DashboardTodayController;
use App\Http\Controllers\DeleteUserController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\UpdatePasswordController;
use App\Http\Controllers\UserAccountInfoController;
use App\Http\Controllers\UserApperanceSettingController;
use App\Http\Controllers\UserDatetimeSettingController;
use App\Http\Controllers\UserNotificationSettingController;
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
 * redirect /dashboard route to / route
 */
Route::redirect('/', '/dashboard', 302);

Route::get('/test', function () {
});

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
 * Forget password route
 */
Route::get('/forget-password', [ForgetPasswordController::class, 'index']);
Route::post('/forget-password', [ForgetPasswordController::class, 'reset']);

/**
 * Logout route
 */
Route::get('/logout', [LogoutController::class, 'logout'])->middleware('auth');

/**
 * Todolist dashboard route
 */
Route::get('/dashboard', [DashboardTaskController::class, 'index'])->middleware('auth');
Route::prefix('/dashboard')->middleware('auth')->group(function () {
    /**
     * Controller for task dashboard page
     */
    Route::get('/task/{id?}/{title?}', [DashboardTaskController::class, 'index'])
        ->whereNumber('id');
    Route::post('/task/add', [DashboardTaskController::class, 'add']);
    Route::post('/task/action', [DashboardTaskController::class, 'action']);

    /**
     * Sidebar add new task controller
     */
    Route::post('/sidebar-new-task', [SidebarController::class, 'add']);

    /**
     * Controller for note dashboard page
     */
    Route::get('/note/{id?}/{title?}', [DashboardNoteController::class, 'index'])
        ->whereNumber('id');
    Route::post('note/add', [DashboardNoteController::class, 'add']);
    Route::post('/note/action', [DashboardNoteController::class, 'action']);

    /**
     * Controller for shortcut dashboard page
     */
    Route::get('/shortcut', [DashboardShortcutController::class, 'index']);
    Route::get('/shortcut/view/{id}/{title}', [DashboardShortcutController::class, 'view'])
        ->whereNumber('id');
    Route::post('/shortcut/view/action', [DashboardShortcutController::class, 'action']);

    /**
     * Controller for list dashboard page
     */
    Route::get('/list/{id}/{title}', [DashboardListController::class, 'index'])
        ->middleware('verify.list')
        ->whereNumber('id');
    Route::post('/list/add', [DashboardListController::class, 'add']);
    Route::post('/list/add/task', [DashboardListController::class, 'addTask']);
    Route::post('/list/delete', [DashboardListController::class, 'delete']);
    Route::post('/list/action', [DashboardListController::class, 'action']);

    /**
     * Controller for tag dashboard page
     */
    Route::get('/tag/{id}/{title}', [DashboardTagsController::class, 'index'])
        ->middleware('verify.tag')
        ->whereNumber('id');
    Route::post('/tag/add', [DashboardTagsController::class, 'add']);
    Route::post('/tag/add/task', [DashboardTagsController::class, 'addTask']);
    Route::post('/tag/delete', [DashboardTagsController::class, 'delete']);
    Route::post('/tag/action', [DashboardTagsController::class, 'action']);

    /**
     * Controller for notebook dashboard page
     */
    Route::get('/notebook/{id}/{title}', [DashboardNotebookController::class, 'index'])
        ->middleware('verify.notebook')
        ->whereNumber('id');
    Route::post('/notebook/add', [DashboardNotebookController::class, 'add']);
    Route::post('/notebook/add/note', [DashboardNotebookController::class, 'addNote']);
    Route::post('/notebook/delete', [DashboardNotebookController::class, 'delete']);
    Route::post('/notebook/action', [DashboardNotebookController::class, 'action']);

    /**
     * Controller fot today dashboard page
     */
    Route::get('/today/{id?}/{title?}', [DashboardTodayController::class, 'index'])
        ->whereNumber('id');
    Route::post('/today/add', [DashboardTodayController::class, 'add']);
    Route::post('/today/action', [DashboardTodayController::class, 'action']);

    /**
     * Controller for complete dsahboard page
     */
    Route::get('/complete', [DashboardCompleteController::class, 'index']);
    Route::get('/complete/reopen/{id}', [DashboardCompleteController::class, 'reopen'])
        ->whereNumber('id');
    Route::get('/complete/delete/{id}', [DashboardCompleteController::class, 'deleteTask'])
        ->whereNumber('id');
    Route::get('/complete/view/{id}/{title}', [DashboardCompleteController::class, 'view'])
        ->whereNumber('id');
    Route::post('/complete/view/action', [DashboardCompleteController::class, 'action']);

    /**
     * Controller for trash dashboard page
     */
    Route::get('/trash', [DashboardTrashController::class, 'index']);
    Route::get('/trash/reopen/{id}', [DashboardTrashController::class, 'reopen'])
        ->whereNumber('id');
    Route::get('/trash/delete/{id}', [DashboardTrashController::class, 'deleteNote'])
        ->whereNumber('id');
    Route::get('/trash/view/{id}/{title}', [DashboardTrashController::class, 'view'])
        ->whereNumber('id');
    Route::post('/trash/view/action', [DashboardTrashController::class, 'action']);
});

/**
 * User profile and personalization setting route
 */
Route::redirect('/dashboard/user/profile', '/user/profile');
Route::redirect('/dashboard/user/setting', '/user/setting/apperance');
Route::prefix('/user')->middleware('auth')->group(function () {
    /**
     * User profile setting controller
     */
    Route::get('/profile', [UserAccountInfoController::class, 'index']);
    Route::post('/profile/update/account/info', [UserAccountInfoController::class, 'updateAccountInfo']);
    Route::get('/profile/change/password', [UpdatePasswordController::class, 'updatePassword']);
    Route::post('/profile/change/password', [UpdatePasswordController::class, 'update']);
    Route::post('/profile/delete/account', [DeleteUserController::class, 'delete']);

    /**
     * User personalization setting controller
     */
    Route::get('/setting/apperance', [UserApperanceSettingController::class, 'index']);
    Route::post('/setting/apperance/update', [UserApperanceSettingController::class, 'update']);
    Route::get('/setting/datetime', [UserDatetimeSettingController::class, 'index']);
    Route::post('/setting/datetime/update', [UserDatetimeSettingController::class, 'update']);
    Route::get('/setting/notification', [UserNotificationSettingController::class, 'index']);
    Route::post('/setting/notification/update', [UserNotificationSettingController::class, 'update']);
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
