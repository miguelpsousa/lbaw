<?php

namespace App\Http\Controllers\Search;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Log;


class SearchProjectsController extends Controller
{
    public function search(Request $request)
    {
        // Retrieve query parameters
        $query = $request->query('query');
        $status = $request->query('status');
        $category = $request->query('category');
        $exact = $request->query('exact');
        Log::info('stust filter: ' . $status);
        // Start building the query
        $projectsQuery = Project::query();

        // Filter by search query (project name)
        if ($query) {
            if($exact === 'true'){
                $projectsQuery->where('name', $query);
            }
            else{
                $projectsQuery->where('name', 'LIKE', "%{$query}%");
            }
        }

        // Filter by status, if provided
        if ($status) {
            if($status == 'ongoing' || $status == 'complete'){
                $projectsQuery->where('status', $status);
            }  
        }
        if($category){
            if($category != 'all'){
                $projectsQuery->where('project_category_id', $category);

            }
            
        }

       

        // Limit results and select relevant fields
        $projects = $projectsQuery->take(10)->get(['id', 'name', 'description', 'status']);

        // Return the response as JSON
        return response()->json($projects);
    }
}