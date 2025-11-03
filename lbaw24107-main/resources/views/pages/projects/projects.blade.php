@extends('layouts.app')
@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar (Partial) -->
    @include('partials.sidebar')

    <!-- Main Content (Your Projects) -->
    <div class="w-3/4 p-8 projects-tab" data-favorites="{{ $favorites }}">
        @if($favorites)
            <h1 class="text-3xl font-bold mb-6 text-center">Your Favorite Projects</h1>
            @php
                $projects = $favoriteProjects;
            @endphp
        @else
            <h1 class="text-3xl font-bold mb-6 text-center">Your Projects</h1>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="alert alert-danger mb-4 p-4 bg-red-100 border border-red-500 text-red-700 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        @if($projects->isEmpty())
            <p class="text-center text-gray-600">No projects available.</p>
        @else
            <!-- Project List -->
            <ul class="space-y-4">
                @foreach($projects as $project)
                    <li class="bg-white rounded-lg shadow-md relative"> <!-- Add relative positioning -->
                        <div class="p-4 bg-indigo-600 text-white rounded-t-lg">
                            <a href="{{ url('/projects/' . $project->id) }}" 
                               class="text-xl font-semibold hover:underline">
                                {{ $project->name }}
                            </a>
                        </div>
                        <div class="p-4">
                            <div class="text-sm mb-2">
                                <p><strong>Category:</strong> {{ $project->category->name }}</p>
                            </div>
                            <div class="text-sm mb-2">
                                <p><strong>Status:</strong> {{ $project->status }}</p>
                            </div>
                            <!-- Remove the star from its previous location -->
                        </div>
                        <!-- Add star in a new div with absolute positioning -->
                        <div class="absolute bottom-4 right-4 text-xl favorite-star" data-projectid="{{ $project->id }}">
                            @if($project->pivot && $project->pivot->favorite)
                                <i class="fa-solid fa-star text-yellow-500 hover:text-yellow-600"></i>
                            @else
                                <i class="fa-regular fa-star text-gray-400 hover:text-gray-500"></i>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>            
        @endif

        <!-- Create New Project Button -->
        <div class="mt-6 text-center">
            <a href="{{ url('/create-project') }}" 
               class="py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                Create New Project
            </a>
        </div>
    </div>
</div>
@endsection
