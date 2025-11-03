@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
@include('partials.sidebar')
<div class="flex-1 p-8">
    <h1 class="text-2xl font-bold mb-6 text-center">Edit Project</h1>

    <div class="max-w-2xl mx-auto p-8 bg-white rounded-lg shadow-lg">
        <form action="{{ route('projects.update', $project->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Project Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Project Name:</label>
                <input type="text" id="name" name="name" value="{{ $project->name }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                <input id="description" name="description" value="{{ $project->description }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></input>
            </div>

            <!-- Project Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Project Category:</label>
                <select name="category" id="category" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $project->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="mt-4">
                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Submit changes
                </button>
            </div>
        </form>
    </div>

</div>
</div>

@endsection