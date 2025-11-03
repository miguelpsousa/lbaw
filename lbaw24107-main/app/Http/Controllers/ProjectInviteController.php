<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class ProjectInviteController extends Controller
{
    /**
     * Handle the accept/decline action for the project invitation.
     *
     * @param  Request  $request
     * @param  int  $projectId
     * @param  string  $response
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function respond(Request $request, $projectId, $notificationId ,$response, $token)
    {
        
        // Verify token and check if it matches the expected token for the user
        $user = auth()->user();
        Log::info("first here");
        Log::info($user);
        Log::info($token);
        if (!$this->verifyToken($user, $token)) {
            return redirect()->route('dashboard')->withErrors(['message' => 'Invalid or expired token.']);
        }
        Log::info("here");
        if($response !== 'accept' && $response !== 'decline'){
            Log::info("also here");
            return redirect()->route('dashboard')->withErrors(['message' => 'Invalid response.']);
        }
        // Get the project
        $project = Project::find($projectId);
        if (!$project) {
            Log::info("also here3");
            return redirect()->route('dashboard')->withErrors(['message' => 'Project not found.']);
        }

        Log::info("also here");
        $notification = Notification::find($notificationId);

        if ($request->response === 'accept') {
            // Update invite status to accepted
            DB::table('project_member')
                ->where('user_id', $user->id)
                ->where('project_id', $projectId)
                ->update(['invite_status' => 'accepted']);
            $notification->update(['response' => 'accepted','read_status' => true]);
        } else {
            // Remove the invite entry
            DB::table('project_member')
                ->where('user_id', $user->id)
                ->where('project_id', $projectId)
                ->delete();
            $notification->update(['response' => 'declined','read_status' => true]);
        }

        return redirect()->route('projects')->withSuccess('Project invitation response submitted.');
    }

    /**
     * Verify the invite token.
     *
     * @param  User  $user
     * @param  string  $token
     * @return bool
     */
    private function verifyToken(User $user, $token)
    {
        // Check if the token matches the one generated for the user
        return hash('sha256', $user->email . $user->id) === $token;
    }
}
