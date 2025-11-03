<?php

namespace App\Http\Controllers\Projects;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    /**
     * Handle the incoming request to favorite a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toggleFavorite($projectId)
    {
        Log::info("here");
        if(!Auth::check()) {
            return response()->json(['message' => "User not logged in."], 403);
        }
        

        Log::info($projectId);
        $projectQuery = Auth::user()->projects()->where('project_id', $projectId);
        Log::info($projectQuery->first()->pivot->favorite);
        if(!$projectQuery->exists()) {
            return response()->json(['message' => "User not in project."], 403);
        }
        else{
            if($projectQuery->first()->pivot->favorite == true){
                Log::info("to false");
                $projectQuery->updateExistingPivot($projectId, ['favorite' => false]);
            }
            else{
                Log::info("to true");
                $projectQuery->updateExistingPivot($projectId, ['favorite' => true]);
            }
        }

        return response()->json(['message' => 'Favorite toggled successfully.'], 200);
    }
}