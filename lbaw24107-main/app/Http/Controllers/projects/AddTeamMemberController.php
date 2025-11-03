<?php

namespace App\Http\Controllers\Projects;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\ProjectInvite;
class AddTeamMemberController extends Controller
{
    /**
     * Add a team member to the project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function addTeamMember(Request $request, $projectId)
    {   
        if(!(auth()->check())){
            return redirect('/login');
        }
        $request->validate([
            'user_id' => 'required|exists:account,id',
        ]);
        $project = Project::with('members')->find($projectId);
        if(!$project) {
            return redirect()->route('projects')->with('error', 'Project not found');
        }
        $userInProject = $project->members->firstWhere('id', auth()->id());
        if(!($userInProject && $userInProject->pivot->role == 'Project Coordinator')) {
            return redirect()->route('projects')->with('error', 'You are not a Project Coordinator of project of id '. $projectId);
        }
        

        $user = User::find($request->input('user_id'));
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($project->members()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'User is already a team member'], 400);
        }

        $project->members()->attach($user->id, [
            'role' => 'Project Member',
            'favorite' => false,
            'invite_status' => 'pending',
        ]);
        
        Notification::create([
            'notification_text' => 'You have been invited to join the project: ' . $project->name,
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'project_id' => $project->id,
            'response' => 'pending',
            'notification_type' => 'invitation'
        ]);
        $notificationId = Notification::get()->last()->id;
        
        try{
            $user->notify(new ProjectInvite($project,$notificationId, auth()->user()));
        }catch(\Exception $e){
            Log::error('Failed to send notification to user '.$user->id.' for project '.$project->id);
        }


        return response()->json(['message' => 'Team member added successfully'], 200);
    }
}