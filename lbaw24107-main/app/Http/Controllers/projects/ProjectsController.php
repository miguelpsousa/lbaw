<?php

namespace App\Http\Controllers\projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the projects the user is in.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is an admin
        if ($user->is_admin) {
            // Get all projects for admin
            $projects = Project::with('category')->get();
        } else {
            // Get all projects the user is in
            $projects = $user->projects()->wherePivot('invite_status', 'accepted')->with('category')->get();
        }
        $favorites = false;
        // Return the view with the projects
        return view('pages.projects.projects', compact('projects','favorites'));
    }

    /**
     * Display the specified project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Get the authenticated user
        $user = Auth::user();

        // Get the project with tasks
        $project = Project::with(['category', 'tasks'])->findOrFail($id);

        // Check if the user is an admin or a member of the project
        if ($user->is_admin || $user->projects()->where('project_id', $id)->exists()) {
            // Return the view with the project details
            return view('pages.projects.projectDashboard', compact('project'));
        } else {
            return redirect('/')->with('error', 'Access denied.');
        }
    }
    public function index_favorites(){

        if(!Auth::check()){
            return redirect('/login');
        }
        
        $favoriteProjects = Auth::user()->projects()->wherePivot('favorite', true)->get();
        Log::info($favoriteProjects);
        $favorites = true;
        return view('pages.projects.projects', compact('favoriteProjects','favorites'));
    }
}