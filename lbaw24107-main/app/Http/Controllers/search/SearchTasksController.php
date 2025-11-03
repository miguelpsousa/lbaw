<?php

namespace App\Http\Controllers\Search;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class SearchTasksController extends Controller
{
    public function search(Request $request)
    {
        // Retrieve query parameters
        $query = $request->query('query');
        $status = $request->query('status');
        $projectId = $request->query('project_id');
        $priority = $request->query('priority');
        $dueDate = $request->query('due_date');
        $exact = $request->query('exact');
        // Start building the query
        $tasksQuery = Task::query();

        // Filter by search query (task name or description)
        if ($query) {
            if($exact === 'true'){
                $tasksQuery->where('name', $query);
            }
            else{
                $tasksQuery->where('name', 'LIKE', "%{$query}%");
            }
        }

        // Filter by status, if provided
        if ($status && $status != 'all') {
            $tasksQuery->where('status', $status);
        }
        if($priority){
            $tasksQuery->where('priority', $priority);
        }
        if($dueDate ){
            $tasksQuery->where('due_date', '<=', $dueDate);
        }
        if($projectId && $projectId != 'all'){
            
            $tasksQuery->where('project_id', $projectId);
        }


        // Limit results and select relevant fields
        $tasks = $tasksQuery->take(10)->get(['id', 'name', 'description', 'status','priority','due_date', 'project_id']);
        foreach ($tasks as $task) {
            $task->project = $task->project()->get(['id', 'name']);
        }

        // Return the response as JSON
        return response()->json($tasks);
    }
}