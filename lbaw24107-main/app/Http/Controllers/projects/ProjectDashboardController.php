<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class ProjectDashboardController extends Controller
{
    /**
    * Display the project dashboard.
    *
    * @return \Illuminate\View\View
    */
    public function show($projectId)
    {   
        if(!(Auth::check())){
            return redirect('/login');
        }
        // Get the project
        $project = Project::with('members')->find($projectId);
        if(!$project){
            return redirect()->route('projects')->with('error', 'Project not found');
        }
        $user = auth()->user();
        $tasks = Task::where('project_id', $projectId)->get();
        $member = $project->members()->withPivot('favorite')->firstWhere('id', $user->id);

        $recentMessages = Message::where('project_id', $projectId)
                ->whereNull('parent_id')
                ->with('replies')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

        // Check if the user is an admin or a member of the project
        if ($user->is_admin || ($project->members->contains($user->id) && $project->members->firstWhere('id', $user->id)->pivot->invite_status == 'accepted')) {
            return view('pages.projects.projectDashboard', [
                'project' => $project,
                'tasks' => $project->tasks,
                'recentMessages' => $recentMessages,
                'favorite' => $member->pivot->favorite
            ]);
        } else {
            return redirect()->route('projects')->with('error', 'You do not have access to this project');
        }
    }

    public function edit(Project $project){
        if (Auth::user()->is_admin || $project->members->contains(Auth::id())) {
            $categories = ProjectCategory::all();
            return view('pages.projects.editProject', compact('project', 'categories'));
        } else {
            return redirect()->route('projects')->with('error', 'You do not have access to this project');
        }
    }

    public function update(Request $request, Project $project){
        if (Auth::user()->is_admin || $project->members->contains(Auth::id())) {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|exists:project_category,id',
            ]);

            $project->name = $validatedData['name'];
            $project->description = $validatedData['description'];
            $project->project_category_id = $validatedData['category'];

            $project->save();

            return redirect()->route('projects.show', $project->id)->with('success', 'Project updated successfully');
        } else {
            return redirect()->route('projects')->with('error', 'You do not have access to this project');
        }
    }

    public function archive(Project $project){
        if (Auth::user()->is_admin || $project->members->contains(Auth::id())) {
            $project->status = 'archived';
            $project->save();

            $projectMembers = $project->members;
            foreach ($projectMembers as $member){
                Notification::create([
                    'notification_text' => 'Project "' . $project->name . '" has been archived',
                    'sender_id' => Auth::id(),
                    'receiver_id' => $member->id,
                    'project_id' => $project->id,
                    'notification_type' => 'project',
                ]);
            }

            return redirect()->route('projects')->with('success', 'Project archived successfully');
        } else {
            return redirect()->route('projects')->with('error', 'You do not have access to this project');
        }
    }
}
