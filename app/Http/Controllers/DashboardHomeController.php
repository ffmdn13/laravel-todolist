<?php

namespace App\Http\Controllers;

use App\Models\Lists;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardHomeController extends Controller
{
    /**
     * Rendering home dashboard page
     */
    public function index()
    {
        $userObj = Auth::user();

        return response()->view('dashboard.index', [
            'title' => $this->getPageTitle(),
            'welcomeText' => $this->getWelcomeText(),
            'parseTimeToGreeting' => $this->parseTimeToGreeting() . ', ' . $userObj->nickname . '👋',
            'priorityColor' => $this->getPriorityColor(),
            'notifications' => null,
            'heroImage' => $this->getHeroImage()
        ]);
    }

    /**
     * Get page name that user currently visit
     */
    private function getPageTitle()
    {
        return ucfirst(explode('/', request()->getRequestUri())[1]);
    }

    /**
     * Generate random welcome text
     */
    private function getWelcomeText()
    {
        return [
            'Start your journey to productivity here.',
            'Welcome! \'s make today count.',
            'Your day just got organized.',
            'Ready to conquer your tasks?',
            'Welcome to a world of productivity.',
            'Get things done, one step at a time.',
            'Let\'s achieve your goals together.',
            'Stay focused, stay organized.',
            'Welcome! Time to check off your list.',
            'Your productivity partner awaits.',
            'Plan. Execute. Achieve.',
            'Welcome! Your tasks, managed.',
            'Ready to boost your productivity?',
            'Let\'s get organized!',
            'Your task journey begins here.',
            'Welcome! \'s tackle your to-do list.',
            'Success starts with a plan.',
            'Make every day productive.',
            'Welcome! Your organized life starts now.',
            'Turn tasks into accomplishments.'
        ][rand(0, 19)];
    }

    /**
     * Parse time into greeting text based on user time format personalization, 
     * whether is 24hr or AM format
     */

    private function parseTimeToGreeting()
    {
        $timeFormat = '24hr';
        return $timeFormat === '24hr' ? $this->parse24HrTime() : $this->parse12AmTime();
    }

    /**
     * Parse time into greeting text based on 24hr time format
     */
    private function parse24HrTime()
    {
        $currentHour = (int)Carbon::now()->toTimeString();
        $currentHour >= 0 && $currentHour <= 4 ? $currentHour += 100 : $currentHour;

        $hours = [
            'Good Morning' => [5, 10],
            'Good Afternoon' => [11, 14],
            'Good Evening' => [15, 18],
            'Good Night' => [19, 400]
        ];

        foreach ($hours as $greeting => $hour) {
            if ($currentHour >= $hour[0] && $currentHour <= $hour[1]) {
                return $greeting;
            }
        }
    }

    /**
     * Parse time into greeting text based on AM & PM time format
     */
    private function parse12AmTime()
    {
        //
    }

    /**
     * Return priority random priority color for task
     */
    private function getPriorityColor()
    {
        return ['color-red', 'color-blue', 'color-green'][rand(0, 2)];
    }

    /**
     * Return random hero image for dashboard home page
     */
    private function getHeroImage()
    {
        return [
            'https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGZvcmVzdHxlbnwwfHwwfHx8MA%3D%3D',
            'https://images.unsplash.com/photo-1545569341-9eb8b30979d9?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8amFwYW58ZW58MHx8MHx8fDA%3D',
            'https://images.unsplash.com/photo-1477948879622-5f16e220fa42?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxleHBsb3JlLWZlZWR8NHx8fGVufDB8fHx8fA%3D%3D',
            'https://images.unsplash.com/photo-1498307833015-e7b400441eb8?q=80&w=1528&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'
        ][rand(0, 3)];
    }
}
