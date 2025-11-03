<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;


class dashboardController extends Controller
{
    /**
     * Show the get started page. 
     */
    public function show()
    {
        $user = auth()->user();
        $tasks = Task::whereHas('responsibles', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('pages.dashboard', compact('tasks'));
    }

}