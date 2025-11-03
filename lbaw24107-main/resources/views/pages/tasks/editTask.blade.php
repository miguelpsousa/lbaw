@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    @include('partials.sidebar')

<main class="flex-1 p-8 bg-gray-100">
    <div class="border-b pb-4 mb-6">
        <h1 class="text-2xl font-bold text-center">Edit Task: {{ $task->name }}</h1>
    </div>

    <form method="POST" action="{{ route('tasks.update', $task->id) }}" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md space-y-6">
        @csrf
        @method('PUT')

        <!-- Task Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Task Name</label>
            <input type="text" id="name" name="name" value="{{ $task->name }}" required 
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" required 
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">{{ $task->description }}</textarea>
        </div>

        <!-- Due Date -->
        <div>
            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
            <input type="date" id="due_date" name="due_date" value="{{ $task->due_date }}" required 
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Priority -->
        <div>
            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
            <input type="number" id="priority" name="priority" value="{{ $task->priority }}" required min="1" 
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Update Button -->
        <div>
            <button type="submit" 
                class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                Update Task
            </button>
        </div>
    </form>
</main>

</div>
@endsection