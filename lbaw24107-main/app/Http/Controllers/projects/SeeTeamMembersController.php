<?php

namespace App\Http\Controllers\Projects;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class SeeTeamMembersController extends Controller
{
    /**
     * Display a listing of the team members.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($projectId)
    {   
        if(!(auth()->check())){
            return redirect('/login');
        }
        $project = Project::find($projectId);
        if(!$project){
            return redirect()->route('projects')->with('error', 'Project not found');
        }

        $teamMembers = $project->members()->withPivot('invite_status')->get();
        $userInProject = $teamMembers->firstWhere('id', auth()->id());
        if (!($userInProject && $userInProject->pivot->invite_status == 'accepted')) {
            return redirect()->route('projects')->with('error', 'You do not have access to project of id'. $projectId);
        }
        return view('pages.projects.seeTeamMembers', compact('teamMembers', 'projectId','userInProject'));
    }

    public function promoteToCoordinator (Request $request, $projectId, $userId){
            $project = Project::find($projectId);
        if (!$project) {
            return redirect()->route('projects')->with('error', 'Project not found');
        }
        $teamMembers = $project->members()->withPivot('invite_status')->get();
        $userInProject = $teamMembers->firstWhere('id', auth()->id());
        if (!($userInProject && $userInProject->pivot->invite_status == 'accepted')) {
            return redirect()->route('projects')->with('error', 'You do not have access to project of id'. $projectId);
        }
        $userToPromote = $project->members()->find($userId);
        if (!$userToPromote) {
            return redirect()->route('projects')->with('error', 'User not found in project');
        }
        $userToPromote->pivot->role = 'Project Coordinator';
        $userToPromote->pivot->save();

        // Notification for the promoted user
        Notification::create([
            'notification_text' => 'You are now a coordinator in project: ' . $project->name,
            'sender_id' => Auth::id(),
            'receiver_id' => $userId,
            'project_id' => $projectId,
            'notification_type' => 'project',
        ]);

        $projectMembers = $project->members;
        foreach ($projectMembers as $member) {
            if ($member->id != $userId) {
                Notification::create([
                    'notification_text' => $userToPromote->username . ' is now a coordinator in project: ' . $project->name,
                    'sender_id' => Auth::id(),
                    'receiver_id' => $member->id,
                    'project_id' => $projectId,
                    'notification_type' => 'project',
                ]);
            }
        }

        return redirect()->route('projects.team', $projectId)->with('success', 'User promoted to coordinator');
    }

    public function removeMember(Request $request, $projectId, $userId){
        $project = Project::find($projectId);
        if (!$project) {
            return redirect()->route('projects')->with('error', 'Project not found');
        }
        $teamMembers = $project->members()->withPivot('invite_status')->get();
        $userInProject = $teamMembers->firstWhere('id', auth()->id());
        if (!($userInProject && $userInProject->pivot->invite_status == 'accepted')) {
            return redirect()->route('projects')->with('error', 'You do not have access to project of id'. $projectId);
        }
        $userToRemove = $project->members()->find($userId);
        if (!$userToRemove) {
            return redirect()->route('projects')->with('error', 'User not found in project');
        }
        $project->members()->detach($userId);
        return redirect()->route('projects.team', $projectId)->with('success', 'User removed from project');
    }
}
?>