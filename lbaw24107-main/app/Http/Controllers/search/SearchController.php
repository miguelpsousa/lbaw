<?php

namespace App\Http\Controllers\Search;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectCategory;

class SearchController extends Controller
{
    /**
     * Displays the search page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $relatedProjects = auth()->user()->projects()->get();
        $projectCategories = ProjectCategory::all();
        $allProjects = Project::all();
        return view('pages.search', compact('relatedProjects','projectCategories','allProjects'));
    }
}
?>