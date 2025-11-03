<?php

namespace App\Http\Controllers\Projects;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectCategory;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use App\Notifications\ProjectInvite;

class createProjectController extends Controller
{
    /**
     * Display the create project form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {   
        if(!(auth()->check())){
            return redirect('/login');
        }
        // Get all categories
        $categories = ProjectCategory::all();

        
        
        // Return the view with the categories and users
        return view('pages.projects.createProject', compact('categories'));
    }
    public function store(Request $request)
{       
    if(!(auth()->check())){
        return redirect('/login');
    }
    $request->validate([
        'name' => 'required|max:50',
        'description' => 'required|max:250',
        'category' => 'required|exists:project_category,id',
        'selected_users' => 'nullable|string',
    ]);

    // Create the project
    try{
        DB::transaction(function () use ($request) {
            $project = Project::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'ongoing',
                'project_category_id' => $request->category,
            ]);
        
            // Attach members to the project
            if ($request->selected_users) {
    
                $userIds = explode(',', $request->selected_users);
                foreach ($userIds as $userId) {
                    if($userId == auth()->id()) {
                        continue;
                    }
                    $user = User::find($userId);
                    if(!$user ) {
                        continue;
                    }
                    $project->members()->attach($userId, ['role' => 'Project Member','invite_status' => 'pending']);
                    
                    Notification::create([
                        'notification_text' => 'You have been invited to join the project: ' . $request->name,
                        'sender_id' => Auth::id(),
                        'receiver_id' => $userId,
                        'project_id' => $project->id,
                        'response' => 'pending',
                        'notification_type' => 'invitation',
                    ]);
                    $notificationId = Notification::get()->last()->id;
                    try{
                        $user->notify(new ProjectInvite($project,$notificationId, auth()->user()));
                    }
                    catch(\Exception $e){
                        Log::error($e);
                    }
                }
    
            }
            $project->members()->attach(auth()->id(), ['role' => 'Project Coordinator', 'invite_status' => 'accepted']);
        });
        return redirect()->route('projects')->with('success', 'Project created successfully!');
    }
    catch(\Exception $e){
        return redirect()->route('projects')->with('success', 'Project created successfully!');
    }
    
}
    
}