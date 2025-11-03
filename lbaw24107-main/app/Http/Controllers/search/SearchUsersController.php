<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class SearchUsersController extends Controller
{
    public function search(Request $request)
    {
        // Retrieve query parameters
        $query = $request->query('query');
        $common = $request->query('common');
        $exact = $request->query('exact');
        Log::info('Common projects filter: ' . $common);


        // Start building the query
        $usersQuery = User::query();

        // Filter by search query (username)
        if ($query) {
            if($exact === 'true'){
                $usersQuery->where('username', $query);
            }
            else{
                $usersQuery->where('username', 'LIKE', "%{$query}%");
            }
        }

        // Exclude the current user
        $usersQuery->where('id', '!=', auth()->id());


        // Filter by common projects, if provided
        if ($common === 'true') {
            $authUserProjectIds = auth()->user()->projects()->pluck('id');

            $usersQuery->whereHas('projects', function ($projectQuery) use ($authUserProjectIds) {
                $projectQuery->whereIn('id', $authUserProjectIds);
            });
        }
        else if(is_numeric($common)){

            $usersQuery->whereHas('projects', function ($projectQuery) use ($common) {
                $projectQuery->where('id', $common);
            });
        }

        // Limit results and select relevant fields
        $users = $usersQuery->take(10)->get(['id', 'username', 'profile_picture']);
        Log::info('Users: ' . $users);
        // Process each user for additional formatting
        $users = $users->map(function ($user) {
            $user->profile_picture = $user->profile_picture
                ? url("public/profile_pictures/{$user->profile_picture}")
                : null; // Set a default profile picture URL if necessary

            return $user;
        });

        // Return the response as JSON
        return response()->json($users);
    }

    public function searchAcceptedUsers(Request $request)
    {
        // Get the query and projectId from the request
        $query = $request->query('query', ''); // Default to empty string if no query is provided
        $projectId = $request->query('projectId'); // Project ID to filter users by

        // Validate the input to ensure projectId is provided
        if (!$projectId) {
            return response()->json(['error' => 'Project ID is required'], 400);
        }

        // Search for users with 'accepted' invite_status
        $users = User::join('project_member', 'account.id', '=', 'project_member.user_id')
                    ->where('project_member.project_id', $projectId)
                    ->where('project_member.invite_status', 'accepted')
                    ->where('account.username', 'LIKE', "%{$query}%") // Filter by username
                    ->where('account.id', '!=', auth()->id()) // Exclude the current user
                    ->take(10) // Limit results to 10 users
                    ->get(['account.id', 'account.username']); // Select id and username

        // Return the users in a JSON response
        return response()->json($users);
    }
}

