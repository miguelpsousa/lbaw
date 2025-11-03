<?php
namespace App\Http\Controllers\Projects;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use Illuminate\Support\Facades\DB;


class LeaveProjectController extends Controller
{
    public function leave(Request $request, $projectId)
    {
        $user = Auth::user();
        $project = Project::findOrFail($projectId);
        // Check if the user is a member of the project
        if (!$project->members->contains($user->id)) {
            return redirect()->back()->with('error', 'You are not a member of this project.');
        }
        DB::transaction(function () use ($user, $project) {
            // Remove the user from the project
            $project->members()->detach($user->id);

            // Remove the user's tasks from the project
            $tasks = $project->tasks();
            foreach ($tasks as $task) {
                $task->resposibles()->detach($user->id);
            }

        });
        

        return redirect()->route('projects')->with('success', 'You have successfully left the project.');
    }
}