@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
@include('partials.sidebar')
<div class="flex-1 p-8">
    <h1 class="text-2xl font-bold mb-6 text-center">Create New Project</h1>

    <div class="max-w-2xl mx-auto p-8 bg-white rounded-lg shadow-lg">
        <form action="/create-project" method="POST" class="space-y-6">
            {{ csrf_field() }}

            <!-- Project Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Project Name:</label>
                <input type="text" id="name" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                <textarea id="description" name="description" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>

            <!-- Project Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Project Category:</label>
                <select name="category" id="category" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search for Users -->
            <div>
                <label for="search-users" class="block text-sm font-medium text-gray-700">Search for users:</label>
                <input type="text" id="search-users" name="search-users" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <ul id="search-results-list" class="mt-2 max-h-40 overflow-auto border border-gray-300 rounded-md">
                    <!-- Search results will appear here -->
                </ul>
            </div>

            <!-- Selected Users -->
            <div>
                <label for="selected-users" class="block text-sm font-medium text-gray-700">Selected Users:</label>
                <div id="selected-users" class="space-y-2">
                    <ul id="selected-users-list" class="space-y-2">
                        <!-- Selected users will be displayed here -->
                    </ul>
                </div>
            </div>

            <!-- Hidden input to submit selected user IDs -->
            <input type="hidden" id="selected-users-input" name="selected_users">

            <!-- Submit Button -->
            <div class="mt-4">
                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Create Project
                </button>
            </div>
        </form>
    </div>

</div>
</div>

@endsection
