@extends('layouts.app')

@section('content')
<div class="px-6 py-12">
    <h1 class="text-3xl font-semibold text-gray-900 mb-6">Search</h1>

    <!-- Search Form -->
    <form method="GET" class="search-form bg-white shadow-lg rounded-lg p-6 space-y-8"> <!-- Increased space between sections -->
        <!-- Search Type and Query on one line -->
        <div class="grid grid-cols-2 gap-8"> <!-- Increased gap between elements -->
            <!-- Search Type -->
            <div>
                <label for="type" class="block text-lg font-medium text-gray-700 mb-2">Search for:</label>
                <select name="type" id="type" class="form-select w-full h-14 text-lg px-4 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="users">Users</option>
                    <option value="tasks">Tasks</option>
                    <option value="projects">Projects</option>
                </select>
            </div>

            <!-- Search Query -->
            <div>
                <label for="query" class="block text-lg font-medium text-gray-700 mb-2">Search Query:</label>
                <input type="text" name="query" id="query" class="form-input w-full h-14 text-lg px-4 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter search query">
            </div>
        </div>
       <!-- Exact Match Checkbox -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" name="exact_match" id="exact_match" class="form-checkbox h-6 w-6 text-blue-500">
            <label for="exact_match" class="text-lg font-medium text-gray-700">Exact match search</label>
        </div>

        <!-- Additional Options -->
        <div class="space-y-12"> <!-- Increased spacing between options -->
            <!-- Task Specific Options -->
            <div id="task-options" class="grid grid-cols-4 gap-12" style="display: none;"> <!-- Increased gap between columns -->
                <div>
                    <label for="task_status" class="block text-lg font-medium text-gray-700 mb-2">Task Status:</label>
                    <select name="task_status" id="task_status" class="form-select w-full h-14 text-lg px-4 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All</option>
                        <option value="complete">Completed</option>
                        <option value="in-progress">In progress</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>

                <div>
                    <label for="task_project" class="block text-lg font-medium text-gray-700 mb-2">Project:</label>
                    <select name="task_project" id="task_project" class="form-select w-full h-14 text-lg px-4 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Projects</option>
                        @foreach($relatedProjects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="task_priority" class="block text-lg font-medium text-gray-700 mb-2">Task Priority:</label>
                    <input type="number" id="task_priority" name="task_priority" class="form-input w-full h-14 text-lg px-4 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" min="1" placeholder="e.g., 5">
                </div>

                <div>
                    <label for="task_due_date" class="block text-lg font-medium text-gray-700 mb-2">Before Due Date:</label>
                    <input type="date" id="task_due_date" name="task_due_date" class="form-input w-full h-14 text-lg px-4 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Project Specific Options -->
            <div id="project-options" class="grid grid-cols-4 gap-12" style="display: none;"> <!-- Increased gap between columns -->
                <div>
                    <label for="project_status" class="block text-lg font-medium text-gray-700 mb-2">Project Status:</label>
                    <select name="project_status" id="project_status" class="form-select w-full h-14 text-lg px-4 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="complete">Complete</option>
                    </select>
                </div>

                <div>
                    <label for="project_category" class="block text-lg font-medium text-gray-700 mb-2">Project Category:</label>
                    <select name="project_category" id="project_category" class="form-select w-full h-14 text-lg px-4 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Categories</option>
                        @foreach($projectCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- User Specific Options -->
            <div id="user-options" class="grid grid-cols-4 gap-12" style="display: none;"> <!-- Increased gap between columns -->
                <div>
                    <label for="common_projects" class="block text-lg font-medium text-gray-700 mb-2">Projects:</label>
                    <select name="common_projects" id="common_projects" class="form-select w-full h-14 text-lg px-4 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Projects</option>
                        <option value="common">All Common Projects</option>
                        @foreach($allProjects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>

    <!-- Search Results -->
    <div class="search-results mt-6">
        <!-- Results will go here -->
    </div>
</div>
@endsection
