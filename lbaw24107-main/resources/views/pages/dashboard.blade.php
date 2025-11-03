@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <h1 class="text-4xl font-semibold text-gray-900 mb-6">Dashboard</h1>

        <!-- Tasks Card -->
        <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="bg-gray-200 p-4 rounded-t-lg">
            <h2 class="text-xl font-semibold text-gray-800">Your Tasks</h2>
        </div>
        <div class="p-4">
            @if($tasks->isEmpty())
                <p class="text-gray-700">You have no tasks assigned.</p>
            @else
                <ul>
                    @foreach($tasks as $task)
                        <li class="mb-4">
                            <a href="{{ route('projects.show', $task->project_id) }}" class="block bg-gray-100 p-4 rounded-lg shadow-md hover:bg-gray-200 transition-colors duration-200">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $task->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $task->description }}</p>
                                <p class="text-sm text-gray-500">Due date: {{ $task->due_date }}</p>
                                <p class="text-sm text-gray-500">Project: {{ $task->project->name }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        </div>
    </div>
</div>
@endsection
