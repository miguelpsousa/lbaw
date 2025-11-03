<?php

namespace App\Http\Controllers\Tasks;

use App\Models\Project;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\Notification;

class TaskController extends Controller
{
	/**
	 * Display the create task form.
	 *
	 * @return \Illuminate\View\View
	 */

     public function show($taskId)
     {
         if (!(auth()->check())) {
             return redirect('/login');
         }
     
         $task = Task::with(['project', 'responsibles', 'comments' => function($query) {
             $query->orderBy('updated_at', 'asc');
         }, 'comments.user'])->find($taskId);
     
         if (!$task) {
             return redirect()->route('projects')->with('error', 'Task not found');
         }
     
         $user = auth()->user();
         $userInProject = $task->project->members()->withPivot('invite_status')->firstWhere('id', auth()->id());
         if (!($user->is_admin || ($userInProject && $userInProject->pivot->invite_status == 'accepted'))) {
             return redirect()->route('projects')->with('error', 'You do not have access to project of id' . $taskId);
         }
     
         return view('pages.tasks.taskDetails', compact('task'));
     }

    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        return view('pages.tasks.createTask', compact('project'));
    }

    public function store(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:250',
            'due_date' => 'required|date|after:today',
            'priority' => 'required|integer|min:1',
        ]);

        $task = new Task($validatedData);
        $task->project_id = $project->id;
        $task->creator_id = auth()->user()->id;
        $task->status = 'pending';
        $task->save();

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Task created successfully.');
    }

    public function edit($taskId){    
        if(!(auth()->check())){
            return redirect('/login');
        }
        $task = Task::with('responsibles')->find($taskId);
        if(!$task){
            return redirect()->route('projects')->with('error', 'Task not found');
        }

		$userInProject = $task->project->members->firstWhere('id', auth()->id());
        if($userInProject && ($userInProject->pivot->role == 'Project Coordinator' || Auth::id() == $task->creator_id)){
            return view('pages.tasks.editTask', compact('task'));
        } else{
			return redirect()->route('projects')->with('error', 'You cannot edit this task');
		}
    }

    public function update(Request $request, $taskId){    
        if(!(auth()->check())){
            return redirect('/login');
        }
        $task = Task::with('responsibles')->find($taskId);
        if(!$task){
            return redirect()->route('projects')->with('error', 'Task not found');
        }

        $request->validate([
            'name' => 'required|max:50',
            'description' => 'required|max:250',
            'due_date' => 'required|date|after:today',
            'priority' => 'required|integer|min:1',
        ]);

		$userInProject = $task->project->members->firstWhere('id', auth()->id());
        // Check if user is one of the responsible members
        if(auth()->id() !== $task->created_by && (!$userInProject || $userInProject->pivot->role !== 'Project Coordinator')){
            return redirect()->route('projects')->with('error', 'You cannot edit this task');
        }

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
        ]);

        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task updated successfully!');
    }

    public function complete($taskId){    
        if(!(auth()->check())){
            return redirect('/login');
        }
        $task = Task::with('responsibles')->find($taskId);
        if(!$task){
            return redirect()->route('projects')->with('error', 'Task not found');
        }

        // Check if user is one of the responsible members
        if(!$task->responsibles->contains(auth()->id())){
            return redirect()->route('projects')->with('error', 'You are not responsible for this task');
        }

        $task->update(['status' => 'Completed']);

        $projectCoordinators = $task->project->members->filter(function($member) {
            return $member->pivot->role == 'Project Coordinator';
        });
    
        $notifiableUsers = $projectCoordinators->merge($task->responsibles);
    
        foreach ($notifiableUsers as $user) {
            Notification::create([
                'notification_text' => 'The task "' . $task->name . '" has been completed in project: ' . $task->project->name,
                'sender_id' => Auth::id(),
                'receiver_id' => $user->id,
                'project_id' => $task->project_id,
                'notification_type' => 'task',
            ]);
        }

        return redirect()->route('projects.show', $task->project_id)->with('success', 'Task completed successfully!');
    }

    public function assign(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            $memberId = $request->input('member_id');

            // Validate the member exists
            $member = User::findOrFail($memberId);

            // Add the member to the task's responsibles
            $task->responsibles()->attach($member->id);

            Notification::create([
                'notification_text' => 'You have been assigned to the task "' . $task->name . '" in project: ' . $task->project->name,
                'sender_id' => Auth::id(),
                'receiver_id' => $member->id,
                'project_id' => $task->project_id,
                'notification_type' => 'task',
            ]);

            return redirect()->route('projects.show', $task->project_id)->with("success", "Member assigned successfully");
        } catch (\Exception $e) {
            return redirect()->route('projects.show', $task->project_id)->with("error", "Failed to assign member");
        }
    }
}
