@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen bg-gray-100">
        @include('partials.sidebar')

        <main class="flex-1 p-8 bg-gray-100">
            <div class="flex justify-between items-center pb-6 border-b">
                <h1 class="text-2xl font-bold">
                    {{ $task->name }} -
                    <a href="{{ url('/projects/' . $task->project->id) }}" class="text-indigo-600 hover:underline">
                        {{ $task->project->name }}
                    </a>
                </h1>

                @if ($task->responsible_id == Auth::id())
                    <a href="{{ url('/tasks/' . $task->id . '/edit') }}"
                       class="py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </a>
                @endif
            </div>

            <div class="mt-6">
                <div class="bg-white shadow-md rounded-lg">
                    <div class="bg-indigo-600 p-4 rounded-t-lg">
                        <h4 class="text-lg font-semibold text-white">{{ $task->status }}</h4>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Task Description -->
                        <p class="text-gray-700">
                            <strong>Description:</strong> {{ $task->description }}
                        </p>

                        <!-- Due Date -->
                        <p class="text-gray-700">
                            <strong>Due Date:</strong> {{ $task->due_date }}
                        </p>

                        <!-- Priority -->
                        <p class="text-gray-700">
                            <strong>Priority:</strong> {{ $task->priority }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Comments</h2>

                    @foreach($task->comments as $comment)
                        <div class="mb-4 p-4 bg-gray-100 rounded-lg" id="comment-{{ $comment->id }}">
                            <div class="flex items-center mb-2">
                                <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" alt="Profile Picture" class="w-10 h-10 rounded-full">
                                <strong class="ml-2">{{ $comment->user->username }}</strong>
                                <span class="ml-2 text-gray-500">{{ $comment->getFormattedTimestamp() }}</span>
                                @if($comment->user_id === auth()->id())
                                    <button class="ml-2 text-indigo-600 hover:text-indigo-800" id="edit-button-{{ $comment->id }}" onclick="editComment({{ $comment->id }})">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                @endif
                                @if($comment->user_id === auth()->id() || auth()->user()->is_admin)
                                    <form action="{{ route('task-comments.destroy', $comment->id) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-2 text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div id="comment-content-{{ $comment->id }}">
                                <p class="text-gray-700">{{ $comment->comment_text }}</p>
                            </div>
                        </div>
                    @endforeach

                    @auth
                        <form action="{{ route('task-comments.store') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="comment_text" class="block text-gray-700 font-bold mb-2">Add a comment:</label>
                                <textarea name="comment_text" id="comment_text" class="form-control w-full p-2 border rounded-lg" rows="3" required></textarea>
                            </div>
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <button type="submit" class="py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">Submit</button>
                        </form>
                    @endauth
                </div>
            </div>
        </main>
    </div>
@endsection