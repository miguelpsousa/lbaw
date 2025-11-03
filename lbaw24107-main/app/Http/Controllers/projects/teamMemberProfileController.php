<?php

namespace App\Http\Controllers\Projects;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class teamMemberProfileController extends Controller
{
    /**
     * Display the profile of a team member. 
     *
     * @return \Illuminate\Http\Response
     */
    public function show($projectId,$userId)
    {   
        if(!(auth()->check())){
            return redirect('/login');
        }
        if(auth()->id() == $userId){
            return redirect()->route('profile.show', ['user' => $userId]);
        }
        $project = Project::find($projectId);
        if(!$project){
            return redirect()->route('projects')->with('error', 'Project not found');
        }
        
        $userInProject = $project->members()->withPivot('invite_status')->find(auth()->id());
        if (!($userInProject && $userInProject->pivot->invite_status == 'accepted')) {
            return redirect()->route('projects')->with('error', 'You do not have access to project of id'. $projectId);
        }
        $teamMember = $project->members()->withPivot('invite_status')->find($userId);
        if(!($teamMember && $teamMember->pivot->invite_status == 'accepted')){
            return redirect()->route('projects')->with('error', 'Team member not found');
        }
        if($teamMember->pivot->invite_status != 'accepted'){
            return redirect()->route('projects')->with('error', 'Team member did not accept the invitation');
        }
        return view('pages.projects.teamMemberProfile', compact('teamMember', 'project'));
    }
}
?>